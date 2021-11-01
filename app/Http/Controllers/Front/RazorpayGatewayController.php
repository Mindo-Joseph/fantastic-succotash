<?php

namespace App\Http\Controllers\Front;


use Log;
use Auth;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\OrderController;
use App\Models\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax};

class RazorpayGatewayController extends FrontController
{
    use ApiResponser;
    public $API_KEY;
    public $API_SECRET_KEY;
    public $test_mode;
   public $api;

    public function __construct()
    {
        $razorpay_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'razorpay')->where('status', 1)->first();
        $creds_arr = json_decode($razorpay_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $api_secret_key = (isset($creds_arr->api_secret_key)) ? $creds_arr->api_secret_key : '';
        $this->test_mode = (isset($razorpay_creds->test_mode) && ($razorpay_creds->test_mode == '1')) ? true : false;

        $this->API_KEY = $api_key;
        $this->API_SECRET_KEY = $api_secret_key;
        $this->api = new Api($api_key, $api_secret_key);

      
    }

    public function razorpayPurchase(Request $request){
        try{
            $user = Auth::user();
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            $amount = $this->getDollarCompareAmount($request->amount);
            // $returnUrlParams = '?amount='.$amount;
            // if($request->has('tip')){
            //     $tip = $request->tip;
            //     $returnUrlParams = $returnUrlParams.'&tip='.$tip;
            // }
            // if( ($request->has('address_id')) && ($request->address_id > 0) ){
            //     $address_id = $request->address_id;
            //     $returnUrlParams = $returnUrlParams.'&address_id='.$address_id;
            // }
            $returnUrlParams = '?gateway=razorpay&order='.$request->order_number;

            $returnUrl = route('order.return.success');
            if($request->payment_form == 'wallet'){
                $returnUrl = route('user.wallet');
            }

            // $checkout_data = array(
            //     'total' => $amount,
            //     'currency' => 'ARS',
            //     'description' => 'Order Checkout',
            //     'return_url' => url($request->returnUrl . $returnUrlParams),
            //     'reference' => $request->order_number,
            //     'webhook' => url('payment/razorpay/notify'),
            //     'redirect' => false,
            //     'test' => $this->test_mode, // True, testing, false, production
            //     // 'options' => array(
            //     //     'theme' => array(
            //     //         'type' => 'light', // dark or light color scheme
            //     //         'showHeader' => true,
            //     //         'header' => array(
            //     //             'name' => 'Your brand name',
            //     //             'logo' => 'https://www.yourstore.com/store-logo.jpg', // Must be https!
            //     //         ),
            //     //     ),
            //     // ),
            //     'customer' => array(
            //         'email' => $user->email,
            //         'name' => $user->name,
            //         // 'identification' => '12123123',
            //         'cart_id' => $cart->id
            //     )
            // );
            $orderData = [
                'receipt'         => $request->order_number,
                'amount'          => $amount, // 39900 rupees in paise
                'currency'        => 'INR'
            ];
            $razorpayOrder = $api->order->create($orderData);
           // $response = $this->mb->mobbex_checkout($checkout_data);
           dd($razorpayOrder);
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function razorpayNotify(Request $request, $domain = '')
    {
        // Notify Mobbex that information has been received
        // header( 'HTTP/1.0 200 OK' );
        // flush();
        // Log::info($request->all());

        $data = $request->data;
        if($data['result'] == 'true'){
            $payment_details = $data['payment'];
            $transactionId = $payment_details['id'];
            $order_number = $payment_details['reference'];
            $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
            if($order){
                if($payment_details['status']['code'] == 200){
                    $order->payment_status = 1;
                    $order->save();
                    $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                    if(!$payment_exists){
                        Payment::insert([
                            'date' => date('Y-m-d'),
                            'order_id' => $order->id,
                            'transaction_id' => $transactionId,
                            'balance_transaction' => $payment_details['total'],
                        ]);

                        // Auto accept order
                        $orderController = new OrderController();
                        $orderController->autoAcceptOrderIfOn($order->id);

                        // Remove cart
                        $user = $data['customer'];
                        Cart::where('id', $user['cart_id'])->update(['schedule_type' => NULL, 'scheduled_date_time' => NULL]);
                        CartAddon::where('cart_id', $user['cart_id'])->delete();
                        CartCoupon::where('cart_id', $user['cart_id'])->delete();
                        CartProduct::where('cart_id', $user['cart_id'])->delete();
                        CartProductPrescription::where('cart_id', $user['cart_id'])->delete();

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
                     //   $this->successMail();
                    }
                }else{
                    $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
                    foreach($order_products as $order_prod){
                        OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
                    }
                    OrderProduct::where('order_id', $order->id)->delete();
                    OrderProductPrescription::where('order_id', $order->id)->delete();
                    VendorOrderStatus::where('order_id', $order->id)->delete();
                    OrderVendor::where('order_id', $order->id)->delete();
                    OrderTax::where('order_id', $order->id)->delete();
                    Order::where('id', $order->id)->delete();
                    $this->failMail();
                }
            }
        }

    }

   
}
