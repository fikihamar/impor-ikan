<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Helper\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\FishImage;

class FishImageController extends Controller
{
    private function _validateData($request)
  {
	  return Validator::make($request->all(), [
		'filename' => 'required|string|unique:fish_merchants',
		'main_image'=>'required|integer',
	]);
  }

  private function _getOne($id)
  {
	  return FishImage::with('fish')->find($id);
  }
  public function all() {
	$data=Cart::where('client_id',Auth::user()->id)->get();  
	foreach ($data as $key=> $item) {
		$loop['id']=$item->id;
		$loop['quantity']=$item->quantity;
		$loop['product_name']=$item->fish_merchant->fish->name;
		$loop['price']=$item->fish_merchant->fish->price;
		$loop['total']=$item->fish_merchant->fish->price * $item->quantity;
		$data_loop[$key]=$loop;
	}
return Response::successWithData(
	'Get Data Cart Successfully',
	$data_loop
);
	}
}
