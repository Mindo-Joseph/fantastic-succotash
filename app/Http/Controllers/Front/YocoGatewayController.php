<?php

namespace App\Http\Controllers\Front;

// use Log;
use WebhookCall;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yoco\YocoClient;
use Yoco\Exceptions\ApiKeyException;
use Yoco\Exceptions\DeclinedException;
use Yoco\Exceptions\InternalException;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\WalletController;
use App\Models\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax, SubscriptionPlansUser};

class YocoGatewayController extends FrontController
{
    use ApiResponser;
    public $SECRET_KEY;
    public $PUBLIC_KEY;
    public $test_mode;
    public $currency;

    public function __construct()
    {
        $yoco_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'yoco')->where('status', 1)->first();
        $creds_arr = json_decode($yoco_creds->credentials);
        $secret_key = (isset($creds_arr->secret_key)) ? $creds_arr->secret_key : '';
        $public_key = (isset($creds_arr->public_key)) ? $creds_arr->public_key : '';
        $this->test_mode = (isset($yoco_creds->test_mode) && ($yoco_creds->test_mode == '1')) ? true : false;
        $this->SECRET_KEY = $secret_key;
        $this->PUBLIC_KEY = $public_key;

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function yocoPurchase(Request $request)
    {
        try {
            $user = Auth::user();
            $token = $request->token;
            $amount = $this->getDollarCompareAmount($request->amount);
            $amount = filter_var($amount, FILTER_SANITIZE_NUMBER_INT);

            $customer_data = array(
                'email' => $user->email,
                'name' => $user->name,
                // 'identification' => '12123123'
            );
            $reference_number = $description = '';

            if($request->payment_form == 'cart'){
                $description = 'Order Checkout';
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
                $customer_data['cart_id'] = $cart->id;
                if($request->has('order_number')){
                    $reference_number = $request->order_number;
                }
            }
            elseif($request->payment_form == 'wallet'){
                $description = 'Wallet Checkout';
                if($request->has('subscription_id')){
                    $reference_number = $request->token;
                }
            }
            if($request->payment_form == 'tip'){
                $description = 'Tip Checkout';
                $customer_data['order_number'] = $request->order_number;
                if($request->has('order_number')){
                    $reference_number = $request->order_number;
                }
            }
            elseif($request->payment_form == 'subscription'){
                $description = 'Subscription Checkout';
                if($request->has('subscription_id')){
                    $slug = $request->subscription_id;
                    $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
                    $customer_data['subscription_id'] = $subscription_plan->id;
                    $reference_number = $request->subscription_id;
                }
            }

            $checkout_data = array(
                'token' => $token,
                'amountInCents' => $amount,
                'currency' => 'ZAR',
                'description' => $description,
                'reference' => $reference_number,
                'redirect' => false,
                'test' => $this->test_mode, // True, testing, false, production
                'customer' => $customer_data
            );
         
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://online.yoco.com/v1/charges/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_USERPWD, $this->SECRET_KEY . ":");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($checkout_data));

            // send to yoco
            $result = curl_exec($ch);
            $result = json_decode($result);
            if ($result->status == 'successful') {
                if ($request->payment_form == '') {
                    return $this->successResponse($result);
                }
                $this->yocoSuccess($request, $result);
                return $this->successResponse($result);
            } else {
                $this->yocoFail($request);
                return $this->errorResponse($result->status, 400);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function yocoSuccess($request, $result)
    {
        $transactionId = $result->id;
        if ($request->payment_form == 'cart') {
            $order_number = $request->order_number;
            $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
            if ($order) {

                $order->payment_status = 1;
                $order->save();
                $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                if (!$payment_exists) {
                    Payment::insert([
                        'date' => date('Y-m-d'),
                        'order_id' => $order->id,
                        'transaction_id' => $transactionId,
                        'balance_transaction' => $request->amount,
                    ]);

                    // Auto accept order
                    $orderController = new OrderController();
                    $orderController->autoAcceptOrderIfOn($order->id);

                    // Remove cart

                    Cart::where('id', $request->cart_id)->update(['schedule_type' => NULL, 'scheduled_date_time' => NULL]);
                    CartAddon::where('cart_id', $request->cart_id)->delete();
                    CartCoupon::where('cart_id', $request->cart_id)->delete();
                    CartProduct::where('cart_id', $request->cart_id)->delete();
                    CartProductPrescription::where('cart_id', $request->cart_id)->delete();

                    // Send Notification
                    if (!empty($order->vendors)) {
                        foreach ($order->vendors as $vendor_value) {
                            $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                            $user_vendors = UserVendor::where(['vendor_id' => $vendor_value->vendor_id])->pluck('user_id');
                            $orderController->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail);
                        }
                    }
                    $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id);
                    $super_admin = User::where('is_superadmin', 1)->pluck('id');
                    $orderController->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail);

                    // Send Email

                }
            }
        }
        elseif ($request->payment_form == 'wallet') {
            
        }
        elseif ($request->payment_form == 'tip') {

        }
    }

