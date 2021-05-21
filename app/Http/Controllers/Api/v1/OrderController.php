<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, Product, OrderProductAddon, ClientPreference};
class OrderController extends Controller{
    use ApiResponser;

    public function postPlaceOrder(OrderStoreRequest $request){
    	try {
    		$user = Auth::user();
    		if($user){
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
		        $order->payment_method = $paymentMethod;
		        $order->payment_status = $paymentStatus;
		        $order->order_number = generateOrderNo();
		        $order->address_id = $request->address_id;
		        $order->payment_option_id = $request->payment_option_id;
		        $order->save();
		        $cartProducts = CartProduct::where('cart_id', $cart->id)->get();
		        foreach ($cartProducts as $cart_product) {
		            $product_detail = Product::where('id', $cart_product->product_id)->first();
		            $order_product = new OrderProduct;
		            $order_product->order_id = $order->id;
		            $order_product->quantity = $cart_product->quantity;
		            $order_product->product_name = $product_detail->sku;
		            $order_product->vendor_id = $cart_product->vendor_id;
		            $order_product->product_id = $cart_product->product_id;
		            $order_product->created_by = $cart_product->created_by;
		            $order_product->variant_id = $cart_product->variant_id;
		            $order_product->is_tax_applied = $cart_product->is_tax_applied;
		            $order_product->save();
		            $cart_addons = CartAddon::where('cart_product_id', $cart_product->id)->get();
		            foreach ($cart_addons as $cart_addon) {
		                $order_addon = new OrderProductAddon;
		                $order_addon->addon_id = $cart_addon->addon_id;
		                $order_addon->option_id = $cart_addon->option_id;
		                $order_addon->order_product_id = $order_product->id;
		                $order_addon->save();
		            }
                    CartAddon::where('cart_product_id', $cart_product->id)->delete();
		        }
                CartProduct::where('cart_id', $cart->id)->delete();
		        return $this->successResponse($order, 'Order placed successfully.', 201);
    		}
    	} catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
    }
}
