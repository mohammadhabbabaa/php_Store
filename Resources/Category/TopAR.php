<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\SubAR;
class TopAR extends JsonResource
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title_ar,
            'description' => $this->description_ar,
            'image' => $this->image,
            'sub'=> SubAR::collection($this->sub_category),
       
        ];
    }

}
