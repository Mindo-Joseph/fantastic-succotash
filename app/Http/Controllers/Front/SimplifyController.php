<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Front\{UserSubscriptionController, OrderController, WalletController, FrontController};
use Auth, Log, Redirect;
use App\Models\{PaymentOption, Cart, SubscriptionPlansUser, Order, Payment, CartAddon, CartCoupon, CartProduct, CartProductPrescription, UserVendor, User};

class SimplifyController extends FrontController
{
	use \App\Http\Traits\SimplifyPaymentManager;
	use \App\Http\Traits\ApiResponser;

	private $public_key;
	private $private_key;
	public function __construct()
  	{
		$simp_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'simplify')->where('status', 1)->first();
	    $creds_arr = json_decode($simp_creds->credentials);
	    $this->public_key = $creds_arr->public_key??'';
	    $this->private_key = $creds_arr->private_key??'';
	}

    public function beforePayment(Request $request)
    {
    	$data = $request->all();
    	$data['public_key'] = $this->public_key;
        $data['come_from'] = 'app';
        if($request->isMethod('post'))
        {
            $data['come_from'] = 'web';
        }
        // $data['come_from'] = 'app';
    	return view('frontend.payment_gatway.simplify_view')->with(['data' => $data]);
    }
    public function createPayment(Request $request)
    {
    	$user = Auth::user();
    	$cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
        $amount = $this->getDollarCompareAmount($request->amount);
    	$data = $request->all();
    	$request['username'] = $user->name;
    	$request['email'] = $user->email;
    	$request['source'] = 'WEB';
    	$request['amount'] = $amount*100;
        if($request->payment_from == 'cart'){
            $request['description'] = 'Order Checkout';
            if($request->has('order_number')){
                $request['reference'] = $request->order_number;
            }
        }
        elseif($request->payment_from == 'wallet'){
            $request['description'] = 'Wallet Checkout';
            $request['reference'] = $user->id;
        }
        elseif($request->payment_from == 'tip'){
            $request['description'] = 'Tip Checkout';
            if($request->has('order_number')){
                $request['reference'] = $request->order_number;
            }
        }
        elseif($request->payment_from == 'subscription'){
            $request['description'] = 'Subscription Checkout';
            if($request->has('subscription_id')){
                $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $request->subscription_id)->where('status', '1')->first();
                $request['reference'] = $request->subscription_id;
            }
        }
    	$payment = $this->create_payment($request->all());
    	$request['amount'] = $amount;
    	if($payment->paymentStatus == 'APPROVED')
    	{
            $returnUrl = $this->sucessPayment($request,$payment);
        } else{
            $returnUrl = $this->failedPayment($request,$payment);
        }
        // dd($returnUrl);
        return Redirect::to(url($returnUrl));
    }
    public function sucessPayment($request, $pamyent)
    {
    	$transactionId = $pamyent->id;
    	if($request->payment_from == 'cart'){
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
                        'type' => 'cart'
                    ]);

                    // Auto accept order
                    $orderController = new OrderController();
                    $orderController->autoAcceptOrderIfOn($order->id);

                    // Remove cart
                    Cart::where('id', $request->cart_id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
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
                }
                if($request->come_from == 'app')
                {
                    $returnUrl = route('payment.gateway.return.response').'/?gateway=simplify'.'&status=200&transaction_id='.$transactionId;
                }else{
                    $returnUrl = route('order.return.success');
                }
                
                return $returnUrl;
            }
        } elseif($request->payment_from == 'wallet'){
            $request->request->add(['wallet_amount' => $request->amount, 'transaction_id' => $transactionId]);
            $walletController = new WalletController();
            $walletController->creditWallet($request);
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=simplify'.'&status=200&transaction_id='.$transactionId;
            }else{
                $returnUrl = route('user.wallet');
            }
            return $returnUrl;
        }
        elseif($request->payment_from == 'tip'){
            $request->request->add(['order_number' => $request->order_number, 'tip_amount' => $request->amount, 'transaction_id' => $transactionId]);
            $orderController = new OrderController();
            $orderController->tipAfterOrder($request);
            $returnUrl = route('user.orders');
            return $returnUrl;
        }
        elseif($request->payment_from == 'subscription'){
            $request->request->add(['payment_option_id' => 12, 'transaction_id' => $transactionId]);
            $subscriptionController = new UserSubscriptionController();
            $subscriptionController->purchaseSubscriptionPlan($request, '', $request->subscription_id);
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=simplify'.'&status=200&transaction_id='.$transactionId;
            }else{
                $returnUrl = route('user.subscription.plans');
            }
            return $returnUrl;
        }
        return Redirect::to(route('order.return.success'));
    }
    public function failedPayment($request, $pamyent)
    {
    	if($request->payment_from == 'cart'){
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
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=simplify&status=0';
            }else{
                $returnUrl = route('showCart');
            }
            return $returnUrl;
        }
        elseif($request->payment_form == 'wallet'){
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=simplify&status=0';
            }else{
                $returnUrl = route('user.wallet');
            }
            return $returnUrl;
        }
        elseif($request->payment_form == 'tip'){
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=simplify&status=0';
            }else{
                $returnUrl = route('user.orders');
            }
            return $returnUrl;
        }
        elseif($request->payment_form == 'subscription'){
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=simplify&status=0';
            }else{
                $returnUrl = route('user.subscription.plans');
            }
            return $returnUrl;
        }
        return route('order.return.success');
    }
}
