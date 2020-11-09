<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Coupon;
use App\Http\Resources\Cart\CartAR;
use App\Http\Resources\Cart\CartEN;
use App\Http\Resources\Coupon\CouponResource;
use App\Product;
use App\ProductsTogether;
use App\ProductAttributes;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use ApiResponses;

    public function index_en(Request $request)
    {
        $validator = Validator::make($request->all(), Cart::session_Validation());
        if ($validator->fails()) {
            return $this->validatorError($validator->errors());
        } else {
            $cart = Cart::all()
                ->where('session_id', '=',
                    $request->get('session_id'));
            if ($cart->isEmpty()) {
                return $this->sendError("Not Found", 404);
            } else {
                $cart = CartEN::collection(Cart::all()->where('session_id', '=', $request->get('session_id')));
                return $this->querySuccess($cart);
            }
        }

    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), Cart::session_Validation());
        if ($validator->fails()) {
            return $this->validatorError($validator->errors());
        } else {
            $cart = Cart::all()
                ->where('session_id', '=', $request->get('session_id'));
            if ($cart->isEmpty()) {
                return $this->sendError("Not Found", 404);
            } else {
                $cart = CartAR::collection(Cart::all()->where('session_id', '=', $request->get('session_id')));
                return $this->querySuccess($cart);
            }
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Cart::Store_Validation());
        if ($validator->fails()) {
            return $this->validatorError($validator->errors());
        } else {
            $data = $request->all();

            $productsInfo = Product::where('id', $request->get('product_id'))->get();
            $data['price'] =$request->get('price'); 
                  if ($productsInfo[0]['price']>0)
                {
                    $data['price'] = $request->get('price');  
                }
                  $data['product_code'] = $productsInfo[0]['product_code'];
                $data['product_name'] = $productsInfo[0]['product_name_en'];
                $data['quantity'] = $request->get('quantity');
                if ($request->hasAny('user_email')) {
                    $data['user_email'] = $request->get('user_email');;
                } else {
                    $data['user_email'] = '';
                }
                Cart::create($data);
                return $this->querySuccess('stored Successfully.');

        }
    }

    public function store_together(Request $request)
    {
        $validator = Validator::make($request->all(), Cart::Store_Validation());
        if ($validator->fails()) {
            return $this->validatorError($validator->errors());
        } else {

            $productsInfo  =ProductsTogether::where('id','=',$request->get('product_id'))
                 ->with('product_one')
                    ->with('product_2')
                    ->get();

              $data = $request->all();
            $arr1= $productsInfo[0]['product_one'] ;
            $arr2 =$productsInfo[0]['product_2'] ;
                $data['price'] = $productsInfo[0]['price'] ;

                $data['product_code'] =' ';

                $data['product_name'] =$arr1[0]['product_name_en'] . " + " . $arr2[0]['product_name_en']   ;
                $data['quantity'] = $request->get('quantity');
                if ($request->hasAny('user_email')) {
                    $data['user_email'] = $request->get('user_email');;
                } else {
                    $data['user_email'] = '';
                }
                Cart::create($data);
                return $this->querySuccess('stored Successfully.');

        }
    }

    public function update_by_id(Request $request, $id)
    {
        $wishList = Cart::where('id', $id);
        $_cart = Cart::where('id', $id)->get();
        $validator = Validator::make($request->all(), Cart::Update_Validation());
        if ($validator->fails()) {
            return $this->validatorError($validator->errors());
        } else {
            if ($_cart->isEmpty()) {
                return $this->sendError('There is Not Product like that.', 404);
            } else {
                $product_id = $_cart[0]['product_id'];
                    $stock = ProductAttributes::where('product_id', $product_id)->get();
                if (!$stock->isEmpty() && $stock[0]['stock'] >= $request->get('quantity')) {
                    $data = $request->all();
                    if ($request->hasAny('user_email')) {
                        $data['user_email'] = $request->get('user_email');;
                    } else {
                        $data['user_email'] = $_cart[0]['user_email'];;
                    }
                    $productsInfo = Product::where('id', $product_id)->get();
                    $data['price'] = $productsInfo[0]['price_b_discount'] ;


                    if($productsInfo[0]['price'] > 0)
                    {
                        $data['price'] = $productsInfo[0]['price'] ;
                    }
                    $data['product_code'] = $productsInfo[0]['product_code'];
                    $data['product_name'] = $productsInfo[0]['product_name_en'];
                    $data['product_id'] = $product_id;
                    $data['quantity'] = $request->get('quantity');

                    $wishList->update($data);
                    return $this->querySuccess('Updated Successfully');
                } else {
                    return $this->sendError('The Product Count is Not Enough.', 404);
                }
            }
        }
    }

    public function delete_by_id($id)
    {
        $wishList = Cart::all()->where('id', '=', $id);
        if ($wishList->isEmpty()) {
            return $this->querySuccess('could not delete');
        } else {
            Cart::where('id', $id)->delete();
            return $this->querySuccess('Cart has deleted Successfully');
        }
    }
    public function grand_total(Request $request)
    {
     //   $costs = Cart::where('session_id', $request->get('session_id'))->sum('price');
        $cost = Cart::where('session_id', $request->get('session_id'))->get();
        if ($cost->isEmpty()) {
            return $this->querySuccess('not found');
        } else {
            $costs = $cost->toArray();
            $data = 0;
            foreach ($costs as $item) {

                $data = $data + ($item['price'] * $item['quantity']);

            }
            return response()->json(['total' => $data], 200);
        }
    }
    public function coupon(Request $request)
    {
        $coupon = Coupon::where('coupon_code', '=',$request->get('coupon_code'))
            ->where('status','=','1')
            ->where('expiry_date','>=',date('Y-m-d'))
              ->get();

        if ($coupon->isEmpty()) {
            return $this->querySuccess('not found');
        } else {

            return $this->querySuccess(CouponResource::collection($coupon));
        }
    }
}
