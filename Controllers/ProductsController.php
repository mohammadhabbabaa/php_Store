<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\Product\productAR;
use App\Http\Resources\Product\productEN;
use App\Product;

use Illuminate\Http\Request;
use App\Traits\ApiResponses;

class ProductsController extends Controller
{
    use ApiResponses;

    public function product_ar()
    {
        $products = productAR::collection(Product::where('status', '=', '1')->orderBy('id', 'desc')->paginate(25));
        return $this->querySuccess($products);
    }

    public function product_en()
    {
        $products = productEN::collection(Product::where('status', '=', '1')->orderBy('id', 'desc')->paginate(25));
        return $this->querySuccess($products);
    }

    public function product_by_category_ar($category)
    {  $categoryIds = Category::where('parent_id', $category)
                ->pluck('id')
                ->push($category)
                ->all();
        $categoryIdss = Category::whereIn('parent_id', $categoryIds)
            ->pluck('id')
            ->push($category)
            ->all();

          $product = Product::where('status', '=', '1')
            ->whereIn('category_id',$categoryIdss)->orderBy('id', 'desc')->paginate(25);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $products = productAR::collection($product);
            return $this->querySuccess($products);
        }
    }

    public function product_by_category_en($category)
    {
        $categoryIds = Category::where('parent_id', $category)
            ->pluck('id')
            ->push($category)
            ->all();
        $categoryIdss = Category::whereIn('parent_id', $categoryIds)
            ->pluck('id')
            ->push($category)
            ->all();

        $product = Product::where('status', '=', '1')
            ->whereIn('category_id',$categoryIdss)->orderBy('id', 'desc')->paginate(25);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $products = productEN::collection($product);
            return $this->querySuccess($products);
        }
    }

    public function product_by_brand_en($brand)
    {
        $product = Product::where('status', '=', '1')
            ->where('brand_id', '=', $brand)->orderBy('id', 'desc')->paginate(25);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {

            $products = productEN::collection($product);
            return $this->querySuccess($products);
        }
    }

    public function product_by_brand_ar($brand)
    {
        $product = Product::where('status', '=', '1')
            ->where('brand_id', '=', $brand)->orderBy('id', 'desc')->paginate(25);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {

            $products = productAR::collection($product);
            return $this->querySuccess($products);
        }
    }

    public function product_detail_en($id)
    {
        $product = Product::all()->where('status', '=', '1')
            ->where('id', '=', $id);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {

            $products = productEN::collection($product);
            return $this->querySuccess($products);
        }
    }

    public function product_detail_ar($id)
    {

        $product = Product::all()->where('status', '=', '1')
            ->where('id', '=', $id);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {

            $products = productAR::collection($product);
            return $this->querySuccess($products);
        }
    }

    public function product_by_filter_ar(Request $request)
    {
        $categoryIds = Category::where('parent_id',  $request->get('category'))
            ->pluck('id')
            ->push($request->get('category'))
            ->all();
        $categoryIdss = Category::whereIn('parent_id', $categoryIds)
            ->pluck('id')
            ->push($request->get('category'))
            ->all();
        if ($request->has('color') && $request->has('size')) {
            Product::with('colors')->with('attribute')->with('images');
            $product = Product::whereHas
            (
                'attribute', function ($query) use ($request) {
                $query->where('size', '=', $request->get('size'));
            })
                ->where(function ($subQuery) use ($request) {
                    $subQuery->whereHas('colors', function ($query) use ($request) {
                        $query->where('color', '=', $request->get('color'))
                      ;
                    });
                })
                ->orwhere('product_color', '=', $request->get('color'))
                ->where('status', '=', '1')
                ->whereIn('category_id',$categoryIdss)
                ->get();

            if ($product->isEmpty()) {
                return $this->sendError("Not Found", 404);
            } else {
                $products = productAR::collection($product);
                return $this->querySuccess($products);
            }

        } elseif ($request->has('size')) {
            Product::with('colors')->with('attribute')->with('images');
            $product = Product::whereHas
            (
                'attribute', function ($query) use ($request) {
                $query->where('size', '=', $request->get('size'));
            })
                ->where('status', '=', '1')
                ->whereIn('category_id',$categoryIdss)->get();
            if ($product->isEmpty()) {
                return $this->sendError("Not Found", 404);
            } else {

                $products = productAR::collection($product);
                return $this->querySuccess($products);
            }

        } elseif ($request->has('color')) {

            $product = Product::whereHas
            (
                'colors', function ($query) use ($request) {
                $query->where('color', '=', $request->get('color'));
            })->where('status', '=', '1')
                ->orwhere('product_color', '=', $request->get('color'))
                ->whereIn('category_id',$categoryIdss)->get();
            if ($product->isEmpty()) {
                return $this->sendError("Not Found", 404);
            } else {
                $products = productAR::collection($product);
                return $this->querySuccess($products);
            }

        }
        else{
            $product= Product::with('colors')->with('attribute')->with('images')->get();
            $products = productAR::collection($product);
            return $this->querySuccess($products);

    }
    }

    public function product_by_filter_en(Request $request)
    { $categoryIds = Category::where('parent_id',  $request->get('category'))
        ->pluck('id')
        ->push($request->get('category'))
        ->all();
        $categoryIdss = Category::whereIn('parent_id', $categoryIds)
            ->pluck('id')
            ->push($request->get('category'))
            ->all();



        if ($request->has('color') && $request->has('size')) {
        Product::with('colors')->with('attribute')->with('images');
        $product = Product::whereHas
        (
            'attribute', function ($query) use ($request) {
            $query->where('size', '=', $request->get('size'));
        })
            ->where(function ($subQuery) use ($request) {
                $subQuery->whereHas('colors', function ($query) use ($request) {
                    $query->where('color', '=', $request->get('color'))
                  ;
                });
            })
            ->orwhere('product_color', '=', $request->get('color'))
            ->where('status', '=', '1')
            ->whereIn('category_id',$categoryIdss)
            ->get();

        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $products = productEN::collection($product);
            return $this->querySuccess($products);
        }

    } elseif ($request->has('size')) {
        Product::with('colors')->with('attribute')->with('images');
        $product = Product::whereHas
        (
            'attribute', function ($query) use ($request) {
            $query->where('size', '=', $request->get('size'));
        })
            ->where('status', '=', '1')
            ->whereIn('category_id',$categoryIdss)->get();
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {

            $products = productEN::collection($product);
            return $this->querySuccess($products);
        }

    } elseif ($request->has('color')) {

        $product = Product::whereHas
        (
            'colors', function ($query) use ($request) {
            $query->where('color', '=', $request->get('color'));
        })->where('status', '=', '1')
            ->orwhere('product_color', '=', $request->get('color'))
            ->whereIn('category_id',$categoryIdss)->get();
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $products = productEN::collection($product);
            return $this->querySuccess($products);
        }

    }
    else{
        $product= Product::with('colors')->with('attribute')->with('images')->get();
        $products = productEN::collection($product);
        return $this->querySuccess($products);

}






    }

    public function feature_en()
    {
        $product = Product::where('status', '=', '1')
            ->where('feature_item', '=', '1')->orderBy('id', 'desc')->paginate(25);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {

            $products = productEN::collection($product);
            return $this->querySuccess($products);
        }
    }

    public function feature_ar()
    {
        $product = Product::where('status', '=', '1')
            ->where('feature_item', '=', '1')->orderBy('id', 'desc')->paginate(25);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {

            $products = productAR::collection($product);
            return $this->querySuccess($products);
        }
    }

    public function random_en()
    {
        $product = Product::all()->where('status', '=', '1')
            ->random(3);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $products = productEN::collection($product);
            return $this->querySuccess($products);
        }
    }

    public function random_ar()
    {

        $product = Product::all()->where('status', '=', '1')
            ->random(3);
        if ($product->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {

            $products = productAR::collection($product);
            return $this->querySuccess($products);
        }
    }


}
