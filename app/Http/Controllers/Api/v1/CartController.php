<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Validation;
use Carbon\Carbon;
use App\Model\Client;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{User, Product, Cart, ProductVariantSet, ProductVariant, CartProduct, CartCoupon, ClientCurrency, Brand, CartAddon, UserDevice, AddonSet};

class CartController extends BaseController{
    use ApiResponser;

    private $field_status = 2;
    
    public function index(Request $request){
        try {
            $user = Auth::user();
            if (!$user->id) {
                $cart = Cart::where('unique_identifier', $user->system_user);
            }else{
                $cart = Cart::where('user_id', $user->id);
            }
            $cart = $cart->first();
            if($cart){
                $cartData = $this->getCart($cart, $user->language, $user->currency);
                return $this->successResponse($cartData);
            }
            return $this->successResponse($cart);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**   check auth and system user to add product in cart    */
    public function userCheck(){
        $user = Auth::user();
        if ($user->id && $user->id > 0) {
            $user_id = $user->id;
        }else{
            if(empty($user->system_user)){
                return response()->json(['error' => 'System id should not be empty.'], 404);
            }
            $user = User::where('system_id', Auth::user()->system_user)->first();
            $val = Auth::user()->system_user;
            if(!$user){
                $user = new User;
                $user->name = "System User";
                $user->email = $val . "@email.com";
                $user->password = Hash::make($val);
                $user->system_id = $val;
                $user->save();
            }
            $user_id = $user->id;
        }
        return $user_id;
    }

    /**     * Add product In Cart    *           */
    public function add(Request $request){
        try {
            $user = Auth::user();
            $langId = $user->language;
            $user_id = $user->id;
            $unique_identifier = '';
            if (!$user_id) {
                if(empty($user->system_user)){
                    return $this->errorResponse('System id should not be empty.', 404);
                }
                $unique_identifier = $user->system_user;
            }
            $product = Product::where('sku', $request->sku)->first();
            if(!$product){
                return $this->errorResponse('Invalid product.', 404);
            }
            $productVariant = ProductVariant::where('product_id',$product->id)->where('id',$request->product_variant_id)->first();
            if(!$productVariant){
                return $this->errorResponse('Invalid product variant.', 404);
            }
            if($product->sell_when_out_of_stock == 0 && $productVariant->quantity < $request->quantity){
                return $this->errorResponse('You Can not order more than '.$productVariant->quantity.' quantity.', 404);
            }
            $addonSets = $addon_ids = $addon_options = array();
            if($request->has('addon_ids')){
                $addon_ids = $request->addon_ids;
            }
            if($request->has('addon_options')){
                $addon_options = $request->addon_options;
            }
            foreach($addon_options as $key => $opt){
                $addonSets[$addon_ids[$key]][] = $opt;
            }
            foreach($addonSets as $key => $value){
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                            ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                            ->where('ast.language_id', $langId)
                            ->where('addon_sets.status', '!=', '2')
                            ->where('addon_sets.id', $key)->first();
                if(!$addon){
                    return $this->errorResponse('Invalid addon or delete by admin. Try again with remove some.', 404);
                }
                if($addon->min_select > count($value)){
                    return response()->json([
                        "status" => "Error",
                        'message' => 'Select minimum ' . $addon->min_select .' options of ' .$addon->title,
                        'data' => $addon
                    ], 404);
                }
                if($addon->max_select < count($value)){
                    return response()->json([
                        "status" => "Error",
                        'message' => 'You can select maximum ' . $addon->min_select .' options of ' .$addon->title,
                        'data' => $addon
                    ], 404);
                }
            }
            $client_currency = ClientCurrency::where('is_primary', '=', 1)->first();
            $cart_detail = [
                'is_gift' => 0,
                'status' => '1',
                'item_count' => 0,
                'user_id' => $user->id,
                'created_by' => $user->id,
                'unique_identifier' => $unique_identifier,
                'currency_id' => $client_currency->currency_id,
            ];
            if(!empty($user_id)){
                $cart_detail = Cart::updateOrCreate(['user_id' => $user->id], $cart_detail);
            }else{
                $cart_detail = Cart::updateOrCreate(['unique_identifier' => $unique_identifier], $cart_detail);
            }
            if($cart_detail->id > 0){
                $oldquantity = $isnew = 0;
                $cart_product_detail = [
                    'status'  => '0',
                    'is_tax_applied'  => '1',
                    'created_by'  => $user_id,
                    'product_id' => $product->id,
                    'cart_id'  => $cart_detail->id,
                    'quantity'  => $request->quantity,
                    'vendor_id'  => $product->vendor_id,
                    'variant_id'  => $request->product_variant_id,
                    'currency_id' => $client_currency->currency_id,
                ];
                $cartProduct = CartProduct::where('cart_id', $cart_detail->id)
                            ->where('product_id', $product->id)
                            ->where('variant_id', $productVariant->id)->first();

                if(!$cartProduct){
                    $isnew = 1;
                }else{
                    $checkaddonCount = CartAddon::where('cart_product_id', $cartProduct->id)->count();
                    if(count($addon_ids) != $checkaddonCount){
                        $isnew = 1;
                    }else{
                        foreach ($addon_options as $key => $opts) {
                            $cart_addon = CartAddon::where('cart_product_id', $cartProduct->id)
                                        ->where('addon_id', $addon_ids[$key])
                                        ->where('option_id', $opts)->first();

                            if(!$cart_addon){
                                $isnew = 1;
                            }
                        }
                    }
                }
                if($isnew == 1){
                    $cartProduct = CartProduct::create($cart_product_detail);
                    if(!empty($addon_ids) && !empty($addon_options)){
                        $saveAddons = array();
                        foreach ($addon_options as $key => $opts) {
                            $saveAddons[] = [
                                'cart_product_id' => $cartProduct->id,
                                'addon_id' => $addon_ids[$key],
                                'option_id' => $opts
                            ];
                        }
                        if(!empty($saveAddons)){
                            CartAddon::insert($saveAddons);
                        }
                    }
                }else{
                    $cartProduct->quantity = $cartProduct->quantity + $request->quantity;
                    $cartProduct->save();
                }
            }
            $cartData = $this->getCart($cart_detail, $user->language, $user->currency);
            if($cartData && !empty($cartData)){
                return $this->successResponse($cartData);
            }else{
                return $this->successResponse($cartData);
            }
        }catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**        
        update quantity in cart       
    **/
    public function updateQuantity(Request $request){
        $user = Auth::user();
        if ($request->quantity < 1) {
            return response()->json(['error' => 'Quantity should not be less than 1'], 422);
        }
        $cart = Cart::where('user_id', $user->id)->where('id', $request->cart_id)->first();
        if(!$cart){
            return response()->json(['error' => 'User cart not exist.'], 404);
        }
        $cartProduct = CartProduct::where('cart_id', $cart->id)->where('id', $request->cart_product_id)->first();
        if(!$cartProduct){
            return response()->json(['error' => 'Product not exist in cart.'], 404);
        }
        $cartProduct->quantity = $request->quantity;
        $cartProduct->save();
        $totalProducts = CartProduct::where('cart_id', $cart->id)->sum('quantity');
        $cart->item_count = $totalProducts;
        $cart->save();
        $cartData = $this->getCart($cart, $user->language, $user->currency);
        return response()->json([
            'data' => $cartData,
        ]);
    }

    public function getItemCount(Request $request){
        $cart = Cart::where('user_id', Auth::user()->id)->where('id', $request->cart_id)->first();
        if(!$cart){
            return response()->json(['error' => 'User cart not exist.'], 404);
        }
        $totalProducts = CartProduct::where('cart_id', $cart->id)->sum('quantity');
        $cart->item_count = $totalProducts;
        $cart->save();
        return response()->json([
            'total_item' => $cart->item_count,
        ]);
    }

    public function removeItem(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $cart = Cart::where('id', $request->cart_id);
        if (!$user_id || $user_id < 1) {
            if(empty($user->system_user)){
                return $this->errorResponse('System id should not be empty.', 404);
            }
            $cart = $cart->where('unique_identifier', $user->system_user);
            
        }else{
            $cart = $cart->where('user_id', $user->id);
        }
        $cart = $cart->first();
        if(!$cart){
            return response()->json(['error' => 'Cart not exist'], 404);
        }
        $cartProduct = CartProduct::where('cart_id', $cart->id)->where('id', $request->cart_product_id)->first();
        if(!$cartProduct){
            return response()->json(['error' => 'Product not exist in cart.'], 404);
        }
        $cartProduct->delete();
        $totalProducts = CartProduct::where('cart_id', $cart->id)->sum('quantity');
        if(!$totalProducts || $totalProducts < 1){
            $cart->delete();
            return response()->json([
                "message" => "Product removed from cart successfully.",
                'data' => array(),
            ]);
        }
        $cart->item_count = $totalProducts;
        $cart->save();
        $cartData = $this->getCart($cart, $user->language, $user->currency);
        return response()->json([
            "message" => "Product removed from cart successfully.",
            'data' => $cartData,
        ]);

    }

    /**         *       Empty cart       *          */
    public function emptyCart($cartId = 0){
        $user = Auth::user();
        $user_id = $user->id;
        $cart = Cart::where('id', '>', 0);
        if (!$user_id || $user_id < 1) {
            if(empty($user->system_user)){
                return $this->errorResponse('System id should not be empty.', 404);
            }
            $cart = $cart->where('unique_identifier', $user->system_user);
        }else{
            $cart = $cart->where('user_id', $user->id);
        }
        $cart->delete();
        return response()->json(['message' => 'Empty cart successfully.']);
    }

    /**         *       Empty cart       *          */
    public function getCart($cart, $langId = '1', $currency = '1'){
        $clientCurrency = ClientCurrency::where('currency_id', $currency)->first();
        if(!$cart){
            return false;
        }
        $cartID = $cart->id;
        $cartData = CartProduct::with(['vendor', 'coupon'=> function($qry) use($cartID){
                        $qry->where('cart_id', $cartID);
                    }, 'coupon.promo.details', 'vendorProducts.pvariant.media.image', 'vendorProducts.product.media.image', 
                    'vendorProducts.pvariant.vset.variantDetail.trans' => function($qry) use($langId){
                        $qry->where('language_id', $langId);
                    },
                    'vendorProducts.pvariant.vset.optionData.trans' => function($qry) use($langId){
                        $qry->where('language_id', $langId);
                    },
                    'vendorProducts.product.translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                        $q->where('language_id', $langId);
                    },
                    'vendorProducts'=> function($qry) use($cartID){
                        $qry->where('cart_id', $cartID);
                    },                    
                    'vendorProducts.addon.set' => function($qry) use($langId){
                        $qry->where('language_id', $langId);
                    },
                    'vendorProducts.addon.option' => function($qry) use($langId){
                        $qry->where('language_id', $langId);
                    }, 'vendorProducts.product.taxCategory.taxRate', 
                ])->select('vendor_id')->where('cart_id', $cartID)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        $total_payable_amount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = 0.00;
        $total_tax = $total_paying = $total_disc_amount = 0.00; $item_count = 0;
        if($cartData){
            $tax_details = [];
            foreach ($cartData as $ven_key => $vendorData) {
                $codeApplied = $is_percent = $proSum = $proSumDis = $taxable_amount = $discount_amount = $discount_percent = 0;
                $ttAddon = $payable_amount = $is_coupon_applied = $coupon_removed = 0; $coupon_removed_msg = '';
                $couponData = $couponProducts = array();
                if(!empty($vendorData->coupon->promo) && ($vendorData->coupon->vendor_id == $vendorData->vendor_id)){
                    $now = Carbon::now()->toDateTimeString();
                    $minimum_spend = 0;
                    if(isset($vendorData->coupon->promo->minimum_spend)){
                        $minimum_spend = $vendorData->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                    }
                    if($vendorData->coupon->promo->expiry_date < $now){
                        $coupon_removed = 1;
                        $coupon_removed_msg = 'Coupon code is expired.';
                    }else{
                        $couponData['coupon_id'] =  $vendorData->coupon->promo->id;
                        $couponData['name'] =  $vendorData->coupon->promo->name;
                        $couponData['disc_type'] = ($vendorData->coupon->promo->promo_type_id == 1) ? 'Percent' : 'Amount';
                        $couponData['expiry_date'] =  $vendorData->coupon->promo->expiry_date;
                        $couponData['allow_free_delivery'] =  $vendorData->coupon->promo->allow_free_delivery;
                        $couponData['minimum_spend'] =  $vendorData->coupon->promo->minimum_spend;
                        $couponData['first_order_only'] = $vendorData->coupon->promo->first_order_only;
                        $couponData['restriction_on'] = ($vendorData->coupon->promo->restriction_on == 1) ? 'Vendor' : 'Product';

                        $is_coupon_applied = 1;
                        if($vendorData->coupon->promo->promo_type_id){
                            $is_percent = 1;
                            $discount_percent = round($vendorData->coupon->promo->amount);
                        }else{
                            $discount_amount = $vendorData->coupon->promo->amount * $clientCurrency->doller_compare;
                        }
                        if($vendorData->coupon->promo->restriction_on == 0){
                            foreach ($vendorData->coupon->promo->details as $key => $value) {
                                $couponProducts[] = $value->refrence_id;
                            }
                        }
                    }
                }
                foreach ($vendorData->vendorProducts as $pkey => $prod) {
                    $price_in_currency = $price_in_doller_compare = $pro_disc = $quantity_price = 0; 
                    $variantsData = $taxData = $vendorAddons = array();
                    $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;
                    $price_in_currency = $prod->pvariant ? $prod->pvariant->price : 0;
                    $price_in_doller_compare = $price_in_currency * $clientCurrency->doller_compare;
                    $quantity_price = $price_in_doller_compare * $prod->quantity;
                    $item_count = $item_count + $prod->quantity;
                    $proSum = $proSum + $quantity_price;
                    if(isset($prod->pvariant->image->imagedata) && !empty($prod->pvariant->image->imagedata)){
                        $prod->cartImg = $prod->pvariant->image->imagedata;
                    }else{
                        $prod->cartImg = (isset($prod->product->media[0]) && !empty($prod->product->media[0])) ? $prod->product->media[0]->image : '';
                    }
                    if($prod->pvariant){
                        $variantsData['price']              = $price_in_currency;
                        $variantsData['id']                 = $prod->pvariant->id;
                        $variantsData['sku']                = $prod->pvariant->sku;
                        $variantsData['title']              = $prod->pvariant->title;
                        $variantsData['barcode']            = $prod->pvariant->barcode;
                        $variantsData['product_id']         = $prod->pvariant->product_id;
                        $variantsData['multiplier']         = $clientCurrency->doller_compare;
                        $variantsData['gross_qty_price']    = $price_in_doller_compare * $prod->quantity;
                        if(!empty($vendorData->coupon->promo) && ($vendorData->coupon->promo->restriction_on == 0) && in_array($prod->product_id, $couponProducts)){
                            $pro_disc = $discount_amount;
                            if($minimum_spend < $quantity_price){
                                if($is_percent == 1){
                                    $pro_disc = ($quantity_price * $discount_percent)/ 100;
                                }
                                $quantity_price = $quantity_price - $pro_disc;
                                $proSumDis = $proSumDis + $pro_disc;
                                if($quantity_price < 0){
                                    $quantity_price = 0;
                                }
                                $codeApplied = 1;
                                
                            }else{
                                $variantsData['coupon_msg'] = "Spend minimun ".$minimum_spend." to apply this coupon";
                                $variantsData['coupon_not_appiled'] = 1;
                            }
                        }
                        $variantsData['discount_amount'] = $pro_disc;
                        $variantsData['coupon_applied'] = $codeApplied;
                        $variantsData['quantity_price'] = $quantity_price;
                        $payable_amount = $payable_amount + $quantity_price;
                        if(!empty($prod->product->taxCategory) && count($prod->product->taxCategory->taxRate) > 0){
                            foreach ($prod->product->taxCategory->taxRate as $tckey => $tax_value) {
                                $rate = round($tax_value->tax_rate);
                                $tax_amount = ($price_in_doller_compare * $rate) / 100;
                                $product_tax = $quantity_price * $rate / 100;
                                $taxData[$tckey]['rate'] = $rate;
                                $taxData[$tckey]['tax_amount'] = $tax_amount;
                                $taxData[$tckey]['product_tax'] = $product_tax;
                                $taxData[$tckey]['sku'] = $prod->pvariant->sku;
                                $taxable_amount = $taxable_amount + $product_tax;
                                $taxData[$tckey]['identifier'] = $tax_value->identifier;
                            }
                        }
                        $prod->taxdata = $taxData;
                        $tax_details[] = $taxData;
                        if(!empty($prod->addon)){
                            foreach ($prod->addon as $ck => $addons) {
                                $opt_quantity_price = 0;
                                $opt_price_in_currency = $addons->option->price;
                                $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                                $opt_quantity_price = $opt_price_in_doller_compare * $prod->quantity;
                                $vendorAddons[$ck]['cart_product_id'] = $addons->cart_product_id;
                                $vendorAddons[$ck]['addon_id'] = $addons->addon_id;
                                $vendorAddons[$ck]['addon_title'] = $addons->set->title;
                                $vendorAddons[$ck]['option_id'] = $addons->option_id;
                                $vendorAddons[$ck]['option_title'] = $addons->option->title;
                                $vendorAddons[$ck]['price_in_cart'] = $addons->option->price;
                                $vendorAddons[$ck]['price'] = $opt_price_in_currency;
                                $vendorAddons[$ck]['multiplier'] = $clientCurrency->doller_compare;
                                $vendorAddons[$ck]['quantity'] = $prod->quantity;
                                $vendorAddons[$ck]['quantity_price'] = $opt_quantity_price;
                                $ttAddon = $ttAddon + $opt_quantity_price;
                                $payable_amount = $payable_amount + $opt_quantity_price;
                            }
                        }
                        unset($prod->product->taxCategory);
                        unset($prod->addon);
                        unset($prod->pvariant);
                    }
                    $variant_options = [];
                    if($prod->pvariant){
                        foreach ($prod->pvariant->vset as $variant_set_option) {
                            $variant_options [] = array(
                                'option' => $variant_set_option->optionData->trans->title,
                                'title' => $variant_set_option->variantDetail->trans->title,
                            );
                        }
                    }
                    $deliver_charge = 0;
                    $prod->variants = $variantsData;
                    $prod->variant_options = $variant_options;
                    $prod->deliver_charge = $deliver_charge;
                    $payable_amount = $payable_amount + $deliver_charge;
                    $prod->product_addons = $vendorAddons;
                }
                $couponApplied = 0;
                if(!empty($vendorData->coupon->promo) && ($vendorData->coupon->promo->restriction_on == 1)){
                    $minimum_spend = $vendorData->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                    if($minimum_spend < $proSum){
                        if($is_percent == 1){
                            $discount_amount = ($proSum * $discount_percent)/ 100;
                        }
                        $couponApplied = 1;
                    }else{
                        $vendorData->coupon_msg = "To apply coupon minimum spend should be greater than ".$minimum_spend.'.';
                        $vendorData->coupon_not_appiled = 1;
                    }
                }
                $vendorData->proSum = $proSum;
                $vendorData->addonSum = $ttAddon;
                $vendorData->coupon_apply_on_vendor = $couponApplied;
                $vendorData->is_coupon_applied = $is_coupon_applied;

                if(empty($couponData)){
                    $vendorData->couponData = NULL;
                }else{
                    $vendorData->couponData = $couponData;
                }
                $vendorData->vendor_gross_total = $payable_amount;
                $vendorData->discount_amount = $discount_amount;
                $vendorData->discount_percent = $discount_percent;
                $vendorData->taxable_amount = $taxable_amount;
                $vendorData->payable_amount = $payable_amount + $taxable_amount - $discount_amount;

                $total_paying = $total_paying + $payable_amount;
                $total_tax = $total_tax + $taxable_amount;
                $total_disc_amount = $total_disc_amount + $discount_amount;
                $total_discount_percent = $total_discount_percent + $discount_percent;
                if(!empty($vendorData->coupon->promo)){
                    unset($vendorData->coupon->promo);
                }
            }
        }
        $cart->total_tax = $total_tax;
        $cart->tax_details = $tax_details;
        $cart->gross_paybale_amount = $total_paying;
        $cart->total_discount_amount = $total_disc_amount;
        $cart->total_payable_amount = $total_paying + $total_tax - $total_disc_amount;
        if($cart->user_id > 0){
            $cart->loyaltyPoints = $this->getLoyaltyPoints($cart->user_id, $clientCurrency->doller_compare);
            $cart->wallet = $this->getWallet($cart->user_id, $clientCurrency->doller_compare, $currency);
        }
        $cart->products = $cartData;
        $cart->item_count = $item_count;
        return $cart;
    }
}