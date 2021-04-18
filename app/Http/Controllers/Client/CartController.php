<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Cart;
use App\Models\FishMerchant;

class CartController extends Controller
{
    private function _checkField($id, $quantity, $client_id)
    {
        $dbFishMerchant = FishMerchant::where('id',$id)->first();
        $dbCart = Cart::where('client_id',$client_id)->get();
        foreach ($dbCart as $cart) {
            if ($dbFishMerchant->merchant_id != $cart->fish_merchant->merchant_id) {
                return $messages = ['merchant'=>"Can't add product to cart from different merchant"];
            }
        }

        if ($quantity > $dbFishMerchant->quantity) {
            return $messages = ['quantity'=> 'Out of stock'];
        }        
    }
    private function _checkPreviousQuantity($quantity, $fish_merchant_id, $client_id)
    {
        $dbCart=Cart::where([['client_id',$client_id],['fish_merchant_id',$fish_merchant_id]])->first();
        if ($dbCart) {
            return $quantity + $dbCart->quantity;
        }
    }
    private function _validateData(Request $request)
    {
        return Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'fish_merchant_id'=>'required|integer|exists:fish_merchants,id',
        ]);
    }
    public function all()
    {
        $data=Cart::where('client_id',Auth::user()->id)->get();  
        if (!$data) 
            return Response::error("Data Not Found",404);

        foreach ($data as $key=> $item) {
            $loop['id']=$item->id;
            $loop['quantity']=$item->quantity;
            $loop['stock']=$item->fish_merchant->quantity;
            $loop['product_name']=$item->fish_merchant->fish->name;
            $loop['price']=$item->fish_merchant->fish->price;
            $loop['subtotal']=$item->fish_merchant->fish->price * $item->quantity;
            $data_loop[$key]=$loop;
            $merchant=$item->fish_merchant->merchant->name;    
        }
        $response=[
            'merchant'=>$merchant,
            'total'=>array_sum(array_column($data_loop,'subtotal')),
            'product'=>$data_loop
            
        ];   
        return Response::successWithData(
            'Get Data Cart Successfully',
            $response
        );
    }
    public function add(Request $r)
    {
        $validated=$this->_validateData($r);
        if ($validated->fails())
			return Response::errorWithData("Add Product To Cart Failed",
				$validated->errors(),
				422
		);
        
        //check quantity
        $client_id=Auth::user()->id;
        $quantity = $r->quantity;
        $checkQuantity=$this->_checkPreviousQuantity($quantity, $fish_merchant_id = $r->fish_merchant_id,$client_id);
        if ($checkQuantity) 
            $quantity=$checkQuantity;
        
        $checkField=$this->_checkField($fish_merchant_id, $quantity, $client_id);
        if ($checkField) {
            return Response::errorWithData('Add To Cart Failed', $checkField, 422);
        }
        
        $cart = Cart::updateOrCreate(
            ['client_id' => $client_id,'fish_merchant_id' => $fish_merchant_id],
            ['quantity' =>  $quantity]
        );

        // $dbStock=FishMerchant::select('quantity')->where('id',$fish_merchant_id)->first();
        // $stock=$dbStock->quantity-$r->quantity;
        // FishMerchant::where('id',$fish_merchant_id)->update(['quantity'=>$stock]);
        return Response::success("Add Product To Cart Successfully");
    }
    public function edit(Request $r,$id)
    {
        $client_id=Auth::user()->id;
        $data=Cart::where([['client_id',$client_id],])->get();
        if (!$data) 
            return Response::error("Data Not Found",404);
        

        $validated=$this->_validateData($r);
        if ($validated->fails())
			return Response::errorWithData("Edit Product Cart Failed",
				$validated->errors(),
				422
		);

        $checkFish=$this->_checkField($fish_merchant_id=$r->fish_merchant_id,$r->quantity,$client_id);
        if ($checkFish) {
            return Response::errorWithData('Edit Product Cart Failed',$checkFish,422);
        }

        $cart = Cart::update(
            ['fish_merchant_id' => $fish_merchant_id,'quantity' =>  $quantity]
        )->where([['client_id',$client_id],['id',$id]]);
        return Response::success("Add Product To Cart Successfully");
    }

    public function delete()
    {
        $client_id=Auth::user()->id;
        $data=Cart::where('client_id',$client_id)->get();
        if (count($data)==0) {
            return Response::error("Data Not Found", 404);
        }

        $del = Cart::where('client_id',$client_id);
        $del->delete();

		return Response::success('Delete Fish Success');
    }
}
