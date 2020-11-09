<?php

namespace App\Http\Resources\Shipping;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingResouce extends JsonResource
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
            'country' => $this->country,
            'shipping_charges0_500g' => $this->shipping_charges0_500g ,
            'shipping_charges501_1000g' => $this->shipping_charges501_1000g ,
            'shipping_charges1001_2000g' => $this->shipping_charges1001_2000g ,
            'shipping_charges2001g_5000g' => $this->shipping_charges2001g_5000g ,

        ];
    }
}
