<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Password;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Client, Category, Brand, Cart, ReferAndEarn, ClientPreference, Vendor, ClientCurrency, User, Country, UserRefferal, Wallet, WalletHistory,CartProduct, PaymentOption};
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;

class CustomerAuthController extends FrontController{

    public function getTestHtmlPage(){
        $active_methods = PaymentOption::select('id', 'code', 'title')->where('status', 1)->get();
        return view('test')->with('active_methods', $active_methods);
    }

    public function loginForm($domain = ''){
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend.account.loginnew')->with(['navCategories' => $navCategories]);
    }

    public function registerForm($domain = ''){
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('frontend.account.registernew')->with(['navCategories' => $navCategories]);
    }

    /**     * Display forgotPassword Form     */
    public function forgotPasswordForm($domain = ''){
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/forgotPassword')->with(['navCategories' => $navCategories]);
    }

    /**     * Display resetPassword Form     */
    public function resetPasswordForm($domain = ''){
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/resetPassword')->with(['navCategories' => $navCategories]);
    }

    /**     * Display resetPassword Form     */
    public function resetSuccess($domain = ''){
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/resetSuccess')->with(['navCategories' => $navCategories]);
    }


    /**     * check if cookie already exist     */
    public function checkCookies($userid){
        if (\Cookie::has('uuid')) {
            $existCookie = \Cookie::get('uuid');
            $userFind = User::where('system_id', $existCookie)->first();
            if($userFind){
                $cart = Cart::where('user_id', $userFind->id)->first();
                if($cart){
                    $cart->user_id = $userid;
                    $cart->save();
                }
                $userFind->delete();
            }
            \Cookie::queue(\Cookie::forget('uuid'));
            return redirect()->route('user.checkout');
        }
    }

    /**     * Display login Form     */
    public function login(LoginRequest $req, $domain = ''){
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            $userid = Auth::id();
            $this->checkCookies($userid);
            $user_cart = Cart::where('user_id', $userid)->first();
            if($user_cart){
                $unique_identifier_cart = Cart::where('unique_identifier', session()->get('_token'))->first();
                if($unique_identifier_cart){
                    $unique_identifier_cart_products = CartProduct::where('cart_id', $unique_identifier_cart->id)->get();
                    foreach ($unique_identifier_cart_products as $unique_identifier_cart_product) {
                        $user_cart_product_detail = CartProduct::where('cart_id', $user_cart->id)->where('product_id', $unique_identifier_cart_product->product_id)->first();
                        if($user_cart_product_detail){
                            $user_cart_product_detail->quantity = ($unique_identifier_cart_product->quantity + $user_cart_product_detail->quantity);
                            $user_cart_product_detail->save();
                            $unique_identifier_cart_product->delete();
                        }else{
                          $unique_identifier_cart_product->cart_id = $user_cart->id;
                          $unique_identifier_cart_product->save();
                        }
                    }
                    $unique_identifier_cart->delete();
                }
            }else{
                Cart::where('unique_identifier', session()->get('_token'))->update(['user_id' => $userid, 'created_by' => $userid, 'unique_identifier' => '']);
            }
            return redirect()->route('user.verify');
        }
        $checkEmail = User::where('email', $req->email)->first();

