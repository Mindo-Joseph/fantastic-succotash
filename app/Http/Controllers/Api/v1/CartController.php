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
    /**         * Get Cart Items    *            */
    public function index(Request $request)
    {
        $cartData = $this->getCart(Auth::user()->id);
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

    /**   check auth and system user to add product in cart    */
    public function userCheck()
    {
        $user_id = '';
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
        return $user_id;
    }

    /**     * Add product In Cart    *           */
    public function add(Request $request)
    {
        $user_id = $this->userCheck();
        $cartInfo = '';
        $langId = Auth::user()->language;

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

            $oldquantity = $isnew = 0;

            $cartProduct = CartProduct::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->where('variant_id', $productVariant->id)->first();

            if(!$cartProduct){
                $isnew = 1;

                $cartProduct = new CartProduct;
                $cartProduct->cart_id = $cart->id;
                $cartProduct->product_id = $product->id;
                $cartProduct->vendor_id = $product->vendor_id;
                $cartProduct->created_by  = $user_id;
                $cartProduct->status  = '0';
                $cartProduct->variant_id  = $productVariant->id;
                $cartProduct->is_tax_applied  = '1';
                $cartProduct->tax_category_id  = $product->tax_category_id;
                $cartProduct->quantity = $request->quantity;
                $cartProduct->currency_id = Auth::user()->currency;
                $cartProduct->save();

            }else{

                $checkaddonCount = CartAddon::where('cart_product_id', $cartProduct->id)->count();

                if(count($addon_ids) == $checkaddonCount){

                    foreach ($addon_options as $key => $opts) {

                        $cart_addon = CartAddon::where('cart_product_id', $cartProduct->id)
                                    ->where('addon_id', $addon_ids[$key])
                                    ->where('option_id', $opts)->first();

                        if(!$cart_addon){
                            $isnew = 1;
                        }
                    }
                    if($isnew == 1){

                        $cartProduct = new CartProduct;
                        $cartProduct->cart_id = $cart->id;
                        $cartProduct->product_id = $product->id;
                        $cartProduct->vendor_id = $product->vendor_id;
                        $cartProduct->created_by  = $user_id;
                        $cartProduct->status  = '0';
                        $cartProduct->variant_id  = $productVariant->id;
                        $cartProduct->is_tax_applied  = '1';
                        $cartProduct->tax_category_id  = $product->tax_category_id;
                        $cartProduct->quantity = $request->quantity;
                        $cartProduct->currency_id = Auth::user()->currency;
                        $cartProduct->save();

                    }else{
                        $cartProduct->quantity = $cartProduct->quantity + $request->quantity;
                        $cartProduct->save();
                    }

                }else{
                    $isnew = 1;
                    $cartProduct = new CartProduct;
                    $cartProduct->cart_id = $cart->id;
                    $cartProduct->product_id = $product->id;
                    $cartProduct->vendor_id = $product->vendor_id;
                    $cartProduct->created_by  = $user_id;
                    $cartProduct->status  = '0';
                    $cartProduct->variant_id  = $productVariant->id;
                    $cartProduct->is_tax_applied  = '1';
                    $cartProduct->tax_category_id  = $product->tax_category_id;
                    $cartProduct->quantity = $request->quantity;
                    $cartProduct->currency_id = Auth::user()->currency;
                    $cartProduct->save();

                }
            }
            if($isnew == 1){
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
            }
        }
        $cart = $this->getCart($user_id);

        return response()->json([
            'data' => $cart,
        ]);
    }

    /**         *       update quantity in cart       *          */
    public function updateQuantity(Request $request)
    {
        if ($request->quantity < 1) {
            return response()->json(['error' => 'Quantity should not be less than 1'], 422);
        }

        $cart = Cart::where('user_id', Auth::user()->id)->where('id', $request->cart_id)->first();
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

        $cartData = $this->getCart(Auth::user()->id);

        return response()->json([
            'data' => $cartData,
        ]);
    }

    /**     *       Get Cart Items            *     */
    public function getItemCount(Request $request)
    {
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

    /**         *       Remove item from cart       *          */
    public function removeItem(Request $request)
    {
        $cart = Cart::where('user_id', Auth::user()->id)->where('id', $request->cart_id)->first();
        if(!$cart){
            return response()->json(['error' => 'User cart not exist.'], 404);
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
        $cartData = $this->getCart(Auth::user()->id);
        return response()->json([
            "message" => "Product removed from cart successfully.",
            'data' => $cartData,
        ]);

    }

    /**         *       Empty cart       *          */
    public function emptyCart($cartId = 0)
    { //->where('id', $request->cart_id)
        $cart = Cart::where('user_id', Auth::user()->id)->first();
        if(!$cart){
            return response()->json(['error' => 'User cart not exist.'], 404);
        }

        $cart->delete();

        return response()->json([
            "message" => "Empty cart successfully.",
        ]);
    }

    /**         *       Empty cart       *          */
    public function getCart($user_id)
    {
        $langId = Auth::user()->language;
        $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
        $cart = Cart::with('coupon.promo.details')->select('id', 'is_gift', 'item_count')
                    ->where('status', '0')
                    ->where('user_id', $user_id)->first();

        if(!$cart){
            return false;
        }

        $cartID = $cart->id;

        $cartData = CartProduct::with(['vendor', 'vendorProducts.pvariant.media.image', 'vendorProducts.product.media.image',
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
        if(empty($cartData) || count($cartData) < 1){
            return false;
        }

        if($cartData){
            $total_tax = $total_paying = $total_disc_amount = 0.00;

            foreach ($cartData as $ven_key => $vendorData) {

                $codeApplied = $is_percent = $proSum = $proSumDis = $taxable_amount = $discount_amount = $discount_percent = 0;

                $ttAddon = $payable_amount = $is_coupon_applied = $coupon_removed = 0; $coupon_removed_msg = '';
                $couponData = $couponProducts = array();
                if(!empty($cart->coupon) && ($cart->coupon->vendor_id == $vendorData->vendor_id)){

                    $now = Carbon::now()->toDateTimeString();

                    $minimum_spend = $cart->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                    
                    if($cart->coupon->promo->expiry_date < $now){
                        $coupon_removed = 1;
                        $coupon_removed_msg = 'Coupon code is expired.';
                    }else{
                        $couponData['coupon_id'] =  $cart->coupon->promo->id;
                        $couponData['name'] =  $cart->coupon->promo->name;
                        $couponData['disc_type'] = ($cart->coupon->promo->promo_type_id == 1) ? 'Percent' : 'Ammount';
                        $couponData['expiry_date'] =  $cart->coupon->promo->expiry_date;
                        $couponData['allow_free_delivery'] =  $cart->coupon->promo->allow_free_delivery;
                        $couponData['minimum_spend'] =  $cart->coupon->promo->minimum_spend;
                        $couponData['first_order_only'] = $cart->coupon->promo->first_order_only;
                        $couponData['restriction_on'] = ($cart->coupon->promo->restriction_on == 1) ? 'Vendor' : 'Product';

                        $is_coupon_applied = 1;
                        if($cart->coupon->promo->promo_type_id){
                            $is_percent = 1;
                            $discount_percent = round($cart->coupon->promo->amount);
                        }else{
                            $discount_amount = $cart->coupon->promo->amount * $clientCurrency->doller_compare;
                        }
                        
                        if($cart->coupon->promo->restriction_on == 0){
                            foreach ($cart->coupon->promo->details as $key => $value) {
                                $couponProducts[] = $value->refrence_id;
                            }
                        }
                    }
                }

                foreach ($vendorData->vendorProducts as $pkey => $prod) {
                    $price_in_currency = $price_in_doller_compare = $pro_disc = $quantity_price = 0; 
                    $variantsData = $taxData = $vendorAddons = array();

                    $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;

                    $price_in_currency = round($prod->pvariant->price / $divider);
                    $price_in_doller_compare = $price_in_currency * $clientCurrency->doller_compare;
                    $quantity_price = $price_in_doller_compare * $prod->quantity;

                    $proSum = $proSum + $quantity_price;

                    if(isset($prod->pvariant->image->imagedata) && !empty($prod->pvariant->image->imagedata)){
                        $prod->cartImg = $prod->pvariant->image->imagedata;
                    }else{
                        $prod->cartImg = (isset($prod->product->media[0]) && !empty($prod->product->media[0])) ? $prod->product->media[0]->image : '';
                    }

                    $variantsData['id']                 = $prod->pvariant->id;
                    $variantsData['sku']                = $prod->pvariant->sku;
                    $variantsData['product_id']         = $prod->pvariant->product_id;
                    $variantsData['title']              = $prod->pvariant->title;
                    $variantsData['price']              = $price_in_currency;
                    $variantsData['barcode']            = $prod->pvariant->barcode;
                    $variantsData['price_in_cart']      = $prod->pvariant->price;
                    $variantsData['multiplier']         = $clientCurrency->doller_compare;
                    $variantsData['gross_qty_price']    = $price_in_doller_compare * $prod->quantity;

                    if(!empty($cart->coupon) && ($cart->coupon->promo->restriction_on == 0) && in_array($prod->product_id, $couponProducts)){
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

                            $taxData[$tckey]['identifier'] = $tax_value->identifier;
                            $taxData[$tckey]['rate'] = $rate;
                            $taxData[$tckey]['tax_amount'] = $tax_amount;
                            $taxData[$tckey]['product_tax'] = $product_tax;
                            $taxable_amount = $taxable_amount + $product_tax;

                            //$payable_amount = $payable_amount + $product_tax;
                        }
                    }
                    $prod->taxdata = $taxData;
                    
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

                    $prod->variants = $variantsData;

                    $deliver_charge = 0;
                    $prod->deliver_charge = $deliver_charge;
                    $payable_amount = $payable_amount + $deliver_charge;
                    $prod->product_addons = $vendorAddons;
                }
                $couponApplied = 0;

                if(!empty($cart->coupon) && ($cart->coupon->promo->restriction_on == 1)){
                    $minimum_spend = $cart->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
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
            }
        }

        $cart->gross_paybale_amount = $total_paying;
        $cart->total_tax = $total_tax;
        $cart->total_payable_amount = $total_paying + $total_tax - $total_disc_amount;
        $cart->total_discount_amount = $total_disc_amount;

        $cart->loyaltyPoints = $this->getLoyaltyPoints($user_id, $clientCurrency->doller_compare);
        $cart->wallet = $this->getWallet($user_id, $clientCurrency->doller_compare, Auth::user()->currency);

        if(!empty($cart->coupon)){
            unset($cart->coupon->promo);
        }

        $cart->products = $cartData;
        return $cart;
    }
}