<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Review;
use App\Models\Merchant;

class ReviewController extends Controller
{
    private function _getClient()
    {
        return Auth::user()->id;
    }
    private function _getMerchant($id)
  {
	  return Merchant::find($id);
  }
  private function _getOne($id)
  {
	  return Review::find($id);
  }
    public function add(Request $r,$id)
    {
        $data=$this->_getMerchant($id);
        if(!$data)
            return Response::error('Merchant Id Not Found',422);

        $validated = Validator::make($r->all(), [
			'star'=> 'required|integer|max:5',
			'description' => 'required|string',
		]);
        if ($validated->fails())
			return Response::errorWithData("Add Review Failed",
				$validated->errors(),
				422
			);
        
        $review = Review::create([
			'star' => $r->star,
			'description' => $r->description,
			'merchant_id' => $id,
			'client_id' => $this->getClient()
		]);

        return Response::success("Add Review Successfully");
    }
    public function detail($id)
    {
        $data = $this->_getOne($id);
		if (!$data)
			return Response::error("Data Not Found", 404);
        
        // $response=[
        //     'id' => $data->id,
        //     'name' => $data->fish->name,
        //     'quantity' => $data->quantity,
        //     'images' => $data->fish->fish_image,
        //     'created_at'=> $data->created_at,
        //     'updated_at'=>$data->updated_at
        // ];
        
            
		return Response::successWithData(
			"Get Detail Fish Data Successfully",
			$data
		);
    }
    public function edit(Request $r,$id)
    {
        $data=$this->_getOne($id);
        if(!$data)
        return Response::error('Data Not Found',404);

        $dataByClientId=Review::where('client_id',$this->_getClient())->find($id);
        if (!$dataByClientId)
            return Response::error('Data Not Found',404);
            

        $validated = Validator::make($r->all(), [
			'star'=> 'required|integer|max:5',
			'description' => 'required|string',
		]);
        if ($validated->fails())
			return Response::errorWithData("Add Review Failed",
				$validated->errors(),
				422
			);
        
        
        $review = $dataByClientId->update([
			'star' => $r->star,
			'description' => $r->description
		]);

        return Response::success("Edit Review Successfully");
    }

    public function delete($id)
    {
        $data=$this->_getOne($id);
        if(!$data)
        return Response::error('Data Not Found',404);

        $dataByClientId=Review::where('client_id',$this->_getClient())->find($id);
        if (!$dataByClientId)
            return Response::error('Data Not Found',404);
        
        $data->delete();
        return Response::success('Delete Review Success');
    }
}
