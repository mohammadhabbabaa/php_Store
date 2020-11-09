<?php

namespace App\Http\Resources\WishList;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsEN extends JsonResource
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
            'title' => $this->product_name_en,
            'description' => $this->description_en,
            'image' => $this->image,
            'product_code' => $this->product_code,
            'weight' => $this->weight,
            'images' => $this->images,
        ];
    }
}
