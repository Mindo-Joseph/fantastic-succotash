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
use App\Models\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax};

class YocoGatewayController extends FrontController
{
    use ApiResponser;
    public $SECRET_KEY;
    public $PUBLIC_KEY;
    // public $API_ACCESS_TOKEN;
    public $test_mode;
    // public $mb;

    public function __construct()
    {
        $yoco_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'yoco')->where('status', 1)->first();
        $creds_arr = json_decode($yoco_creds->credentials);
        $secret_key = (isset($creds_arr->secret_key)) ? $creds_arr->secret_key : '';
        $public_key = (isset($creds_arr->public_key)) ? $creds_arr->public_key : '';
        // $api_access_token = (isset($creds_arr->api_access_token)) ? $creds_arr->api_access_token : '';
        $this->test_mode = (isset($yoco_creds->test_mode) && ($yoco_creds->test_mode == '1')) ? true : false;

        $this->SECRET_KEY = $secret_key;
        $this->PUBLIC_KEY = $public_key;
        // $this->API_ACCESS_TOKEN = $api_access_token;


    }

    public function yocoPurchase(Request $request)
    {

        try {
            $user = Auth::user();
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

            $returnUrl = route('order.return.success');
            if ($request->payment_form == 'wallet') {
                $returnUrl = route('user.wallet');
            }

            $checkout_data = array(
                'token' => $token,
                'amountInCents' => $amount,
                'currency' => 'ZAR',
                'description' => 'Order Checkout',
                'return_url' => url($request->returnUrl . $returnUrlParams),
                'reference' => $request->order_number,
                'webhook' => url('/payment/yoco/notify/'),
                'redirect' => false,
                'test' => $this->test_mode, // True, testing, false, production
                // 'options' => array(
                //     'theme' => array(
                //         'type' => 'light', // dark or light color scheme
                //         'showHeader' => true,
                //         'header' => array(
                //             'name' => 'Your brand name',
                //             'logo' => 'https://www.yourstore.com/store-logo.jpg', // Must be https!
                //         ),
                //     ),
                // ),
                'customer' => array(
                    'email' => $user->email,
                    'name' => $user->name,
                    // 'identification' => '12123123',
                    'cart_id' => $cart->id
                )
            );

          //  $client = new YocoClient($this->SECRET_KEY, $this->PUBLIC_KEY);
            // WebhookCall::create()
            //     ->url('payment/yoco/notify')
            //     ->payload($checkout_data)
            //     ->useSecret($this->SECRET_KEY)
            //     ->dispatch();
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://online.yoco.com/v1/charges/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_USERPWD, $this->SECRET_KEY . ":");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($checkout_data));

            // send to yoco
            $result = curl_exec($ch);
            // return $result;
            $result = json_decode($result);
       	          
            if ($result->status == 'successful') {
              $this->yocoSuccess($request,$result);
                // $response = $this->mb->mobbex_checkout($checkout_data);
                return $this->successResponse(url($returnUrl . $returnUrlParams));
            }
            else {
                $this->yocoFail($request);
                return $this->errorResponse($result->status, 400);
            }
            // if ($response['response']['result']) {
            //     return $this->successResponse($response['response']['data']['url']);
            // } elseif (!$response['response']['result']) {
            //     return $this->errorResponse($response['response']['error'], 400);
            // } else {
            //     return $this->errorResponse($response->getMessage(), 400);
            // }

        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function yocoSuccess($request, $result,$domain = '')
    {
        // Notify Mobbex that information has been received
        // header( 'HTTP/1.0 200 OK' );
        // flush();
       // Log::info('testing');

  
            $transactionId = $result->id;
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


    public function yocoFail($request, $domain = '')
    {
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

    
}
