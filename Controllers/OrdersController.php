<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Http\Resources\Order\OrderAR;
use App\Http\Resources\Order\OrderEN;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\ProductAttributes;
use App\Traits\ApiResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{
    use ApiResponses;

    public function index()
    {
        $product_by_order =
            Order::with('orders_products.products_details.images')
                ->with('orders_products.products_details')
                ->with('orders_products')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
        return $this->querySuccess(OrderAR::collection($product_by_order));
    }

    public function index_en()
    {
        $product_by_order =
            Order::with('orders_products.products_details.images')
                ->with('orders_products.products_details')
                ->with('orders_products')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
        return $this->querySuccess(OrderEN::collection($product_by_order));
    }


    public function index_detail($id)
    {
        $product_by_order =
            Order::where('id','=',$id)
            ->with('orders_products.products_details.images')
                ->with('orders_products.products_details')
                ->with('orders_products')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
        return $this->querySuccess(OrderAR::collection($product_by_order));
    }

    public function index_en_detail($id)
    {
        $product_by_order =
            Order::where('id','=',$id)
            ->with('orders_products.products_details.images')
                ->with('orders_products.products_details')
                ->with('orders_products')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
        return $this->querySuccess(OrderEN::collection($product_by_order));
    }



    public function create()
    {
        return response()->json(['validator' => Order::Store_Validation()], 200);
    }


    public function create_order(Request $request)
    {
        $validator = Validator::make($request->all(), Order::Store_Validation());
        if ($validator->fails()) {
            return $this->validatorError($validator->errors());
        } else {

            $data = $request->all();
            $data['user_email'] = Auth::user()->email;
            $data['user_id'] = Auth::user()->id;
            $data['grand_total'] = $request->get('grand_total');
            $data['session_id'] = $request->get('session_id');
            $id = Order::create($data)->id;
            $order_items = Cart::where('session_id', '=', $request->get('session_id'))->get();
            if ($order_items->isEmpty()) {

                return $this->sendError('the cart is empty ', 404);
            } else {

                $order_items = $order_items->toArray();
                $inserts = [];
                foreach ($order_items as $item) {
                    $inserts[] = [

                        'order_id' => $id,
                        'user_id' => Auth::user()->id,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'product_code' => $item['product_code'],
                        'product_color' => $item['product_color'],
                        'product_size' => $item['size'],
                        'product_price' => $item['price'],
                        'product_qty' => $item['quantity']
                    ];
                }
                foreach ($inserts as $value) {
                    OrderProduct::create($value);
              if (strpos($value['product_name'], '+') == false) {
                        $oder_stock = ProductAttributes::where('product_id','=', $value['product_id'])->where('size','=', $value['product_size']);
                        $oders_stock = ProductAttributes::where('product_id','=',$value['product_id'])->where('size','=', $value['product_size'])->get();
                        if(!$oders_stock->isEmpty())
                        {
                            $stock = $oders_stock[0]['stock'] - $value['product_qty'] ;
                            $oder_stock->update(['stock' => $stock ]);
                        }
                    }

                }
                $order_id = $id;
                $created_at = date("Y-m-d H:i:s");
                $userDetailes = array(
                    'state'=>$request->get('state'),
                    'city' =>$request->get('city'),
                    'address' =>$request->get('address'),
                    'name' =>$request->get('name'),
                    'mobile' =>$request->get('mobile'),
                      );

                $productDetaiels = array(
                    'payment_method'=>$request->get('payment_method'),
                    'orders' =>$inserts,
                    'grand_total'=>$request->get('grand_total'),

                );



                Mail::to( Auth::user()->email)->send(new SendMail($order_id,$userDetailes,$productDetaiels,$created_at));

             Cart::where('session_id', $request->get('session_id'))->delete();
                $message[] = [

                     'data' => 'stored Successfully.',
                     'order_id' => $id,
                ];











                return $this->querySuccess($message);


            }
        }

    }


    public function paid(Request $request, $id)
    {
        $order = Order::where('id', $id);
        $orders = Order::where('id', $id)->get();
        if ($orders->isEmpty()) {

            return $this->sendError('there is no order ', 404);
        } else {
            $order->update(['order_status' => 'Paid']);

            return $this->querySuccess('Updated Successfully.');

        }
    }


}
