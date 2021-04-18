<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use App\Models\FishMerchant;
use App\Models\Fish;
use App\Models\FishImage;

class ProductController extends Controller
{
    private function _getMerchant()
    {
        return FishMerchant::with('fish')->where('merchant_id',Auth::user('merchant')->id);
    }

    private function _validateData($request)
  {
    return Validator::make($request->all(), [
		'fish_id' => 'required|integer|exists:fish,id',
		'quantity'=>'required|integer',
	]);
  }

  private function _getOne($id)
  {
	  return $this->_getMerchant()->find($id);
  }

  public function all() {
	return Response::successWithData('Data Fish All',ProductResource::collection(FishMerchant::where('merchant_id',Auth::user('merchant')->id)->get()));
	}

	public function detail($id) {
		$data = $this->_getOne($id);
		if (!$data)
			return Response::error("Data Not Found", 404);

        foreach($data->fish->fish_image AS $index => $image){
  			$loop['filename'] = env('APP_URL') ."/fish_images/".$image->filename;
			$loop['main_image'] = $image->main_image;
			$data_image[$index] =$loop;
		}	
        $response=[
            'id' => $data->id,
            'name' => $data->fish->name,
            'quantity' => $data->quantity,
            'images' => $data_image,
            'created_at'=> $data->created_at,
            'updated_at'=>$data->updated_at
        ];
        
            
		return Response::successWithData(
			"Get Detail Products Data Successfully",
			$response
		);
	}
	public function add(Request $r)
	{
        $validated = $this->_validateData($r);

		if ($validated->fails())
			return Response::errorWithData("Add Product Failed",
				$validated->errors(),
				422
			);
        
        $check=$r->only('fish_id');   

        $checkInTableFishMerchant=$this->_getMerchant()->where('fish_id',$check)->first();
        if ($checkInTableFishMerchant) {
            return Response::error("Fish is already in your product", 422);
        }
        
		$fish = FishMerchant::insert([
			'fish_id' => $r->fish_id,
            'merchant_id'=>Auth::user()->id,
			'quantity' => $r->quantity,
		]);

		return Response::success("Add Product Successfully");

	}
	public function edit(Request $r,$id)
	{
		$data = $this->_getOne($id);
		if (!$data)
			return Response::error("Data Not Found", 404);

        $check=$r->only('fish_id');
        
        $checkInTableFishMerchant=$this->_getMerchant()->Where('fish_id',$check)->where('id','<>',$id)->first();
        if ($checkInTableFishMerchant) {
            return Response::error("Fish Id is already in table your product", 422);
        }
            
		$validated = $this->_validateData($r);
		if ($validated->fails())
			return Response::errorWithData("Edit Product Failed",
				$validated->errors(),
				422
			);

		$fish = $this->_getMerchant()->where('id',$id)->update([
			'fish_id' => $r->fish_id,
			'quantity' => $r->quantity,
		]);

		return Response::success("Edit Product Successfully");

	}
	public function delete($id)
	{
		$data=$this->_getOne($id);
		if (!$data) 
			return Response::error('Data Not Found',404);
		
		$data->delete();
		return Response::success('Delete Fish Success');
	}
	public function fish_data()
	{
		return Response::successWithData("Get Fish Data Successcully",Fish::all());
	}
}