        if ($checkEmail) {
            return redirect()->back()->with('err_password', 'Password not matched. Please enter correct password.');
        }
        return redirect()->back()->with('err_email', 'Email not exist. Please enter correct email.');
    }

     
    /**     * Display register Form     */
    public function register(SignupRequest $req, $domain = ''){
        try {
            $user = new User();
            $county = Country::where('code', strtoupper($req->countryData))->first();
            $phoneCode = mt_rand(100000, 999999);
            $emailCode = mt_rand(100000, 999999);
            $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
            $user->type = 1;
            $user->status = 1;
            $user->role_id = 1;
            $user->name = $req->name;
            $user->email = $req->email;
            $user->is_email_verified = 0;
            $user->is_phone_verified = 0;
            $user->country_id = $county->id;
            $user->phone_token = $phoneCode;
            $user->email_token = $emailCode;
            $user->phone_number = $req->full_number;
            $user->phone_token_valid_till = $sendTime;
            $user->email_token_valid_till = $sendTime;
            $user->password = Hash::make($req->password);
            $user->save();
            $userRefferal = new UserRefferal();
            $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
            if($req->refferal_code != null){
                $userRefferal->reffered_by = $req->refferal_code;
            }
            $userRefferal->user_id = $user->id;
            $userRefferal->save();
            if ($user->id > 0) {
                $userCustomData = $this->userMetaData($user->id, 'web', 'web');
                $rae = ReferAndEarn::first();
                if($rae){
                    $userReff_by = UserRefferal::where('refferal_code', $req->refferal_code)->first();
                    $wallet_by = Wallet::where('user_id' , $userReff_by->user_id)->first();
                    $wallet_to = Wallet::where('user_id' , $user->id)->first();
                    if($rae->reffered_by_amount != null){
                        $wallet_history = new WalletHistory();
                        $wallet_history->user_id = $userReff_by->user_id;
                        $wallet_history->wallet_id = $wallet_by->id;
                        $wallet_history->amount = $rae->reffered_by_amount;
                        $wallet_history->save();
                    }
                    if($rae->reffered_to_amount != null){
                        $wallet_history = new WalletHistory();
                        $wallet_history->user_id = $user->id;
                        $wallet_history->wallet_id = $wallet_to->id;
                        $wallet_history->amount = $rae->reffered_to_amount;
                        $wallet_history->save();
                    }
                }
                Auth::login($user);
                $this->checkCookies($user->id);
                return redirect()->route('user.verify');
            }
        } catch (Exception $e) {
            die();
        }  
    }

    public function forgotPassword(Request $request, $domain = ''){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50'
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                $errors['error'] = $error_value[0];
                return response()->json($errors, 422);
            }
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->with('err_email', 'Email not exist. Please enter correct email.');
        }
        $notified = 1;

        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        $newDateTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
        $otp = mt_rand(100000, 999999);
        $user->email_token = $otp;
        $user->email_token_valid_till = $newDateTime;
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $sendto = $user->email;
            $client_name = $client->name;
            $mail_from = $data->mail_from;
            try {
                Mail::send('email.verify',[
                        'customer_name' => ucwords($user->name),
                        'code_text' => 'We have gotton a forget password request from your account. Please enter below otp of verify that it is you.',
                        'code' => $otp,
                        'logo' => $client->logo['original'],
                        'link' => "link"
                ],function ($message) use ($sendto, $client_name, $mail_from) {
                        $message->from($mail_from, $client_name);
                        $message->to($sendto)->subject('OTP to verify account');
                });
                if (Mail::failures()) {
                    pr(Mail::failures());die;
                    return new Error(Mail::failures()); 
                }
                $notified = 1;
            } catch (\Exception $e) {
                $user->save();
            }
        }
        $user->save();
        if ($notified == 1) {
            return redirect()->route('customer.resetPassword');
        }
    }

    /**     * Display resetPassword Form     */
    public function resetPassword(Request $request, $domain = ''){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'otp' => 'required|string|min:6|max:50',
            'new_password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                $errors['error'] = $error_value[0];
                return redirect()->back()->with($errors);
            }
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->with('err_email', 'User not found.');
        }
        if ($user->email_token != $request->otp) {
            return redirect()->back()->with('err_otp', 'OTP is not valid');
        }
        $currentTime = \Carbon\Carbon::now()->toDateTimeString();
        if ($currentTime > $user->email_token_valid_till) {
            return redirect()->back()->with('err_otp', 'OTP has been expired.');
        }
        $user->password = Hash::make($request['new_password']);
        $user->save();
        return redirect()->route('customer.resetSuccess');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('customer.login');
    }

    public function stripeCharge(request $request)
    {
        $paypal_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($paypal_creds->credentials);

        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';

        $message = $html = $transactionReference = '';
        
        try{
            $gateway = Omnipay::create('Stripe');
            $token = $request->input('stripe_token');
            $gateway->setApiKey($api_key);
            $gateway->setTestMode(true);
            
            // Send purchase request
            $response = $gateway->purchase(
                [
                    'amount' => $request->input('amount'),
                    'currency' => 'INR',
                    'description' => 'This is a test purchase transaction.',
                    'token' => $token,
                    'metadata' => ['order_id' => '10']
                ]
            )->send();

            // Process response
            if ($response->isSuccessful()) {

                // $html = file_get_contents($response->getData()['receipt_url']);
                return response()->json(array('success' => true, 'transactionReference'=>$response->getTransactionReference(), 'msg'=>"Thankyou for your payment"));

            } elseif ($response->isRedirect()) {
                
                // Redirect to offsite payment gateway
                return response()->json(array('success' => true, 'redirect_url'=>$response->getRedirectUrl(), 'msg'=>''));

            } else {
                // Payment failed
                return response()->json(array('success' => false, 'msg'=>$response->getMessage()));
            }
        }
        catch(\Exception $ex){
            return response()->json(array('success' => false, 'msg'=>$ex->getMessage()));
        }
    }

    public function paypalCharge(request $request)
    {
        $paypal_creds = PaymentOption::select('credentials')->where('code', 'paypal')->where('status', 1)->first();
        $creds_arr = json_decode($paypal_creds->credentials);

        $username = (isset($creds_arr->username)) ? $creds_arr->username : '';
        $password = (isset($creds_arr->password)) ? $creds_arr->password : '';
        $signature = (isset($creds_arr->signature)) ? $creds_arr->signature : '';

        $message = $html = $transactionReference = '';
        
        try{
            $gateway = Omnipay::create('PayPal_Express');
            $gateway->setUsername('sb-r6ryi6463363_api1.business.example.com');
            $gateway->setPassword('2WT35LCJ73SYWLMD');
            $gateway->setSignature('Ai9cuHQXupERagE016AbIPpQXy9fAgblu9y2ZXrzYkt1e0GUY.EPoJBl');
            $gateway->setTestMode(true); //set it to 'false' when go live

            // Send purchase request
            $response = $gateway->purchase(
                [
                    'amount' => $request->input('amount'),
                    'currency' => 'USD',
                    // 'card' => $formInputData,
                    'returnUrl' => url('/payment/paypalSuccess'),
                    'cancelUrl' => url('/payment/paypalError')
                ]
            )->send();

            // dd($response);
            // exit;

            // Process response
            if ($response->isSuccessful()) {
                
                return response()->json(array('success' => true, 'transactionReference'=>$response->getTransactionReference(), 'msg'=>"Thankyou for your payment"));

            } elseif ($response->isRedirect()) {
                
                // Redirect to offsite payment gateway
                // $response->redirect();
                return response()->json(array('success' => true, 'redirect_url'=>$response->getRedirectUrl()));

            } else {
                // Payment failed
                return response()->json(array('success' => false, 'msg'=>$response->getMessage()));
            }
        }
        catch(\Exception $ex){
            return response()->json(array('success' => false, 'msg'=>$ex->getMessage()));
        }
        
    }

    public function paypalSuccess(request $request)
    {
        $message = $html = '';

        if($request->has(['token', 'PayerID'])){
            $transaction = $this->paypalGateway->completePurchase(array(
                'amount' => '0.01',
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('token'),
            ));

            $response = $transaction->send();

            if ($response->isSuccessful())
            {
                // The customer has successfully paid.
                $arr_body = $response->getData();
                
                // dd($arr_body);
                // exit;
         
                // Insert transaction data into the database
         
                $message = "Payment is successful. Your transaction id is: ". $arr_body['TOKEN'];
            } else {
                $message = $response->getMessage();
            }
        } else {
            $message = 'Transaction is declined';
        }

    }

    public function paypalError(request $request)
    {
        $message = $html = '';

        
        dd($request);
        exit;
        
    }
}
