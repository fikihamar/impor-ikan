<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $item_image=Fish::where('id', $this->fish_merchant->fish->fish_image->filename);
        // $image=env('APP_URL') . '/fish_images/' .$item_image;
        // if (!$item_image) {
        //     $image = null;
        // }
        return [
            'id' => $this->id,
            'name' => $this->transaction_detail->quantity,
            // 'name' => $this->transaction_detail->quantity
            // 'price' => $this->fish->price,
            // 'description' => $this->fish_merchant->fish->description,
            // 'type' => $this->fish_merchant->fish->type,
        ];
    }
}
