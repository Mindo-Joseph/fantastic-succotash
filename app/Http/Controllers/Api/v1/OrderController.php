<?php

namespace App\Http\Controllers\Api\v1;
use DB;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderStoreRequest;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, Product, OrderProductAddon, ClientPreference, ClientCurrency};
class OrderController extends Controller{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getOrdersList(Request $request){
    	$user = Auth::user();
    	$orders = Order::with('products')->orderBy('id', 'DESC')->paginate(10);
    	foreach ($orders as $order) {
    		$order_item_count = 0;
    		foreach ($order->products as $product) {
    			$order_item_count += $product->quantity;
    		}
    		$order->order_item_count = $order_item_count;
    	}
    	return $this->successResponse($orders, 'Order placed successfully.', 201);
    }

    public function postOrderDetail(Request $request){
    	try {
    		$user = Auth::user();
    		$order_id = $request->order_id;
	    	$order = Order::with(['vendors.vendor','vendors.products','address'])->where('user_id', $user->id)->where('id', $order_id)->first();
    		$order_item_count = 0;
    		foreach ($order->products as $product) {
    			$order_item_count += $product->quantity;
    		}
    		$order->order_item_count = $order_item_count;
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
    			$clientCurrency = ClientCurrency::where('currency_id', $user->currency)->first();
	    		$cart = Cart::where('user_id', $user->id)->first();
		        $order = new Order;
		        $order->user_id = $user->id;
		        $order->order_number = generateOrderNo();
		        $order->address_id = $request->address_id;
		        $order->payment_option_id = $request->payment_option_id;
		        $order->save();
		        $cart_products = CartProduct::select('*')->with('product.pimage', 'product.variants', 'product.taxCategory.taxRate')->where('cart_id', $cart->id)->where('status', [0,1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
		        $total_amount = 0;
	            $total_discount = 0;
	            $taxable_amount = 0;
	            $payable_amount = 0;
	            foreach ($cart_products->groupBy('vendor_id') as $vendor_cart_products) {
	                foreach ($vendor_cart_products as $vendor_cart_product) {
	                    $variant = $vendor_cart_product->product->variants->where('id', $vendor_cart_product->variant_id)->first();
	                    $quantity_price = 0;
	                    $divider = (empty($vendor_cart_product->doller_compare) || $vendor_cart_product->doller_compare < 0) ? 1 : $vendor_cart_product->doller_compare;
	                    $price_in_currency = $variant->price / $divider;
	                    $price_in_dollar_compare = $price_in_currency * $clientCurrency->doller_compare;
	                    $quantity_price = $price_in_dollar_compare * $vendor_cart_product->quantity;
	                    $payable_amount = $payable_amount + $quantity_price;
	                    $product_taxable_amount = 0;
	                    $product_payable_amount = 0;
	                    if(isset($vendor_cart_product->product['taxCategory'])){
	                    	if(isset($vendor_cart_product->product['taxCategory']['taxRate'])){
			                    foreach ($vendor_cart_product->product['taxCategory']['taxRate'] as $tax_rate_detail) {
			                        $rate = round($tax_rate_detail->tax_rate);
			                        $tax_amount = ($price_in_dollar_compare * $rate) / 100;
			                        $product_tax = $quantity_price * $rate / 100;
			                        $product_taxable_amount += $taxable_amount + $product_tax;
			                        $payable_amount = $payable_amount + $product_tax;
			                    }
	                    	}	
	                    }
	                    $total_amount += $variant->price;
	                    $taxable_amount += $product_taxable_amount;
	                    $order_product = new OrderProduct;
	                    $order_product->order_id = $order->id;
	                    $order_product->price = $variant->price;
	                    $order_product->quantity = $vendor_cart_product->quantity;
	                    $order_product->vendor_id = $vendor_cart_product->vendor_id;
	                    $order_product->product_id = $vendor_cart_product->product_id;
	                    $order_product->created_by = $vendor_cart_product->created_by;
	                    $order_product->variant_id = $vendor_cart_product->variant_id;
	                    $order_product->product_name = $vendor_cart_product->product->sku;
	                    if($vendor_cart_product->product->pimage){
	                        $order_product->image = $vendor_cart_product->product->pimage->first() ? $vendor_cart_product->product->pimage->first()->path : '';
	                    }
	                    $order_product->save();
	                    $cart_addons = CartAddon::where('cart_product_id', $vendor_cart_product->id)->get();
	                    if($cart_addons){
	                        foreach ($cart_addons as $cart_addon) {
	                            $orderAddon = new OrderProductAddon;
	                            $orderAddon->addon_id = $cart_addon->addon_id;
	                            $orderAddon->option_id = $cart_addon->option_id;
	                            $orderAddon->order_product_id = $order_product->id;
	                            $orderAddon->save();
	                        }
	                        CartAddon::where('cart_product_id', $vendor_cart_product->id)->delete();
	                    }
	                }
	            }
	            $order->total_amount = $total_amount;
	            $order->total_discount = $total_discount;
	            $order->taxable_amount = $taxable_amount;
	            $order->payable_amount = $payable_amount;
	            $order->save();
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
