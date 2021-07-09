<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Promocode;
use App\Models\CartCoupon;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Models\PromoCodeDetail;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PromoCodeController extends Controller{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postPromoCodeList(Request $request){
        try {
            $user = Auth::user();
            $language_id = $user->language;
            $promo_codes = new \Illuminate\Database\Eloquent\Collection;
            $vendor_id = $request->vendor_id;
            $cart_id = $request->cart_id;
            $validator = $this->validatePromoCodeList();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if(!$vendor){
                return response()->json(['error' => 'Invalid vendor id.'], 404);
            }
            $now = Carbon::now()->toDateTimeString();
            $product_ids = Product::where('vendor_id', $request->vendor_id)->pluck("id");
            $cartData = CartProduct::with(['vendor', 'coupon'=> function($qry) use($cart_id){
                        $qry->where('cart_id', $cart_id);
                    }, 'coupon.promo.details', 'vendorProducts.pvariant.media.image', 'vendorProducts.product.media.image', 
                    'vendorProducts.pvariant.vset.variantDetail.trans' => function($qry) use($language_id){
                        $qry->where('language_id', $language_id);
                    },
                    'vendorProducts.pvariant.vset.optionData.trans' => function($qry) use($language_id){
                        $qry->where('language_id', $language_id);
                    },
                    'vendorProducts.product.translation' => function($q) use($language_id){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                        $q->where('language_id', $language_id);
                    },
                    'vendorProducts'=> function($qry) use($cart_id){
                        $qry->where('cart_id', $cart_id);
                    },                    
                    'vendorProducts.addon.set' => function($qry) use($language_id){
                        $qry->where('language_id', $language_id);
                    },
                    'vendorProducts.addon.option' => function($qry) use($language_id){
                        $qry->where('language_id', $language_id);
                    }, 'vendorProducts.product.taxCategory.taxRate', 
                ])->select('vendor_id')->where('cart_id', $cart_id)->where('vendor_id', $request->vendor_id)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
                $vendor_payable_amount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = 0.00;$total_tax = $total_paying = $total_disc_amount = 0.00; $item_count = 0;
                if($cartData){
                    $tax_details = [];
                    $ttAddon = $payable_amount = $is_coupon_applied = $coupon_removed = 0; $coupon_removed_msg = '';
                    foreach ($cartData as $ven_key => $vendorData) {
                        $codeApplied = $is_percent = $proSum = $proSumDis = $taxable_amount = $discount_amount = $discount_percent = 0;
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
                                if($vendorData->coupon->promo->promo_type_id == 1){
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
                        $deliver_charge = 0;
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
                                $variantsData['sku']                = ucfirst($prod->pvariant->sku);
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
                                        $taxable_amount = $taxable_amount + $product_tax;
                                        $taxData[$tckey]['sku'] = ucfirst($prod->pvariant->sku);
                                        $taxData[$tckey]['identifier'] = $tax_value->identifier;
                                        $tax_details[] = array(
                                            'rate' => $rate,
                                            'tax_amount' => $tax_amount,
                                            'identifier' => $tax_value->identifier,
                                            'sku' => ucfirst($prod->pvariant->sku),
                                        );
                                    }
                                }
                                $prod->taxdata = $taxData;

                                if(!empty($prod->product->Requires_last_mile) && $prod->product->Requires_last_mile == 1){   
                                    $deliver_charge = $this->getDeliveryFeeDispatcher($vendorData->vendor_id);
                                }
                                if(!empty($prod->addon)){
                                    foreach ($prod->addon as $ck => $addons) {
                                        $opt_quantity_price = 0;
                                        $opt_price_in_currency = $addons->option ? $addons->option->price : 0;
                                        $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                                        $opt_quantity_price = $opt_price_in_doller_compare * $prod->quantity;
                                        $vendorAddons[$ck]['quantity'] = $prod->quantity;
                                        $vendorAddons[$ck]['addon_id'] = $addons->addon_id;
                                        $vendorAddons[$ck]['option_id'] = $addons->option_id;
                                        $vendorAddons[$ck]['price'] = $opt_price_in_currency;
                                        $vendorAddons[$ck]['addon_title'] = $addons->set->title;
                                        $vendorAddons[$ck]['quantity_price'] = $opt_quantity_price;
                                        $vendorAddons[$ck]['option_title'] = $addons->option ? $addons->option->title : 0;
                                        $vendorAddons[$ck]['price_in_cart'] = $addons->option->price;
                                        $vendorAddons[$ck]['cart_product_id'] = $addons->cart_product_id;
                                        $vendorAddons[$ck]['multiplier'] = $clientCurrency->doller_compare;
                                        $ttAddon = $ttAddon + $opt_quantity_price;
                                        $payable_amount = $payable_amount + $opt_quantity_price;
                                    }
                                }
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
                        $vendor_payable_amount = $payable_amount - $discount_amount - $deliver_charge;
                    }
                }
            if($product_ids){
                $promo_code_details = PromoCodeDetail::whereIn('refrence_id', $product_ids->toArray())->pluck('promocode_id');
                if($promo_code_details->count() > 0){
                    $result1 = Promocode::whereIn('id', $promo_code_details->toArray())->whereDate('expiry_date', '>=', $now)->where('restriction_on', 0)->where('restriction_type', 0)->where('is_deleted', 0)->get();
                    $promo_codes = $promo_codes->merge($result1);
                }
                $vendor_promo_code_details = PromoCodeDetail::whereHas('promocode')->where('refrence_id', $vendor_id)->pluck('promocode_id');
                $result2 = Promocode::whereIn('id', $vendor_promo_code_details->toArray())->where('restriction_on', 1)->whereHas('details', function($q) use($vendor_id){
                    $q->where('refrence_id', $vendor_id);
                })->where('restriction_on', 1)->where('is_deleted', 0)->where('minimum_spend','>=', $vendor_payable_amount)->whereDate('expiry_date', '>=', $now)->get();
                $promo_codes = $promo_codes->merge($result2);
            }
            return $this->successResponse($promo_codes, '', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function postVerifyPromoCode(Request $request){
        try {
            $validator = $this->validatePromoCode();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if(!$vendor){
                return response()->json(['error' => 'Invalid vendor id.'], 404);
            }
            $cart_detail = Cart::where('id', $request->cart_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Cart Id', 422);
            }
            $cart_detail = Promocode::where('id', $request->coupon_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Promocode Id', 422);
            }
            $cart_coupon_detail = CartCoupon::where('cart_id', $request->cart_id)->where('vendor_id', $request->vendor_id)->where('coupon_id', $request->coupon_id)->first();
            if($cart_coupon_detail){
                return $this->errorResponse('Coupon Code already applied.', 422);
            }
            $cart_coupon_detail2 = CartCoupon::where('cart_id', $request->cart_id)->where('coupon_id', $request->coupon_id)->first();
            if($cart_coupon_detail2){
                return $this->errorResponse('Coupon Code already applied other vendor.', 422);
            }
            $cart_coupon = new CartCoupon();
            $cart_coupon->cart_id = $request->cart_id;
            $cart_coupon->vendor_id = $request->vendor_id;
            $cart_coupon->coupon_id = $request->coupon_id;
            $cart_coupon->save();
            return $this->successResponse($cart_coupon, 'Promotion Code Used Successfully.', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    public function postRemovePromoCode(Request $request){
        try {
            $validator = $this->validatePromoCode();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $cart_detail = Cart::where('id', $request->cart_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Cart Id', 422);
            }
            $cart_detail = Promocode::where('id', $request->coupon_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Promocode Id', 422);
            }
            CartCoupon::where('cart_id', $request->cart_id)->where('coupon_id', $request->coupon_id)->delete();
            return $this->successResponse(null, 'Promotion Code Removed Successfully.', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function validatePromoCodeList(){
        return Validator::make(request()->all(), [
            'vendor_id' => 'required',
        ]);
    }
    
    public function validatePromoCode(){
        return Validator::make(request()->all(), [
            'cart_id' => 'required',
            'vendor_id' => 'required',
            'coupon_id' => 'required',
        ]);
    }
}
