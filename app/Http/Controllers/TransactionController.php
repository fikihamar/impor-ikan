<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\FishMerchant;
use App\Models\Fish;
use App\Http\Controllers\Helper\Response;
use App\Http\Controllers\Helper\TransactionHelper ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TransactionResource;
class TransactionController extends Controller
{
    private function _validateData(Request $request)
    {
        return Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.fish_id' => 'required|integer|exists:fish_merchants,id',
            'products.*.quantity' => 'required|integer',
            'address' => 'required|string',
            'note' => 'required|string',
        ]);
    }
    private function _uniqueCode($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function dataMerchant()
    {
        $id = Auth::user('merchant')->id;

        $data = Transaction::where('merchant_id',$id)
            ->latest()
            ->get();
        
        if (!$data)
            return Response::error('Data Not Found',404);

        return Response::successWithData('Data All Transaction', $data);
    }
    public function detailMerchant($id)
    {
        $data = Transaction::where('id', $id)
            ->with('transaction_detail')
            ->first();
        if (!$data) {
            return Response::error('Data Not Found',404);
        }
        $subtotal = 0;

        foreach ($data->transaction_detail as $key => $item) {
            $subtotal_merchant = $item->fish_merchant->fish->price * $item->quantity;
            $subtotal += $subtotal_merchant;

            $data->transaction_detail[$key]->subtotal = $subtotal_merchant;
            $data->transaction_detail[$key]->fish_merchant = $item->fish_merchant;
            
            $data->transaction_detail[$key]->fish_merchant->fish = $item->fish_merchant->fish;
        }

        $data->subtotal = $subtotal;
        $data->proof_image = env('APP_URL') ."/proof_images/".$data->proof_image;
        return Response::successWithData('Detail Checkout',$data);
    }
    
    public function data()
    {
        $id = Auth::user('client')->id;
        $data = Transaction::where('client_id',$id)->latest()->get();
        if (!$data)
            return Response::error('Data Not Found',404);
            
        return Response::successWithData('All Data Checkout',$data);
    }
    public function detail($id)
    {
        $data = Transaction::find($id);
        if (!$data)
            return Response::error('Data Not Found',404);
         
        $subtotal = 0;

        foreach ($data->transaction_detail as $key => $item) {
            $subtotal_merchant = $item->fish_merchant->fish->price * $item->quantity;
            $subtotal += $subtotal_merchant;
    
            $data->transaction_detail[$key]->subtotal = $subtotal_merchant;
            $data->transaction_detail[$key]->fish_merchant = $item->fish_merchant;
                
            $data->transaction_detail[$key]->fish_merchant->fish = $item->fish_merchant->fish;
            }
    
            $data->subtotal = $subtotal;            
        
        return Response::successWithData('Detail Checkout Client',$data);
    }
    
    public function changeStatus(Request $r,$id)
    {
        $validated = Validator::make($r->all(), [
            'status' => 'required|string'
        ]);

        if ($validated->fails()) 
            return Response::errorWithData("Upload Proof Image Failed",
				$validated->errors(),
				422
		    );

        $data = TransactionHelper::change_status_transaction($id,$r->status);

        if ($data === 400) {
            return Response::error('Change Status Failed',412);
        }

        return Response::success('Change Status Success');
    }
    public function cancel($id)
    {
        $client_id = Auth::user('client')->id;
        $data = Transaction::find($id);
        if(!$data || $data->client_id != $client_id)
            return Response::error('Data Not Found',404);
          
        if (!in_array($data->status, ['WP', 'CP'])) {
            return Response::error('Order Canceled Failed', 412);
        }

        $data->status = 'OC';
        $data->save();

        foreach($data->transaction_detail as $stock){
            $quantity = $stock->quantity;

            $restoreStock = FishMerchant::find($stock->fish_merchant_id);
            $stockBefore = $restoreStock->quantity;
            $restoreStock->quantity = $quantity + $stockBefore;

            $restoreStock->save();
        }
        return Response::success('Change Status Success');
    }
    public function cancelMerchant($id)
    {
        $merchant_id = Auth::user('merchant')->id;
        $data = Transaction::find($id);
        if(!$data || $data->merchant_id != $merchant_id)
            return Response::error('Data Not Found', 404);

        if (!in_array($data->status, ['WP', 'CP','OP','SO'])) {
            return Response::error('Order Canceled Failed', 412);
        }

        $data->status = 'OC';
        $data->save();

        foreach($data->transaction_detail as $stock){
            $quantity = $stock->quantity;

            $restoreStock = FishMerchant::find($stock->fish_merchant_id);
            $stockBefore = $restoreStock->quantity;
            $restoreStock->quantity = $quantity + $stockBefore;

            $restoreStock->save();
        }
        return Response::success('Change Status Success');
    }
    public function checkout(Request $r)
    {   
        $validated = $this->_validateData($r);
        if ($validated->fails())
			return Response::errorWithData("Checkout Failed",
				$validated->errors(),
				422
		);

        foreach ($r['products'] as $item) {
            $fish_id = $item['fish_id'];
        }

        $merchant = FishMerchant::find($fish_id);
        $code = $this->_uniqueCode(6);
        $transaction = Transaction::insert([
            'code' => $code,
            'date' => date('Y-m-d H:i:s'),
            'address' => $r['address'],
            'status' => 'WP',
            'note' => $r['note'],
            'merchant_id' => $merchant->merchant_id,
            'client_id' => Auth::user('client')->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

       $transaction_id = Transaction::select('id')->where('code',$code)->first();
        
        foreach ($r['products'] as $detail) {
            $transaction_detail = TransactionDetail::insert([
                'quantity' => $detail['quantity'],
                'transaction_id' => $transaction_id->id,
                'fish_merchant_id' => $detail['fish_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }  
        return Response::success('Checkout Success');
    }
    public function uploadImage(Request $r,$id)
    {
        $file=$r->gambar;
        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $fileInfo = pathinfo($file_name);
        $filename = $fileInfo['filename'];
        $newname = $filename .'_'. time() . "." . $file_ext;
        
        $tujuan_upload = 'proof_images';
        
        $client_id = Auth::user('client')->id;
        $data = Transaction::find($id);
        if(!$data || $data->client_id != $client_id)
            return Response::error('Data Not Found', 404);

        if($data->proof_image)
            unlink(public_path($tujuan_upload.'/'.$data->proof_image));

        $data->proof_image = $newname;
        $data->status = 'CP';
        $data->save();
        $file->move($tujuan_upload,$newname);

        return Response::success('Upload Confirm Payment Success');

    }
}
