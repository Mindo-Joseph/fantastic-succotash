<?php

namespace App\Http\Controllers\Api\v1;
use DB;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderStoreRequest;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, Product, OrderProductAddon, ClientPreference};
class OrderController extends Controller{

    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getOrdersList(Request $request){
    	$user = Auth::user();
    	$orders = Order::with('products')->where('user_id', $user->id)->paginate(10);
    	return $this->successResponse($orders, 'Order placed successfully.', 201);
    }

    public function postOrderDetail(Request $request){
    	try {
    		$order_id = $request->order_id;
	    	$order = Order::with('products')->findOrFail($order_id);
	    	return $this->successResponse($order, null, 201);
    	} catch (Exception $e) {
    		return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
    }

    public function postPlaceOrder(OrderStoreRequest $request){
    	try {
    		$user = Auth::user();
    		if($user){
    			DB::beginTransaction();
    			$client_preference = ClientPreference::first();
    			if($client_preference->verify_email == 1){
	    			if($user->is_email_verified == 0){
	    				return response()->json(['error' => 'Your account is not verified.'], 404);
	    			}
    			}
    			if($client_preference->verify_phone == 1){
	    			if($user->is_phone_verified == 0){
	    				return response()->json(['error' => 'Your phone is not verified.'], 404);
	    			}
    			}
	    		$cart = Cart::where('user_id', $user->id)->first();
		        $order = new Order;
		        $order->user_id = $user->id;
		        $order->order_number = generateOrderNo();
		        $order->address_id = $request->address_id;
		        $order->payment_option_id = $request->payment_option_id;
		        $order->save();
		        $cart_products = CartProduct::with('product.pimage', 'product.variants')->where('cart_id', $cart->id)->get();
	            foreach ($cart_products as $cart_product) {
	                $variant = $cart_product->product->variants->where('id', $cart_product->variant_id)->first();
	                $order_product = new OrderProduct;
	                $order_product->order_id = $order->id;
	                $order_product->price = $variant->price;
	                $order_product->quantity = $cart_product->quantity;
	                $order_product->vendor_id = $cart_product->vendor_id;
	                $order_product->product_id = $cart_product->product_id;
	                $order_product->created_by = $cart_product->created_by;
	                $order_product->variant_id = $cart_product->variant_id;
	                $order_product->product_name = $cart_product->product->sku;
	                if($cart_product->product->pimage){
	                    $order_product->image = $cart_product->product->pimage->first() ? $cart_product->product->pimage->first()->path : '';
	                }
	                $order_product->save();
	                $cart_addons = CartAddon::where('cart_product_id', $cart_product->id)->get();
	                if($cart_addons){
	                    foreach ($cart_addons as $cart_addon) {
	                        $orderAddon = new OrderProductAddon;
	                        $orderAddon->addon_id = $cart_addon->addon_id;
	                        $orderAddon->option_id = $cart_addon->option_id;
	                        $orderAddon->order_product_id = $order_product->id;
	                        $orderAddon->save();
	                    }
	                    CartAddon::where('cart_product_id', $cart_product->id)->delete();
	                }
	            }
	            CartProduct::where('cart_id', $cart->id)->delete();
	            DB::commit();
		        return $this->successResponse($order, 'Order placed successfully.', 201);
    		}
    	} catch (Exception $e) {
    		DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
    }

}
