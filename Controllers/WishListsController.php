<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Http\Resources\WishList\WishAR;
use App\Http\Resources\WishList\WishEN;
use App\Product;
use App\ProductAttributes;
use App\Traits\ApiResponses;
use App\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishListsController extends Controller
{
    use ApiResponses;

    public function index_en()
    {
        $wishList = WishList::all()->where('user_email', '=', Auth::user()->email);
        if ($wishList->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $wishList = WishEN::collection(WishList::orderBy('id', 'DESC')->where('user_email', '=', Auth::user()->email)
            ->get());
            return $this->querySuccess($wishList);
        }
    }

    public function index()
    {
        $wishList = WishList::all()->where('user_email', '=', Auth::user()->email);
        if ($wishList->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $wishList = WishAR::collection(WishList::orderBy('id', 'DESC')->where('user_email', '=', Auth::user()->email)
                ->get());
            return $this->querySuccess($wishList);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), WishList::Store_Validation());
        if ($validator->fails()) {
            return $this->validatorError($validator->errors());
        } else {
            $data = $request->all();
            $data['user_email'] = Auth::user()->email;
            $productsInfo = Product::where('id', $request->get('product_id'))->get();
            $data['product_name'] = $productsInfo[0]['product_name_en'];
            $data['product_code'] = $productsInfo[0]['product_code'];
            $data['product_id'] =  $request->get('product_id');
            $data['product_color'] = $productsInfo[0]['product_color'];
            $data['size'] = "wishlist";
            $data['quantity'] = 1;
            $data['price'] = $productsInfo[0]['price'];

                WishList::create($data);
                return $this->querySuccess('stored Successfully.');

        }
    }

    public function update_by_id(Request $request, $id)
    {
        $wishList = WishList::where('id', $id);
        $product_id = WishList::where('id', $id)->get();
        $product_id = $product_id[0]['product_id'];

        $validator = Validator::make($request->all(), WishList::Update_Validation());
        if ($validator->fails()) {
            return $this->validatorError($validator->errors());
        } else {

            $data = $request->all();
            $data['user_email'] = Auth::user()->email;

            $productsInfo = Product::where('id', $product_id)->get();

            $data['price'] = $productsInfo[0]['price'] * $request->get('quantity');
            $data['product_code'] = $productsInfo[0]['product_code'];
            $data['product_name'] = $productsInfo[0]['product_name_en'];
            $data['product_id'] = $product_id;
            $data['product_color'] = $productsInfo[0]['product_color'];
            $data['size'] = " ";
            $data['price'] = $productsInfo[0]['price'];

            $wishList->update($data);
            return $this->querySuccess(WishList::where('id', $id)->get());
        }


    }

    public function delete_by_id($id)
    {
        $wishList = WishList::where('product_id', '=', $id)
                ->where('user_email','=',Auth::user()->email)->get();

        if ($wishList->isEmpty()) {

            return $this->querySuccess('could not delete');
        } else {

            WishList::where('product_id', '=', $id)
                ->where('user_email','=',Auth::user()->email)->delete();
            return $this->querySuccess('WishList has deleted Successfully');
        }
    }
}
