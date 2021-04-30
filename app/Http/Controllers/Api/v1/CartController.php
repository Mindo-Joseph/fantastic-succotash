<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Cart, ProductVariantSet, ProductVariant, CartProduct, CartCoupon, ClientCurrency, Brand, CartAddon, UserDevice, AddonSet};
use Validation;
use DB;
use Illuminate\Support\Facades\Hash;

class CartController extends BaseController
{
    private $field_status = 2;
    /**
     * Get Cart Items
     *
     */
    public function index(Request $request)
    {
        $user_id = '';
        if (Auth::user()->id && Auth::user()->id > 0) {
            $user_id = Auth::user()->id;
        }else{
            if(empty(Auth::user()->system_user)){
                return response()->json(['error' => 'System id should not be empty.'], 404);
            }
        }

        $cartData = $this->getCart($user_id);

        return response()->json([
            'data' => $cartData,
        ]);
    }

    /**
     * Get Cart Items
     *
     */
    public function add(Request $request)
    {
        $langId = Auth::user()->language;
        $user_id = '';
        $cartInfo = '';

        $product = Product::where('sku', $request->sku)->first();
        if(!$product){
            return response()->json(['error' => 'Invalid product.'], 404);
        }

        $productVariant = ProductVariant::where('product_id', $product->id)->where('id', $request->product_variant_id)->first();
        if(!$productVariant){
            return response()->json(['error' => 'Invalid product variant.'], 404);
        }

        if($product->sell_when_out_of_stock == 0 && $productVariant->quantity < $request->quantity){
            return response()->json(['error' => 'You Can not order more than ' . $productVariant->quantity . ' quantity.'], 404);
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
                return response()->json(['error' => 'Invalid addon or delete by admin. Try again with remove some.'], 404);
            }
            if($addon->min_select > count($value)){
                return response()->json([
                    'error' => 'Select minimum ' . $addon->min_select .' options of ' .$addon->title,
                    'data' => $addon
                ], 404);
            }
            if($addon->max_select < count($value)){
                return response()->json([
                    'error' => 'You can select maximum ' . $addon->min_select .' options of ' .$addon->title,
                    'data' => $addon
                ], 404);
            }
        }

