<?php

namespace App\Http\Resources\WishList;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsAR extends JsonResource
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
            'title' => $this->product_name_ar,
            'description' => $this->description_ar,
            'image' => $this->image,
            'weight' => $this->weight,
            'product_code' => $this->product_code,
            'images' => $this->images,
         ];
    }
}
