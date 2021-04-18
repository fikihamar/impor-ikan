<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Review;
use App\Models\FishImage;
class FishResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $item_image=FishImage::where('main_image','1')->where('id',$this->fish_id)->pluck('filename')->first();
        $image= env('APP_URL') . "/fish_images/" .$item_image;
        if (!$item_image) {
            $image = null;
        }
        return [
            'id' => $this->id,
            'name' => $this->fish->name,
            'price' => $this->fish->price,
            'description' => $this->fish->description,
            'type' => $this->fish->type,
            'min_order' => $this->fish->min_order,
            'quantity' => $this->quantity,
            'images' => $image,
            'merchant_id' => $this->merchant->id,
            'merchant_name' => $this->merchant->name,
            'merchant_star' => Review::where('merchant_id',$this->merchant->id)->avg('star'),
            'merchant_city' => $this->merchant->city->name,
            'merchant_province' => $this->merchant->city->province->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
