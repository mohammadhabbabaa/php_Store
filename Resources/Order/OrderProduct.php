<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Product\productAR;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProduct extends JsonResource
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
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'product_code' => $this->product_code,
            'product_color' => $this->product_size,
            'product_size' => $this->product_size,
            'product_qty' => $this->product_price,
            'product_price' => $this->product_price,
            'products_images' => $this->products_images,

        ];
    }
}
