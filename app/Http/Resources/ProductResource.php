<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FishImage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $item_image=FishImage::where([['main_image',1],['fish_id',$this->fish_id]])->pluck('filename')->first();
        $image= env('APP_URL') ."/fish_images/" .$item_image;
        if (!$item_image) {
            $image = null;
        }
        return [
            'id' => $this->id,
            'name' => $this->fish->name,
            'quantity' => $this->quantity,
            'images' => $image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