        if (Auth::user()->id && Auth::user()->id > 0) {
            $user_id = Auth::user()->id;
        }else{
            if(empty(Auth::user()->system_user)){
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

        $cart = Cart::where('user_id', $user_id)->first();
        if (!$cart) {
            $cart = new Cart;
            $cart->unique_identifier = Auth::user()->system_user;
            $cart->user_id = $user_id;
            $cart->created_by = $user_id;
            $cart->is_gift = '0';
            $cart->status = '0';
            $cart->item_count = $request->quantity;
            $cart->currency_id = Auth::user()->currency;
        }else{
            $cart->item_count = $cart->item_count + $request->quantity;
        }
        $cart->save();

        if($cart->id > 0){

            $oldquantity = 0;

            $cartProduct = CartProduct::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->where('variant_id', $productVariant->id)->first();

            if(!$cartProduct){
                $cartProduct = new CartProduct;
                $cartProduct->cart_id = $cart->id;
                $cartProduct->product_id = $product->id;
                $cartProduct->vendor_id = $product->vendor_id;
                $cartProduct->created_by  = $user_id;
                $cartProduct->status  = '0';
                $cartProduct->variant_id  = $productVariant->id;
                $cartProduct->is_tax_applied  = '1';
                $cartProduct->tax_rate_id  = $product->tax_category_id;
                $cartProduct->quantity = $request->quantity;
                $cartProduct->currency_id = Auth::user()->currency;
            }else{
                $cartProduct->quantity = $cartProduct->quantity + $request->quantity;
            }
            $cartProduct->save();
            if(!empty($addon_ids) && !empty($addon_options)){

                $saveAddons = array();

                foreach ($addon_options as $key => $opts) {

                    $cart_addon = CartAddon::where('cart_product_id', $cartProduct->id)
                                    ->where('addon_id', $addon_ids[$key])
                                    ->where('option_id', $opts)->first();
                    if(!$cart_addon){
                        $saveAddons[] = [
                            'cart_product_id' => $cartProduct->id,
                            'addon_id' => $addon_ids[$key],
                            'option_id' => $opts
                        ];
                    }
                }
                if(!empty($saveAddons)){
                    CartAddon::insert($saveAddons);
                }
            }
        }
        $cart = $this->getCart($user_id);

        //$cartData = Cart::with('products')->select('id', 'item_count')->where('user_id', $user_id)->first();

        return response()->json([
            'data' => $cart,
        ]);
    }

    /**
     * Get Cart Items
     *
     */
    public function getCart($user_id)
    {
        $langId = Auth::user()->language;
        $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
        $cartData = Cart::with(['coupon.promo', 'cartProducts.addon.option' => function($qry) use($langId){
                            $qry->where('language_id', $langId);
                        }, 'cartProducts.pvariant.image.imagedata', 'cartProducts.product.media.image']) 
                    ->select('id', 'is_gift', 'item_count')
                    ->where('status', '0')
                    ->where('user_id', $user_id)->first();

        $payable_amount = 0;
        $discount_amount = 0;
        $discount_percent = 0;
        if($cartData){

            foreach ($cartData->cartProducts as $ck => $prod) {

                $divider = $prod->doller_compare;

                $price_in_currency = $prod->pvariant->price / $divider;
                $quantity_price = $price_in_currency * $prod->quantity;

                $prod->pvariant->price_in_cart = $prod->pvariant->price;
                $prod->pvariant->price = $price_in_currency;
                $prod->pvariant->multiplier = $clientCurrency->doller_compare;
                $prod->pvariant->quantity_price = $quantity_price;

                $payable_amount = $payable_amount + ($quantity_price * $clientCurrency->doller_compare);

                foreach ($prod->addon as $ck => $addons) {

                    $opt_price_in_currency = $addons->option->price / $divider;
                    $opt_quantity_price = $opt_price_in_currency * $prod->quantity;

                    $addons->option->price_in_cart = $addons->option->price;
                    $addons->option->price = $opt_price_in_currency;
                    $addons->option->multiplier = $clientCurrency->doller_compare;
                    $addons->option->quantity_price = $opt_quantity_price;

                    $payable_amount = $payable_amount + ($opt_quantity_price * $clientCurrency->doller_compare);
                }

                if(isset($prod->pvariant->image->imagedata) && !empty($prod->pvariant->image->imagedata)){
                    $prod->cartImg = $prod->pvariant->image->imagedata;
                }else{
                    $prod->cartImg = (isset($prod->product->media[0]) && !empty($prod->product->media[0])) ? $prod->product->media[0]->image : '';
                }
            }

            $is_percent = 0;
            $amount_value = 0;

            foreach ($cartData->coupon as $ck => $code) {
                if($code->promo->promo_type_id == 1){
                    $is_percent = 1;
                    $discount_percent = $discount_percent + round($code->promo->amount);
                }else{
                    $amount_value = $amount_value + $code->promo->amount;
                }
            }
        }
        if($is_percent == 1){
            $discount_percent = ($discount_percent > 100) ? 100 : $discount_percent;
            $discount_amount = ($payable_amount * $discount_percent) / 100;
        }

        if($amount_value > 0){
            $amount_value = $amount_value * $clientCurrency->doller_compare;
            $discount_amount = $discount_amount + $amount_value;
            
        }
        $payable_amount = $payable_amount - $discount_amount;

        $cartData->total_amount = $payable_amount + $discount_amount;
        $cartData->payable_amount = $payable_amount;
        $cartData->discount_amount = $discount_amount;
        $cartData->discount_percent = $discount_percent;

        return $cartData;
    }
}