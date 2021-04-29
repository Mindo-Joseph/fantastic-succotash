<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Cart, ProductVariantSet, ProductVariant, CartProduct, CartCoupon, ClientCurrency, Brand, CartAddon, AddonSet};
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
        $cartData = Cart::with('products')->select('id', 'item_count')->where('user_id', $user_id)->first();

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

        $addon_ids = $addon_options = array();

        if($request->has('addon_ids')){
            $addon_ids = $request->addon_ids;
        }

        if($request->has('addon_options')){
            $addon_options = $request->addon_options;
        }

        $addonSets = array();

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

            $cartProduct = CartProduct::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->where('variant_id', $productVariant->id)->first();
            if($cartProduct){
                return response()->json(['error' => 'Product already added into cart.'], 404);
            }

            $cartProduct = new CartProduct;
            $cartProduct->cart_id = $cart->id;
            $cartProduct->product_id = $product->id;
            $cartProduct->vendor_id = $product->vendor_id;
            $cartProduct->quantity  = $request->quantity;
            $cartProduct->created_by  = $user_id;
            $cartProduct->status  = '0';
            $cartProduct->variant_id  = $productVariant->id;
            $cartProduct->is_tax_applied  = '1';
            $cartProduct->save();
        }

        $cartData = Cart::with('products')->select('id', 'item_count')->where('user_id', $user_id)->first();
        dd($cartData->toArray());
        //$response['listData'] = $this->listData($langId, $cid, $category->type->redirect_to, $paginate, $userid);

        return response()->json([
            'data' => $cartData,
        ]);

    }

    /**
     * Get Cart Items
     *
     */
    public function remove(Request $request)
    {
       
    }
}