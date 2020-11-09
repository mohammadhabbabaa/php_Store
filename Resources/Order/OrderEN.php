<?php

namespace App\Http\Resources\Order;

use \App\Http\Resources\Order\ordersproductsEN;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderEN extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'country' => $this->country,
            'mobile' => $this->mobile,
            'shipping_charges' => $this->shipping_charges,
            'coupon_code' => $this->coupon_code,
            'coupon_amount' => $this->coupon_amount,
            'payment_method' => $this->payment_method,
            'grand_total' => $this->grand_total,
            'order_status' => $this->order_status,
            'ordered_products' =>ordersproductsEN::collection($this->orders_products),


        ];
    }
}
