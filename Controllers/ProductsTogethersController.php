<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductTogether;
use App\Http\Resources\Product\ProductTogetherEN;
use App\Product;
use App\ProductsTogether;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class ProductsTogethersController extends Controller
{
    use ApiResponses;

    public function products()
    {   $product = ProductTogether::collection(
            ProductsTogether::with('product_one.attribute')
                ->with('product_one.colors')
                ->with('product_one.images')
                ->with('product_one')
                ->with('product_2.attribute')
                ->with('product_2.colors')
                ->with('product_2.images')
                ->with('product_2')
                ->get()
        );
        return $this->querySuccess($product);
    }
    public function products_en()
    {   $product = ProductTogetherEN::collection(
            ProductsTogether::with('product_one.attribute')
                ->with('product_one.colors')
                ->with('product_one.images')
                ->with('product_one')
                ->with('product_2.attribute')
                ->with('product_2.colors')
                ->with('product_2.images')
                ->with('product_2')
                ->get()
        );
        return $this->querySuccess($product);
    }

    public function products_detail($id)
    {   $product = ProductTogether::collection(
            ProductsTogether::where('id','=',$id)
            ->with('product_one.attribute')
                ->with('product_one.colors')
                ->with('product_one.images')
                ->with('product_one')
                ->with('product_2.attribute')
                ->with('product_2.colors')
                ->with('product_2.images')
                ->with('product_2')
                
                ->get()
        );
        return $this->querySuccess($product);
    }
    public function products_en_detail($id)
    {   $product = ProductTogetherEN::collection(
            ProductsTogether::where('id','=',$id)
            ->with('product_one.attribute')
                ->with('product_one.colors')
                ->with('product_one.images')
                ->with('product_one')
                ->with('product_2.attribute')
                ->with('product_2.colors')
                ->with('product_2.images')
                ->with('product_2')
                ->get()
        );
        return $this->querySuccess($product);
    }


}
