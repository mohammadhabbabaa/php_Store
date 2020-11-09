<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductDetailAR extends JsonResource
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
            'title' => $this->product_name_ar,
            'product_code' => $this->product_code,
            'description' => $this->description_ar,
            'image' => $this->image,
        ];
    }
}
