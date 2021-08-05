<?php

namespace App\Mail;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\ClientCurrency;
use App\Models\ClientPreference;
use App\Models\LoyaltyCard;
use App\Models\Order;
use App\Models\SubscriptionInvoicesUser;
use App\Models\UserAddress;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use DB;
use Carbon\Carbon;
use Session;

class OrderSuccessEmail extends Mailable{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData){
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        // dd($this->mailData);
        $user = Auth::user();
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
        }
        $cartData = '';
        if ($cart) {
            dd("mlrek");
            $cartData = CartProduct::where('status', [0, 1])->where('cart_id', $cart->id)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        }
        dd($cartData->toArray());
        if ($cartData && !empty($cart_details)) {
            return response()->json([
                'data' => $cart_details,
            ]);
        }
        return $this->view('email.orderSuccesseEmail')->from($this->mailData['mail_from'])->subject($this->mailData['subject'])->with('mailData', $this->mailData);
    }

    /**
     * Get Cart Items
     *
     */
    public function getCart($cart, $address_id=0)
    {
        $cart_id = $cart->id;
        $user = Auth::user();
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $pharmacy = ClientPreference::first();
        $cart->pharmacy_check = $pharmacy->pharmacy_check;
        $customerCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $latitude = '';
        $longitude = '';
        if($address_id > 0){
            $address = UserAddress::where('user_id', $user->id)->where('id', $address_id)->first();
        }else{
            $address = UserAddress::where('user_id', $user->id)->where('is_primary', 1)->first();
            $address_id = ($address) ? $address->id : 0;
        }
        $latitude = ($address) ? $address->latitude : '';
        $longitude = ($address) ? $address->longitude : '';
        $cartData = CartProduct::with([
            'vendor', 'coupon' => function ($qry) use ($cart_id) {
                $qry->where('cart_id', $cart_id);
            }, 'vendorProducts.pvariant.media.image', 'vendorProducts.product.media.image',
            'vendorProducts.pvariant.vset.variantDetail.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.pvariant.vset.optionData.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.product.translation_one' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'vendorProducts' => function ($qry) use ($cart_id) {
                $qry->where('cart_id', $cart_id);
            },
            'vendorProducts.addon.set' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.addon.option' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendorProducts.product.taxCategory.taxRate',
        ])->select('vendor_id', 'luxury_option_id')->where('status', [0, 1])->where('cart_id', $cart_id)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        $loyalty_amount_saved = 0;
        $redeem_points_per_primary_currency = '';
        $loyalty_card = LoyaltyCard::where('status', '0')->first();
        if ($loyalty_card) {
            $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
        }
        $subscription_features = array();
        if($user){
            $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                    $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                    if($customerCurrency->is_primary != 1){
                        $loyalty_amount_saved = $loyalty_amount_saved * $customerCurrency->doller_compare;
                    }
                }
            }
            $now = Carbon::now()->toDateTimeString();
            $user_subscription = SubscriptionInvoicesUser::with('features')
                ->select('id', 'user_id', 'subscription_id')
                ->where('user_id', $user->id)
                ->where('end_date', '>', $now)
                ->orderBy('end_date', 'desc')->first();
            if ($user_subscription) {
                foreach ($user_subscription->features as $feature) {
                    $subscription_features[] = $feature->feature_id;
                }
            }
        }
        $total_payable_amount = $total_subscription_discount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = 0.00;
        if ($cartData) {
            $delivery_status = 1;
            foreach ($cartData as $ven_key => $vendorData) {
                $payable_amount = $taxable_amount = $subscription_discount = $discount_amount = $discount_percent = $deliver_charge = $delivery_fee_charges = 0.00;
                $delivery_count = 0;
                // if($address_id > 0){
                //     $serviceArea = $vendorData->vendor->whereHas('serviceArea', function($query) use($latitude, $longitude){
                //         $query->select('vendor_id')
                //         ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                //     })->where('id', $vendorData->vendor_id)->get();
                // }
                foreach ($vendorData->vendorProducts as $ven_key => $prod) {
                    $quantity_price = 0;
                    $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;
                    $price_in_currency = $prod->pvariant->price / $divider;
                    $price_in_doller_compare = $price_in_currency * $customerCurrency->doller_compare;
                    $quantity_price = $price_in_doller_compare * $prod->quantity;
                    $prod->pvariant->price_in_cart = $prod->pvariant->price;
                    $prod->pvariant->price = $price_in_currency;
                    $prod->pvariant->media_one = $prod->pvariant->media ? $prod->pvariant->media->first() : [];
                    $prod->pvariant->media_second = $prod->product->media ? $prod->product->media->first() : [];
                    $prod->pvariant->multiplier = $customerCurrency->doller_compare;
                    $prod->pvariant->quantity_price = number_format($quantity_price, 2);
                    $payable_amount = $payable_amount + $quantity_price;
                    $taxData = array();
                    if (!empty($prod->product->taxCategory) && count($prod->product->taxCategory->taxRate) > 0) {
                        foreach ($prod->product->taxCategory->taxRate as $tckey => $tax_value) {
                            $rate = round($tax_value->tax_rate);
                            $tax_amount = ($price_in_doller_compare * $rate) / 100;
                            $product_tax = $quantity_price * $rate / 100;
                            $taxData[$tckey]['identifier'] = $tax_value->identifier;
                            $taxData[$tckey]['rate'] = $rate;
                            $taxData[$tckey]['tax_amount'] = number_format($tax_amount, 2);
                            $taxData[$tckey]['product_tax'] = number_format($product_tax, 2);
                            $taxable_amount = $taxable_amount + $product_tax;
                            $payable_amount = $payable_amount + $product_tax;
                        }
                        unset($prod->product->taxCategory);
                    }
                    $prod->taxdata = $taxData;
                    foreach ($prod->addon as $ck => $addons) {
                        $opt_price_in_currency = $addons->option->price / $divider;
                        $opt_price_in_doller_compare = $opt_price_in_currency * $customerCurrency->doller_compare;
                        $opt_quantity_price = number_format($opt_price_in_doller_compare * $prod->quantity, 2);
                        $addons->option->price_in_cart = $addons->option->price;
                        $addons->option->price = $opt_price_in_currency;
                        $addons->option->multiplier = $customerCurrency->doller_compare;
                        $addons->option->quantity_price = $opt_quantity_price;
                        $payable_amount = $payable_amount + $opt_quantity_price;
                    }
                    if (isset($prod->pvariant->image->imagedata) && !empty($prod->pvariant->image->imagedata)) {
                        $prod->cartImg = $prod->pvariant->image->imagedata;
                    } else {
                        $prod->cartImg = (isset($prod->product->media[0]) && !empty($prod->product->media[0])) ? $prod->product->media[0]->image : '';
                    }
                    if (!empty($prod->product->Requires_last_mile) && ($prod->product->Requires_last_mile == 1)) {
                        $deliver_charge = $this->getDeliveryFeeDispatcher($vendorData->vendor_id);
                        if (!empty($deliver_charge) && $delivery_count == 0) {
                            $delivery_count = 1;
                            $prod->deliver_charge = number_format($deliver_charge, 2);
                            $payable_amount = $payable_amount + $deliver_charge;
                            $delivery_fee_charges = $deliver_charge;
                        }
                    }
                }
                if ($vendorData->coupon) {
                    if ($vendorData->coupon->promo->promo_type_id == 2) {
                        $total_discount_percent = $vendorData->coupon->promo->amount;
                        $payable_amount -= $total_discount_percent;
                    } else {
                        $gross_amount = number_format(($payable_amount - $taxable_amount), 2);
                        $percentage_amount = ($gross_amount * $vendorData->coupon->promo->amount / 100);
                        $payable_amount -= $percentage_amount;
                    }
                }
                if (in_array(1, $subscription_features)) {
                    $subscription_discount = $subscription_discount + $delivery_fee_charges;
                }
                if(isset($serviceArea)){
                    if($serviceArea->isEmpty()){
                        $vendorData->isDeliverable = 0;
                        $delivery_status = 0;
                    }else{
                        $vendorData->isDeliverable = 1;
                    }
                }
                $vendorData->delivery_fee_charges = number_format($delivery_fee_charges, 2);
                $vendorData->payable_amount = number_format($payable_amount, 2);
                $vendorData->discount_amount = number_format($discount_amount, 2);
                $vendorData->discount_percent = number_format($discount_percent, 2);
                $vendorData->taxable_amount = number_format($taxable_amount, 2);
                $vendorData->product_total_amount = number_format(($payable_amount - $taxable_amount), 2);
                if (!empty($subscription_features)) {
                    $vendorData->product_total_amount = number_format(($payable_amount - $taxable_amount - $subscription_discount), 2);
                }
                $total_payable_amount = $total_payable_amount + $payable_amount;
                $total_taxable_amount = $total_taxable_amount + $taxable_amount;
                $total_discount_amount = $total_discount_amount + $discount_amount;
                $total_discount_percent = $total_discount_percent + $discount_percent;
                $total_subscription_discount = $total_subscription_discount + $subscription_discount;
            }
            $is_percent = 0;
            $amount_value = 0;
            if ($cart->coupon) {
                foreach ($cart->coupon as $ck => $coupon) {
                    if (isset($coupon->promo)) {
                        if ($coupon->promo->promo_type_id == 1) {
                            $is_percent = 1;
                            $total_discount_percent = $total_discount_percent + round($coupon->promo->amount);
                        }
                    }
                }
            }
            if ($is_percent == 1) {
                $total_discount_percent = ($total_discount_percent > 100) ? 100 : $total_discount_percent;
                $total_discount_amount = $total_discount_amount + ($total_payable_amount * $total_discount_percent) / 100;
            }
            if ($amount_value > 0) {
                $amount_value = $amount_value * $customerCurrency->doller_compare;
                $total_discount_amount = $total_discount_amount + $amount_value;
            }
            if (!empty($subscription_features)) {
                $total_discount_amount = $total_discount_amount + $total_subscription_discount;
                $cart->total_subscription_discount = number_format($total_subscription_discount, 2);
            }
            $total_payable_amount = $total_payable_amount - $total_discount_amount;
            if ($loyalty_amount_saved > 0) {
                if ($loyalty_amount_saved > $total_payable_amount) {
                    $loyalty_amount_saved =  $total_payable_amount;
                }
                $total_payable_amount = $total_payable_amount - $loyalty_amount_saved;
            }
            $cart->loyalty_amount = number_format($loyalty_amount_saved, 2);
            $cart->gross_amount = number_format(($total_payable_amount + $total_discount_amount + $loyalty_amount_saved - $total_taxable_amount), 2);
            $cart->new_gross_amount = number_format(($total_payable_amount + $total_discount_amount), 2);
            $cart->total_payable_amount = number_format($total_payable_amount, 2);
            $cart->total_discount_amount = number_format($total_discount_amount, 2);
            $cart->total_taxable_amount = number_format($total_taxable_amount, 2);
            $cart->tip_5_percent = number_format((0.05 * $total_payable_amount), 2);
            $cart->tip_10_percent = number_format((0.1 * $total_payable_amount), 2);
            $cart->tip_15_percent = number_format((0.15 * $total_payable_amount), 2);
            $cart->deliver_status = $delivery_status;
            $cart->products = $cartData->toArray();
        }
        return $cart;
    }
}