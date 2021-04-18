<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FishMerchant;
use App\Models\FishImage;
use App\Models\Review;
use App\Models\Client;
use App\Http\Controllers\Helper\Response;
use App\Http\Resources\FishResource;

class FishController extends Controller
{
    private function _getOne($id)
    {
        return FishMerchant::find($id);
    }
    public function all()
    {   
        return Response::successWithData('Data Fish All',FishResource::collection(FishMerchant::where('quantity','<>',0)->get()));
    }
    public function detail($id)
    {
        $data = $this->_getOne($id);
	if (!$data)
		return Response::error("Data Not Found", 404);
        
	$data_image=[];
	foreach ($data->fish->fish_image as $key => $item) {
            $item_image=env('APP_URL') . '/fish_images/' .$item->filename;
            if (!$item->filename) {
                $item_image=null;
            }
            $image['filename']= $item_image;
            $image['main_image']=$item->main_image;
            $data_image[$key]=$image;
        }
        $response=[
            'id' => $data->id,
            'name' => $data->fish->name,
            'price' => $data->fish->price,
            'quantity' => $data->quantity,
            'description'=>$data->fish->description,
            'type'=>$data->fish->type,
            'min_order'=>$data->fish->min_order,
            'price'=>$data->fish->price,
            'images' => $data_image,
            'merchant_id'=>$data->merchant->id,
            'merchant_star'=>$data->merchant->review->avg('star'),
            'merchant_name'=>$data->merchant->name,
            'merchant_city'=>$data->merchant->city->name,
            'merchant_province'=>$data->merchant->city->province->name,
            'review' =>  $review=Review::select('star','description','created_at','updated_at','client_id')->with(array('client' => function($query) {
                $query->select('id','name');
            }))->where('merchant_id',$data->merchant_id)->get(),
            'created_at'=> $data->created_at,
            'updated_at'=>$data->updated_at
        ];
            
		return Response::successWithData(
			"Get Detail Fish Data Successfully",
			$response
		);
    }
    public function get_by_merchant($id)
    {
        $data=FishMerchant::where('merchant_id',$id)->get();  
        if (!$data) 
            return Response::error("Data Not Found",404);

        $item_image=FishImage::where('main_image','1')->where('id',$data[0]->fish_id)->pluck('filename')->first();
        $image=env('APP_URL') . '/fish_images/' .$item_image;
        if (!$item_image) {
            $image = null;
        }
        foreach ($data as $key=> $item) {
            $loop['id']=$item->id;
            $loop['name']=$item->fish->name;
            $loop['price']=$item->fish->price;
            $loop['image']= $image;
            $loop['type']=$item->fish->type;
            $loop['min_order']=$item->fish->min_order;
            $data_loop[$key]=$loop;
            $merchant_id=$item->merchant->id;
            $merchant_name=$item->merchant->name;
            $merchant_city=$item->merchant->city->name;
            $merchant_province=$item->merchant->city->province->name;
            $merchant_star=$item->merchant->review->avg('star');    
        }
        $response=[
            'merchant_id'=>$merchant_id,
            'merchant_name'=>$merchant_name,
            'merchant_star'=>$merchant_star,
            'merchant_city'=>$merchant_city,
            'merchant_province'=>$merchant_province,
            'product'=>$data_loop
            
        ];   
        return Response::successWithData(
            'Get Data Fish By Merchant Successfully',
            $response
        );
    }
}
