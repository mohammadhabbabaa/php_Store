<?php

namespace App\Http\Resources\Product;
use \App\Http\Resources\Product\productEN;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductTogetherEN extends JsonResource
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
            'product_one' => productEN::collection($this->product_one),
            'product_2' =>productEN::collection($this->product_2) ,
            'price' => $this->price,

        ];
    }
}
