<?php

namespace App\Http\Resources\WishList;
use App\Http\Resources\WishList\ProductDetailsAR;
use Illuminate\Http\Resources\Json\JsonResource;

class WishAR extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_detail' =>ProductDetailsAR::collection($this->product_detail) ,
            'product_color' => $this->product_color,
            'size' => $this->size,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'user_email' => $this->user_email,
        ];
    }
}
