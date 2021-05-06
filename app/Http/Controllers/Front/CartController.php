<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{AddonSet, Cart, CartAddon, CartProduct, User, Product, ClientCurrency, ProductVariant, ProductVariantSet};
use Illuminate\Http\Request;
use Session;
use Auth;

class CartController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function randomString()
    {
        $random_string = substr(md5(microtime()), 0, 32);
        while (User::where('system_id', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 32);
        }
        return $random_string;
    }


     /**
     * add product to cart
     *
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');

        if ($request->has('addonID') && $request->has('addonoptID')) {

            $addon_ids = $request->addonID;
            $addon_options = $request->addonoptID;

            $addonSets = array();

            foreach ($addon_options as $key => $opt) {
                $addonSets[$addon_ids[$key]][] = $opt;
            }

            foreach ($addonSets as $key => $value) {
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                    ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                    ->where('ast.language_id', $langId)
                    ->where('addon_sets.status', '!=', '2')
                    ->where('addon_sets.id', $key)->first();
                if (!$addon) {
                    return response()->json(['error' => 'Invalid addon or delete by admin. Try again with remove some.'], 404);
                }
                if ($addon->min_select > count($value)) {
                    return response()->json([
                        'error' => 'Select minimum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
                if ($addon->max_select < count($value)) {
                    return response()->json([
                        'error' => 'You can select maximum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
            }
        }

        $user_id = ' ';
        $cartInfo = ' ';
        if (Auth::user()) {
            $user_id = Auth::user()->id;

            $currency = ClientCurrency::where('is_primary', '=', 1)->first();
            $userFind = Cart::where('user_id', $user_id)->first();
            if (!$userFind) {
                $cart = new Cart;
                $cart->unique_identifier = Auth::user()->system_id;
                $cart->user_id = $user_id;
                $cart->created_by = $user_id;
                $cart->status = '0';
                $cart->is_gift = '1';
                $cart->item_count = '1';
                $cart->currency_id = $currency->currency->id;
                $cart->save();

                $cartInfo = $cart;
            } else {
                $cartInfo = $userFind;
            }
            $checkIfExist = CartProduct::where('product_id', $request->product_id)->where('variant_id', $request->variant_id)->where('cart_id', $cartInfo->id)->first();
            if ($checkIfExist) {
                $checkIfExist->quantity = (int)$checkIfExist->quantity + 1;
                $cartInfo->cartProducts()->save($checkIfExist);
                return response()->json($user_id);
            }
        } else {

            $val = ' ';
            if (!isset($_COOKIE["uuid"])) {

                $token = $this->randomString();
                setcookie("uuid", $token, time() + (10 * 365 * 24 * 60 * 60), "/");

                $val = $token;
                $user = new User;
                $user->name = "Test";
                $user->email = $val . "@email.com";
                $user->password = "test";
                $user->system_id = $val;
                $user->save();

                $user_id = $user->id;
                $currency = ClientCurrency::where('is_primary', '=', 1)->first();


                $cart = new Cart;
                $cart->unique_identifier = $token;
                $cart->user_id = $user_id;
                $cart->created_by = $user_id;
                $cart->status = '0';
                $cart->is_gift = '1';
                $cart->item_count = '1';
                $cart->currency_id = $currency->currency->id;
                $cart->save();

                $cartInfo = $cart;
            } else {
                $val = $_COOKIE["uuid"];
                $userInfo = User::where('system_id', $val)->first();
                $user_id = $userInfo->id;

                $cartInfo = Cart::where('user_id', $user_id)->first();

                $checkIfExist = CartProduct::where('product_id', $request->product_id)->where('variant_id', $request->variant_id)->where('cart_id', $cartInfo->id)->first();
                if ($checkIfExist) {
                    $checkIfExist->quantity = (int)$checkIfExist->quantity + 1;
                    $cartInfo->cartProducts()->save($checkIfExist);
                    return response()->json($user_id);
                }
            }
        }

        $productForVendor = Product::where('id', $request->product_id)->first();
        $cartProduct = new CartProduct;
        $cartProduct->product_id = $request->product_id;
        $cartProduct->cart_id  = $cartInfo->id;
        $cartProduct->vendor_id  = $productForVendor->vendor_id;
        $cartProduct->quantity  = $request->quantity;
        $cartProduct->created_by  = $user_id;
        $cartProduct->status  = '0';
        $cartProduct->variant_id  = $request->variant_id;
        $cartProduct->is_tax_applied  = '1';
        $cartProduct->currency_id = $cartInfo->currency_id;
        $cartProduct->save();
        //$cartInfo->cartProducts()->save($cartProduct);

        if ($request->has('addonID') && $request->has('addonID')) {
            foreach ($addon_ids as $key => $value) {
                $aa = $addon_ids[$key];
                $bb = $addon_options[$key];
                $cartAddOn = new CartAddon;
                $cartAddOn->cart_product_id = $cartProduct->id;
                $cartAddOn->addon_id = $aa;
                $cartAddOn->option_id = $bb;
                $cartAddOn->save();
            }
        }

        return response()->json($user_id);
        // dd($request->all());
    }

    /**
     * get products from cart
     *
     * @return \Illuminate\Http\Response
     */
    public function getCartProducts($domain = '')
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

        
        if($cartData && !empty($cartData)){
            return response()->json([
                'data' => $cartData,
            ]);
        }
        return response()->json([
            'message' => "No product found in cart",
            'data' => $cartData,
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
        if(empty($cartData->cartProducts) || count($cartData->cartProducts) < 1){
            return false;
        }
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
    /**
     * Show Main Cart
     *
     * @return \Illuminate\Http\Response
     */
    public function showCart($domain = '')
    {
        //   dd("fewge");
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('forntend/cart')->with(['navCategories' => $navCategories]);
    }


    /**
     * Update Quantityt
     *
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity($domain = '', Request $request)
    {
        $cartProduct = CartProduct::find($request->cartproduct_id);

        $cartProduct->quantity = $request->quantity;

        $cartProduct->save();

        return response()->json("Successfully Updated");
    }

    /**
     * Delete Cart Product
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCartProduct($domain = '', Request $request)
    {
        //    dd($request->cartproduct_id);
        $update = CartProduct::where('id', '=', $request->cartproduct_id)
            ->update(['status' => '2']);
        return response()->json('successfully deleted');
    }
}
