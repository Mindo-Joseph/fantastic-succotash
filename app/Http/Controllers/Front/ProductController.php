<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{AddonSet, Cart, CartAddon, CartProduct, User, Product, ClientCurrency, ProductVariant, ProductVariantSet};
use Illuminate\Http\Request;
use Session;
use Auth;


class ProductController extends FrontController
{
    private $field_status = 2;

    /**
     * Display product By Id
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $domain = '', $sku)
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);

        $product = Product::select('id')->where('sku', $sku)->firstOrFail();
        $p_id = $product->id;

        $product = Product::with([
            'variant' => function ($sel) {
                $sel->groupBy('product_id');
            },
            'variant.set' => function ($sel) {
                $sel->select('product_variant_id', 'variant_option_id');
            },
            'variant.vimage.pimage.image', 'related', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'addOn' => function ($q1) use ($langId) {
                $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                $q1->where('ast.language_id', $langId);
            },
            'variantSet' => function ($z) use ($langId, $p_id) {
                $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                $z->where('vt.language_id', $langId);
                $z->where('product_variant_sets.product_id', $p_id);
            },
            'variantSet.option2' => function ($zx) use ($langId, $p_id) {
                $zx->where('vt.language_id', $langId)
                    ->where('product_variant_sets.product_id', $p_id);
            },
            'addOn.setoptions' => function ($q2) use ($langId) {
                $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                $q2->where('apt.language_id', $langId);
            },
        ])->select('id', 'sku', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory')
            ->where('sku', $sku)
            ->where('is_live', 1)
            ->firstOrFail();

        //dd($product->toArray());
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        foreach ($product->variant as $key => $value) {
            $product->variant[$key]->multiplier = $clientCurrency->doller_compare;
        }

        $vendorIds[] = $product->vendor_id;

        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;

        foreach ($product->addOn as $key => $value) {
            foreach ($value->setoptions as $k => $v) {
                $v->multiplier = $clientCurrency->doller_compare;
            }
        }
        return view('forntend/product')->with(['product' => $product, 'navCategories' => $navCategories, 'newProducts' => $newProducts]);
    }

    /**
     * Display product variant data
     *
     * @return \Illuminate\Http\Response
     */
    public function getVariantData(Request $request, $domain = '', $sku)
    {
        $product = Product::select('id')->where('sku', $sku)->firstOrFail();
        $pv_ids = array();
        if ($request->has('options') && !empty($request->options)) {
            foreach ($request->options as $key => $value) {
                $newIds = array();

                $product_variant = ProductVariantSet::where('variant_type_id', $request->variants[$key])
                    ->where('variant_option_id', $request->options[$key]);

                if (!empty($pv_ids)) {
                    $product_variant = $product_variant->whereIn('product_variant_id', $pv_ids);
                }
                $product_variant = $product_variant->where('product_id', $product->id)->get();

                if ($product_variant) {
                    foreach ($product_variant as $key => $value) {
                        $newIds[] = $value->product_variant_id;
                    }
                }
                $pv_ids = $newIds;
            }
        }
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        $variantData = ProductVariant::select('id', 'sku', 'quantity', 'price',  'barcode', 'product_id')
            ->where('id', $pv_ids[0])->first();
        if ($variantData) {
            $variantData->productPrice = Session::get('currencySymbol') . $variantData->price * $clientCurrency->doller_compare;
            return response()->json(array('success' => true, 'result' => $variantData->toArray()));
        }

        return response()->json(array('error' => true, 'result' => NULL));
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

        $cartProduct = new CartProduct;
        $cartProduct->product_id = $request->product_id;
        $cartProduct->cart_id  = $cartInfo->id;
        $cartProduct->quantity  = $request->quantity;
        $cartProduct->created_by  = $user_id;
        $cartProduct->status  = '0';
        $cartProduct->variant_id  = $request->variant_id;
        $cartProduct->is_tax_applied  = '1';
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
        $userId = 0;
        if (Auth::user()) {
            $userId = Auth::user()->id;
        } elseif (isset($_COOKIE["uuid"])) {
            $user = User::where('system_id', $_COOKIE["uuid"])->first();
            if (!$user) {
                return response()->json(array('res' => 'null', 'html' => "<li><div class='total'><h5>No Products</h5></div></li>"));
            }
            $userId = $user->id;
        } else {
            return response()->json(array('res' => 'null', 'html' => "<li><div class='total'><h5>No Products</h5></div></li>"));
        }

        $cart = Cart::where('user_id', $userId)->first();
        $cartproducts = $cart->cartProducts()->get();
        $products = array();
        $quantity = array();
        $price = array();
        $images = array();
        $cp_id = array();
        $variants = array();
        foreach ($cartproducts as $carpro) {
            if ($carpro->status == '0') {
                $cp_id[] = $carpro->id;
                $products_variant = ProductVariant::with(['image.pimage.image', 'set'])->find($carpro->variant_id);

                $pro = Product::find($carpro->product_id);
                $variants[] = $products_variant->toArray();
                $products[] = $pro->sku;
                $quantity[] = $carpro->quantity;

                $price[] = $products_variant->price;
                $images[] = $products_variant->image;
            }
        }

        return response()->json([
            'cart_products' => json_encode($cp_id),
            'products' => json_encode($products),
            'quantity' => json_encode($quantity),
            'price' => json_encode($price),
            'image' => json_encode($images),
            'variants' => json_encode($variants)
        ]);
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