    public function yocoFail($request)
    {
        if ($request->payment_form == 'cart') {
            $order_number = $request->order_number;
            $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
            $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
            foreach ($order_products as $order_prod) {
                OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
            }
            OrderProduct::where('order_id', $order->id)->delete();
            OrderProductPrescription::where('order_id', $order->id)->delete();
            VendorOrderStatus::where('order_id', $order->id)->delete();
            OrderVendor::where('order_id', $order->id)->delete();
            OrderTax::where('order_id', $order->id)->delete();
            Order::where('id', $order->id)->delete();
            return Redirect::to(url('viewcart'));
        }
        elseif ($request->payment_form == 'wallet') {
            
        }
        elseif ($request->payment_form == 'tip') {
            
        }
    }

    public function yocoPurchaseApp(Request $request)
    {
        try {
            $user = User::where('auth_token', $request->auth_token)->first();
            Auth::login($user);
            $token = $request->token;
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            $amount = $this->getDollarCompareAmount($request->amount);
            $amount = filter_var($amount, FILTER_SANITIZE_NUMBER_INT);
     
            // $returnUrlParams = '?amount='.$amount;
            // if($request->has('tip')){
            //     $tip = $request->tip;
            //     $returnUrlParams = $returnUrlParams.'&tip='.$tip;
            // }
            // if( ($request->has('address_id')) && ($request->address_id > 0) ){
            //     $address_id = $request->address_id;
            //     $returnUrlParams = $returnUrlParams.'&address_id='.$address_id;
            // }
            $returnUrlParams = '?gateway=yoco&order=' . $request->order_number;

            // $returnUrl = route('user.wallet');
            // $returnUrlParams = '';
            // if (isset($request->order_number)) {
            //     $returnUrlParams = '?gateway=yoco&order=' . $request->order_number;

            //     $returnUrl = route('order.return.success');
            // }

            $checkout_data = array(
                'token' => $token,
                'amountInCents' => $amount,
                'currency' => 'ZAR',
                'description' => 'Order Checkout',
                // 'return_url' => $returnUrl,
                'reference' => $request->order_number,
                'redirect' => false,
                'test' => $this->test_mode, // True, testing, false, production
                'customer' => array(
                    'email' => $user->email,
                    'name' => $user->name,
                    // 'identification' => '12123123',
                    'cart_id' => $cart->id
                )
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://online.yoco.com/v1/charges/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_USERPWD, $this->SECRET_KEY . ":");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($checkout_data));

            // send to yoco
            $result = curl_exec($ch);
            $result = json_decode($result);
            if ($result->status == 'successful') {
                if ($request->payment_form == '') {
                    return $this->successResponse($result);
                }
                $this->yocoSuccessApp($request, $result);
                return $this->successResponse($result);
            } else {
                $this->yocoFailApp($request);
                return $this->errorResponse($result->status, 400);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function yocoSuccessApp($request, $result)
    {
        $transactionId = $result->id;
        if ($request->payment_form == 'cart') {
            $order_number = $request->order_number;
            $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
            if ($order) {

                $order->payment_status = 1;
                $order->save();
                $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                if (!$payment_exists) {
                    Payment::insert([
                        'date' => date('Y-m-d'),
                        'order_id' => $order->id,
                        'transaction_id' => $transactionId,
                        'balance_transaction' => $request->amount,
                    ]);

                    // Auto accept order
                    $orderController = new OrderController();
                    $orderController->autoAcceptOrderIfOn($order->id);

                    // Remove cart

                    Cart::where('id', $request->cart_id)->update(['schedule_type' => NULL, 'scheduled_date_time' => NULL]);
                    CartAddon::where('cart_id', $request->cart_id)->delete();
                    CartCoupon::where('cart_id', $request->cart_id)->delete();
                    CartProduct::where('cart_id', $request->cart_id)->delete();
                    CartProductPrescription::where('cart_id', $request->cart_id)->delete();

                    // Send Notification
                    if (!empty($order->vendors)) {
                        foreach ($order->vendors as $vendor_value) {
                            $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                            $user_vendors = UserVendor::where(['vendor_id' => $vendor_value->vendor_id])->pluck('user_id');
                            $orderController->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail);
                        }
                    }
                    $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id);
                    $super_admin = User::where('is_superadmin', 1)->pluck('id');
                    $orderController->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail);

                    // Send Email

                }
            }
        }
        elseif ($request->payment_form == 'wallet') {
            $walletController = new WalletController();
            $request->request->add(['wallet_amount' => $request->amount, 'transaction_id' => $transactionId]);
            $walletController->creditWallet($request);
        }
        elseif ($request->payment_form == 'tip') {
            
        }
    }

    public function yocoFailApp($request)
    {
        if ($request->payment_form == 'cart') {
            $order_number = $request->order_number;
            $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
            $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
            foreach ($order_products as $order_prod) {
                OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
            }
            OrderProduct::where('order_id', $order->id)->delete();
            OrderProductPrescription::where('order_id', $order->id)->delete();
            VendorOrderStatus::where('order_id', $order->id)->delete();
            OrderVendor::where('order_id', $order->id)->delete();
            OrderTax::where('order_id', $order->id)->delete();
            Order::where('id', $order->id)->delete();
        }
        elseif ($request->payment_form == 'wallet') {
            
        }
        elseif ($request->payment_form == 'tip') {
            
        }
    }
}
