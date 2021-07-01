<?php

namespace App\Http\Controllers\Api\v1;
use DB;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderStoreRequest;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, Product, OrderProductAddon, ClientPreference, ClientCurrency, OrderVendor, UserAddress, CartCoupon, VendorOrderStatus, OrderStatusOption, Vendor};

class OrderController extends Controller {
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getOrdersList(Request $request){
    	$user = Auth::user();
        $order_status_options= [];
        $paginate = $request->has('limit') ? $request->limit : 12;
        $type = $request->has('type') ? $request->type : 'active';
        $orders = OrderVendor::where('user_id', $user->id)->orderBy('id', 'DESC');
        switch ($type) {
            case 'active':
                $order_status_options = [6,3];
                $orders->whereDoesntHave('status', function ($query) use($order_status_options) {
                    $query->whereIn('order_status_option_id', $order_status_options);
                });
            break;
            case 'past':
                $order_status_options = [6,3];
                $orders->whereHas('status', function ($query) use($order_status_options) {
                    $query->whereIn('order_status_option_id', $order_status_options);
                });
            break;
            case 'schedule':
                $order_status_options = [10];
                $orders->whereHas('status', function ($query) use($order_status_options) {
                    $query->whereIn('order_status_option_id', $order_status_options);
                });
            break;
        }
        $orders = $orders->paginate($paginate);
        foreach ($orders as $order) {
            $order_item_count = 0;
            $order->user_name = $user->name;
            $order->user_image = $user->image;
            $order->date_time = convertDateTimeInTimeZone($order->orderDetail->created_at, $user->timezone);
            $order->payment_option_title = $order->orderDetail->paymentOption->title;
            $order->order_number = $order->orderDetail->order_number;
            $product_details = [];
            $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->orderDetail->id)->where('vendor_id', $order->vendor_id)->orderBy('id', 'DESC')->first();
            if($vendor_order_status){
                $order->order_status =  ['current_status' => ['id' => $vendor_order_status->OrderStatusOption->id, 'title' => $vendor_order_status->OrderStatusOption->title]];
            }else{
                $order->current_status = null;
            }
            foreach ($order->products as $product) {
                $order_item_count += $product->quantity;
                $product_details[]= array(
                    'image_path' => $product->media->first() ? $product->media->first()->image->path : $product->image,
                    'price' => $product->price,
                    'qty' => $product->quantity,
                );
            }
            $order->product_details = $product_details;
            $order->item_count = $order_item_count;
            unset($order->user);
            unset($order->products);
            unset($order->paymentOption);
            unset($order->payment_option_id);
            unset($order->orderDetail);
        }
    	return $this->successResponse($orders, '', 201);
    }

    public function postOrderDetail(Request $request){
    	try {
    		$user = Auth::user();
    		$order_item_count = 0;
            $language_id = $user->language;
    		$order_id = $request->order_id;
            $vendor_id = $request->vendor_id;
            if($vendor_id){
	       	   $order = Order::with([
                'vendors' => function($q) use($vendor_id){$q->where('vendor_id', $vendor_id);},
                'vendors.products' => function($q) use($vendor_id){$q->where('vendor_id', $vendor_id);},
                'vendors.products.translation' => function($q) use($language_id){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                    $q->where('language_id', $language_id);
                },
                'vendors.products.pvariant.vset.optionData.trans','vendors.products.addon','vendors.coupon','address','vendors.products.productRating'
            ])->where('id', $order_id)->first();
            }else{
                $order = Order::with(['vendors.vendor',
                    'vendors.products.translation' => function($q) use($language_id){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                        $q->where('language_id', $language_id);
                    },
                    'vendors.products.pvariant.vset.optionData.trans','vendors.products.addon','vendors.coupon','address','vendors.products.productRating']
                )->where('user_id', $user->id)->where('id', $order_id)->first();
            }
            if($order){
                $order->user_name = $order->user->name;
                $order->user_image = $order->user->image;
                $order->payment_option_title = $order->paymentOption->title;
    	    	foreach ($order->vendors as $vendor) {
    				$couponData = [];
    				$payable_amount = 0;
        			$discount_amount = 0;
    				$product_addons = [];
                    $vendor->vendor_name = $vendor->vendor->name;
        			foreach ($vendor->products as  $product) {
                        $product_addons = [];
                        $variant_options = [];
    	    			$order_item_count += $product->quantity;
                        $product->image_path = $product->media->first() ? $product->media->first()->image->path : $product->image;
                        if($product->pvariant){
                            foreach ($product->pvariant->vset as $variant_set_option) {
                                $variant_options [] = array(
                                    'option' => $variant_set_option->optionData->trans->title,
                                    'title' => $variant_set_option->variantDetail->trans->title,
                                );
                            }
                        }
                        $product->variant_options = $variant_options;
                        if(!empty($product->addon)){
                            foreach ($product->addon as $addon) {
                                $product_addons[] = array(
                                    'addon_id' =>  $addon->addon_id,
                                    'addon_title' =>  $addon->set->title,
                                    'option_title' =>  $addon->option->title,
                                );
                            }
                        }
                        $product->product_addons = $product_addons;
        			}
        		}
    		    $order->order_item_count = $order_item_count;
            }
	    	return $this->successResponse($order, null, 201);
    	} catch (Exception $e) {
    		return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
    }

    public function postPlaceOrder(OrderStoreRequest $request){
    	try {
            $total_amount = 0;
            $total_discount = 0;
            $taxable_amount = 0;
            $payable_amount = 0;
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
    			$user_address = UserAddress::where('id', $request->address_id)->first();
                if(!$user_address){
                    return response()->json(['error' => 'Invalid address id.'], 404);
                }
	    		$cart = Cart::where('user_id', $user->id)->first();
		        if($cart){
                    $order = new Order;
                    $order->user_id = $user->id;
                    $order->order_number = generateOrderNo();
                    $order->address_id = $request->address_id;
                    $order->payment_option_id = $request->payment_option_id;
                    $order->save();
                    $clientCurrency = ClientCurrency::where('currency_id', $user->currency)->first();
                    $cart_products = CartProduct::with('product.pimage', 'product.variants', 'product.taxCategory.taxRate','coupon', 'product.addon')->where('cart_id', $cart->id)->where('status', [0,1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
                    foreach ($cart_products->groupBy('vendor_id') as $vendor_id => $vendor_cart_products) {
                        $vendor_payable_amount = 0;
                        $vendor_discount_amount = 0;
                        foreach ($vendor_cart_products as $vendor_cart_product) {
                            $variant = $vendor_cart_product->product->variants->where('id', $vendor_cart_product->variant_id)->first();
                            $quantity_price = 0;
                            $divider = (empty($vendor_cart_product->doller_compare) || $vendor_cart_product->doller_compare < 0) ? 1 : $vendor_cart_product->doller_compare;
                            $price_in_currency = $variant->price / $divider;
                            $price_in_dollar_compare = $price_in_currency * $clientCurrency->doller_compare;
                            $quantity_price = $price_in_dollar_compare * $vendor_cart_product->quantity;
                            $payable_amount = $payable_amount + $quantity_price;
                            $vendor_payable_amount = $vendor_payable_amount + $quantity_price;
                            $product_taxable_amount = 0;
                            $product_payable_amount = 0;
                            if($vendor_cart_product->product['taxCategory']){
                                foreach ($vendor_cart_product->product['taxCategory']['taxRate'] as $tax_rate_detail) {
                                    $rate = round($tax_rate_detail->tax_rate);
                                    $tax_amount = ($price_in_dollar_compare * $rate) / 100;
                                    $product_tax = $quantity_price * $rate / 100;
                                    $taxable_amount = $taxable_amount + $product_tax;
                                    $payable_amount = $payable_amount + $product_tax;
                                    $vendor_payable_amount = $vendor_payable_amount;
                                }
                            }
                            $total_amount += $variant->price;
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
                            if(!empty($vendor_cart_product->addon)){
                                foreach ($vendor_cart_product->addon as $ck => $addon) {
                                    $opt_quantity_price = 0;
                                    $opt_price_in_currency = $addon->option->price;
                                    $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                                    $opt_quantity_price = $opt_price_in_doller_compare * $order_product->quantity;
                                    $total_amount = $total_amount + $opt_quantity_price;
                                    $payable_amount = $payable_amount + $opt_quantity_price;
                                    $vendor_payable_amount = $vendor_payable_amount + $opt_quantity_price;
                                }
                            }
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
                        $coupon_id = null;
                        $coupon_name = null;
                        if($vendor_cart_product->coupon){
                            $coupon_id = $vendor_cart_product->coupon->promo->id;
                            $coupon_name = $vendor_cart_product->coupon->promo->name;
                            if($vendor_cart_product->coupon->promo->promo_type_id == 2){
                                $coupon_discount_amount = $vendor_cart_product->coupon->promo->amount;
                                $total_discount += $coupon_discount_amount;
                                $vendor_payable_amount -= $coupon_discount_amount;
                                $vendor_discount_amount +=$coupon_discount_amount;
                            }else{
                                $coupon_discount_amount = ($quantity_price * $vendor_cart_product->coupon->promo->amount / 100);
                                $final_coupon_discount_amount = $coupon_discount_amount * $clientCurrency->doller_compare;
                                $total_discount += $final_coupon_discount_amount;
                                $vendor_payable_amount -=$final_coupon_discount_amount;
                                $vendor_discount_amount +=$final_coupon_discount_amount; 
                            }   
                        }
                        $order_vendor = new OrderVendor;
                        $order_vendor->status = 0;
                        $order_vendor->user_id= $user->id;
                        $order_vendor->order_id= $order->id;
                        $order_vendor->vendor_id= $vendor_id;
                        $order_vendor->coupon_id = $coupon_id;
                        $order_vendor->coupon_code = $coupon_name;
                        $order_vendor->payable_amount= $vendor_payable_amount;
                        $order_vendor->discount_amount= $vendor_discount_amount;
                        $order_vendor->payment_option_id = $request->payment_option_id;
                        $vendor_info = Vendor::where('id', $vendor_id)->first();
                        if ($vendor_info) {
                            if (($vendor_info->commission_percent) != null && $vendor_payable_amount > 0) {
                                $order_vendor->admin_commission_percentage_amount = round($vendor_info->commission_percent * ($vendor_payable_amount / 100), 2);
                            }
                            if (($vendor_info->commission_fixed_per_order) != null && $vendor_payable_amount > 0) {
                                $order_vendor->admin_commission_fixed_amount = $vendor_info->commission_fixed_per_order;
                            }
                        }
                        $order_vendor->save();
                        $order_status = new VendorOrderStatus();
                        $order_status->order_id = $order->id;
                        $order_status->order_status_option_id = 1;
                        $order_status->vendor_id = $vendor_id;
                        $order_status->save();
                    }
                    $order->total_amount = $total_amount;
                    $order->total_discount = $total_discount;
                    $order->taxable_amount = $taxable_amount;
                    $order->payable_amount = $payable_amount -  $total_discount;
                    $order->save();
                    CartCoupon::where('cart_id', $cart->id)->delete();
                    CartProduct::where('cart_id', $cart->id)->delete();
                    DB::commit();
                    return $this->successResponse($order, 'Order placed successfully.', 201);
                    }
		        }else{
                    return $this->errorResponse(['error' => 'Empty cart.'], 404);
                }
    	} catch (Exception $e) {
    		DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
    }
    public function postVendorOrderStatusUpdate(Request $request){
        DB::beginTransaction();
        try {
            $order_id = $request->order_id;
            $vendor_id = $request->vendor_id;
            $order_status_option_id = $request->order_status_option_id;
            if($order_status_option_id == 7){
                $order_status_option_id = 2;
            }else if ($order_status_option_id == 8) {
                $order_status_option_id = 3;
            }
            $vendor_order_status_detail = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->where('order_status_option_id', $order_status_option_id)->first();
            if (!$vendor_order_status_detail) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $order_id;
                $vendor_order_status->vendor_id = $vendor_id;
                $vendor_order_status->order_status_option_id = $order_status_option_id;
                $vendor_order_status->save();
                $current_status = OrderStatusOption::select('id','title')->find($order_status_option_id);
                if($order_status_option_id == 2){
                    $upcoming_status = OrderStatusOption::select('id','title')->where('id', '>', 3)->first();
                }elseif ($order_status_option_id == 3) {
                    $upcoming_status = null;
                }elseif ($order_status_option_id == 6) {
                    $upcoming_status = null;
                }else{
                    $upcoming_status = OrderStatusOption::select('id','title')->where('id', '>', $order_status_option_id)->first();
                }
                $order_status = [
                    'current_status' => $current_status,
                    'upcoming_status' => $upcoming_status,
                ];
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'order_status' => $order_status,
                    'message' => 'Order Status Updated Successfully.'
                ]);
            }
        } catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
           
        }
    }
}
