<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Password;
use JWT\Token;
use Validation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\{LoginRequest, SignupRequest};
use App\Models\{User, Client, ClientPreference, BlockedToken, Otp, Country, UserDevice, UserVerification, ClientLanguage, CartProduct, Cart, UserRefferal, EmailTemplate};

class AuthController extends BaseController{
    use ApiResponser;
    /**
     * Get Country List
     * * @return country array
     */
    public function countries(Request $request){
        $country = Country::select('id', 'code', 'name', 'nicename', 'phonecode')->get();
        return response()->json([
            'data' => $country
        ]);
    }

    /**
     * Login user and create token
     *
     */
    public function login(LoginRequest $loginReq){
        $errors = array();
        $user = User::with('country')->where('email', $loginReq->email)->first();
        if(!$user){
            $errors['error'] = __('Invalid email');
            return response()->json($errors, 422);
        }
        if(!Auth::attempt(['email' => $loginReq->email, 'password' => $loginReq->password])){
            $errors['error'] = __('Invalid password');
            return response()->json($errors, 422);
        }
        $user = Auth::user();
        $prefer = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format','time_format', 'map_key','sms_provider','verify_email','verify_phone', 'app_template_id', 'web_template_id')->first();
        $verified['is_email_verified'] = $user->is_email_verified;
        $verified['is_phone_verified'] = $user->is_phone_verified;
        $token1 = new Token;
        $token = $token1->make([
            'key' => 'royoorders-jwt',
            'issuer' => 'royoorders.com',
            'expiry' => strtotime('+1 month'),
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();
        $token1->setClaim('user_id', $user->id);
        try {
            Token::validate($token, 'secret');
        } catch (\Exception $e) {

        }
        $user_refferal = UserRefferal::where('user_id', $user->id)->first();

        // $device = UserDevice::where('user_id', $user->id)->first();
        // if(!$device){
        //     $device = new UserDevice();
        //     $device->user_id = $user->id;
        // }
        // $device->device_type = $loginReq->device_type;
        // $device->device_token = $loginReq->device_token;
        // $device->access_token = $token;
        // $device->save();

        if (!empty($loginReq->fcm_token)) {
            $device = UserDevice::updateOrCreate(
                ['device_token' => $loginReq->fcm_token],
                [
                    'user_id' => $user->id,
                    'device_type' => $loginReq->device_type,
                    'access_token' => $token
                ]
            );
        } else {
            $device = UserDevice::updateOrCreate(['device_token' => $loginReq->device_token],
                                                          ['user_id' => $user->id,
                                                          'device_type' => $loginReq->device_type,
                                                          'access_token' => $token]);
        }
        

        $user->auth_token = $token;
        $user->save();

        $user_cart = Cart::where('user_id', $user->id)->first();
        if($user_cart){
            $unique_identifier_cart = Cart::where('unique_identifier', $loginReq->device_token)->first();
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
            Cart::where('unique_identifier', $loginReq->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
        }
        $checkSystemUser = $this->checkCookies($user->id);
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['auth_token'] =  $token;
        $data['source'] = $user->image;
        $data['verify_details'] = $verified;
        $data['is_admin'] = $user->is_admin;
        $data['client_preference'] = $prefer;
        $data['dial_code'] = $user->dial_code;
        $data['phone_number'] = $user->phone_number;
        $data['cca2'] = $user->country ? $user->country->code : '';
        $data['callingCode'] = $user->country ? $user->country->phonecode : '';
        $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code: '';
        return response()->json(['data' => $data]);
    }

    /**
     * User registraiotn
     * @return [status, email, need_email_verify, need_phone_verify]
     */
    public function signup(Request $signReq){
        $validator = Validator::make($signReq->all(), [
            'dial_code'   => 'required|string',
            'device_type'   => 'required|string',
            'device_token'  => 'required|string',
            'country_code'  => 'required|string',
            'name'          => 'required|string|min:3|max:50',
            'password'      => 'required|string|min:6|max:50',
            'email'         => 'required|email|max:50||unique:users',
            'phone_number'  => 'required|string|min:8|max:15|unique:users',
            'refferal_code' => 'nullable|exists:user_refferals,refferal_code',
        ]);
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }
        $user = new User();

        foreach ($signReq->only('name', 'email', 'phone_number', 'country_id','dial_code') as $key => $value) {
            $user->{$key} = $value;
        }
        $country_detail = Country::where('code', $signReq->country_code)->first();
        $phoneCode = mt_rand(100000, 999999);
        $emailCode = mt_rand(100000, 999999);
        $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
        $user->password = Hash::make($signReq->password);
        $user->type = 1;
        $user->status = 1;
        $user->role_id = 1;
        $user->is_email_verified = 0;
        $user->is_phone_verified = 0;
        $user->phone_token = $phoneCode;
        $user->email_token = $emailCode;
        $user->country_id = $country_detail->id;
        $user->phone_token_valid_till = $sendTime;
        $user->email_token_valid_till = $sendTime;
        $user->save();
        $wallet = $user->wallet;
        $userRefferal = new UserRefferal();
        $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
        if($signReq->refferal_code != null){
            $userRefferal->reffered_by = $signReq->refferal_code;
        }
        $userRefferal->user_id = $user->id;
        $userRefferal->save();
        $user_cart = Cart::where('user_id', $user->id)->first();
        if($user_cart){
            $unique_identifier_cart = Cart::where('unique_identifier', $signReq->device_token)->first();
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
            Cart::where('unique_identifier', $signReq->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
        }
        $token1 = new Token;
        $token = $token1->make([
            'key' => 'royoorders-jwt',
            'issuer' => 'royoorders.com',
            'expiry' => strtotime('+1 month'),
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();
        $token1->setClaim('user_id', $user->id);
        $user->auth_token = $token;
        $user->save();
        if($user->id > 0){
            if($signReq->refferal_code){
                $refferal_amounts = ClientPreference::first();
                if($refferal_amounts){
                    if($refferal_amounts->reffered_by_amount != null && $refferal_amounts->reffered_to_amount != null){
                        $reffered_by = UserRefferal::where('refferal_code', $signReq->refferal_code)->first();
                        $user_refferd_by = $reffered_by->user_id;
                        $user_refferd_by = User::where('id', $reffered_by->user_id)->first();
                        if($user_refferd_by){
                            //user reffered by amount
                            $wallet_user_reffered_by = $user_refferd_by->wallet;
                            $wallet_user_reffered_by->deposit($refferal_amounts->reffered_by_amount, ['Referral code used by <b>' . $signReq->name . '</b>']);
                            $wallet_user_reffered_by->balance;
                            //user reffered to amount
                            $wallet->deposit($refferal_amounts->reffered_to_amount, ['You used referal code of <b>' . $user_refferd_by->name . '</b>']);
                            $wallet->balance;
                        }
                    }
                }
            }
            $checkSystemUser = $this->checkCookies($user->id);
            $response['status'] = 'Success';
            $response['name'] = $user->name;
            $response['auth_token'] =  $token;
            $response['email'] = $user->email;
            $response['dial_code'] = $user->dial_code;
            $response['phone_number'] = $user->phone_number;
            $verified['is_email_verified'] = 0;
            $verified['is_phone_verified'] = 0;
            $prefer = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 
                        'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
            $response['verify_details'] = $verified;
            $response['cca2'] = $user->country ? $user->country->code : '';
            $preferData['map_key'] = $prefer->map_key;
            $preferData['theme_admin'] = $prefer->theme_admin;
            $preferData['date_format'] = $prefer->date_format;
            $preferData['time_format'] = $prefer->time_format;
            $preferData['map_provider'] = $prefer->map_provider;
            $preferData['sms_provider'] = $prefer->sms_provider;
            $preferData['verify_email'] = $prefer->verify_email;
            $preferData['verify_phone'] = $prefer->verify_phone;
            $preferData['distance_unit'] = $prefer->distance_unit;
            $preferData['app_template_id'] = $prefer->app_template_id;
            $preferData['web_template_id'] = $prefer->web_template_id;
            $response['client_preference'] = $preferData;
            $response['refferal_code'] = $userRefferal ? $userRefferal->refferal_code: '';
            
            // $user_device[] = [
            //     'access_token' => '',
            //     'user_id' => $user->id,
            //     'device_type' => $signReq->device_type,
            //     'device_token' => $signReq->device_token,
            // ];
            // UserDevice::insert($user_device);

            if (!empty($signReq->fcm_token)) {
                $user_device = UserDevice::updateOrCreate(
                    ['device_token' => $signReq->fcm_token],
                    [
                        'user_id' => $user->id,
                        'device_type' => $signReq->device_type,
                        'access_token' => $token
                    ]
                );
            } else {
                $user_device = UserDevice::updateOrCreate(
                    ['device_token' => $signReq->device_token],
                    [
                        'user_id' => $user->id,
                        'device_type' => $signReq->device_type,
                        'access_token' => $token
                    ]
                );
            }

            if(!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)){
                $response['send_otp'] = 1;
                if($user->dial_code == "971"){
                    $to = '+'.$user->dial_code."0".$user->phone_number;
                } else {
                    $to = '+'.$user->dial_code.$user->phone_number;
                }
                $provider = $prefer->sms_provider;
                $body = "Dear ".ucwords($user->name).", Please enter OTP ".$phoneCode." to verify your account.";
                $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
            }
            if(!empty($prefer->mail_driver) && !empty($prefer->mail_host) && !empty($prefer->mail_port) && !empty($prefer->mail_port) && !empty($prefer->mail_password) && !empty($prefer->mail_encryption)){
                $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
                $confirured = $this->setMailDetail($prefer->mail_driver, $prefer->mail_host, $prefer->mail_port, $prefer->mail_username, $prefer->mail_password, $prefer->mail_encryption);
                $client_name = $client->name;
                $mail_from = $prefer->mail_from;
                $sendto = $signReq->email;
                try {
                    $email_template_content = '';
                    $email_template = EmailTemplate::where('id', 2)->first();
                    if($email_template){
                        $email_template_content = $email_template->content;
                        $email_template_content = str_ireplace("{code}", $emailCode, $email_template_content);
                        $email_template_content = str_ireplace("{customer_name}", ucwords($user->name), $email_template_content);
                    }
                    $data = [
                        'code' => $emailCode,
                        'link' => "link",
                        'email' => $sendto,
                        'mail_from' => $mail_from,
                        'client_name' => $client_name,
                        'logo' => $client->logo['original'],
                        'subject' => $email_template->subject,
                        'customer_name' => ucwords($user->name),
                        'email_template_content' => $email_template_content,
                    ];
                    dispatch(new \App\Jobs\SendVerifyEmailJob($data))->onQueue('verify_email');
                    $notified = 1;
                } catch (\Exception $e) {
                    $user->save();
                }
                // try{
                //     Mail::send('email.verify',[
                //             'customer_name' => ucwords($signReq->name),
                //             'code_text' => 'Enter below code to verify yoour account',
                //             'code' => 'qweqwewqe',
                //             'logo' => $client->logo['original'],
                //             'link'=>"link"
                //     ],
                //     function ($message) use($sendto, $client_name, $mail_from) {
                //         $message->from($mail_from, $client_name);
                //         $message->to($sendto)->subject('OTP to verify account');
                //     });
                //     $response['send_email'] = 1;
                // }
                // catch(\Exception $e){
                //     return response()->json(['data' => $response]);
                // }
            }
            return response()->json(['data' => $response]);
        }else{
            $errors['errors']['user'] = 'Something went wrong. Please try again.';
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendToken(Request $request, $domain = '', $uid = 0){
        try {
            $user = User::where('id', Auth::user()->id)->first();
            if(!$user){
                return response()->json(['error' => __('User not found.')], 404);
            }
            if($user->is_email_verified == 1 && $user->is_phone_verified == 1){
                return response()->json(['message' => __('Account already verified.')], 200); 
            }
            $notified = 1;
            $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
            $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from','mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
            $newDateTime = Carbon::now()->addMinutes(10)->toDateTimeString();
            if ($request->type == "phone") {
                if($user->is_phone_verified == 0){
                    $otp = mt_rand(100000, 999999);
                    $user->phone_token = $otp;
                    $user->phone_token_valid_till = $newDateTime;
                    $user->save();
                    $provider = $data->sms_provider;
                    $to = '+'.$request->dial_code.$request->phone_number;
                    $body = "Dear ".ucwords($user->name).", Please enter OTP ".$otp." to verify your account.";
                    if(!empty($data->sms_key) && !empty($data->sms_secret) && !empty($data->sms_from)){
                        $send = $this->sendSms($provider, $data->sms_key, $data->sms_secret, $data->sms_from, $to, $body);
                        if($send){
                            $message = __('An otp has been sent to your phone. Please check.');
                            return $this->successResponse([], $message);
                        }
                    }else{
                        return $this->errorResponse(__('Provider service is not configured. Please contact administration.'), 404); 
                    }
                }
            }else{
                if($user->is_email_verified == 0){
                    $otp = mt_rand(100000, 999999);
                    $user->email_token = $otp;
                    $user->email_token_valid_till = $newDateTime;
                    $user->save();
                    if(!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)){
                        $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
                        $client_name = $client->name;
                        $mail_from = $data->mail_from;
                        $sendto = $user->email;
                        $email_template_content = '';
                        $email_template = EmailTemplate::where('id', 2)->first();
                        if($email_template){
                            $email_template_content = $email_template->content;
                            $email_template_content = str_ireplace("{code}", $otp, $email_template_content);
                            $email_template_content = str_ireplace("{customer_name}", ucwords($user->name), $email_template_content);
                        }
                        $data = [
                            'code' => $otp,
                            'link' => "link",
                            'mail_from' => $mail_from,
                            'email' => $request->email,
                            'client_name' =>  $client->name,
                            'logo' => $client->logo['original'],
                            'subject' => $email_template->subject,
                            'customer_name' => ucwords($user->name),
                            'email_template_content' => $email_template_content,
                        ];
                        dispatch(new \App\Jobs\SendVerifyEmailJob($data))->onQueue('verify_email');
                        $message = __('An otp has been sent to your email. Please check.');
                        return $this->successResponse([], $message);
                    }else{
                        return $this->errorResponse(__('Provider service is not configured. Please contact administration.'), 404); 
                    }
                } 
            }
        } catch (Exception $e) {
           return $this->errorResponse($e->getMessage(), $e->getCode()); 
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyToken(Request $request, $domain = ''){
        try {
            $user = User::where('id', Auth::user()->id)->first();
            if(!$user || !$request->has('type')){
                return $this->errorResponse(__('User not found.'), 404);
            }
            $currentTime = Carbon::now()->toDateTimeString();
            $message = 'Account verified successfully.';
            if($request->has('is_forget_password') && $request->is_forget_password == 1){
                $message = 'OTP matched successfully.';
            }
            if($request->type == 'phone'){
                $phone_number = str_ireplace(' ', '', $request->phone_number);
                $user_detail_exist = User::where('phone_number', $phone_number)->whereNotIn('id', [$user->id])->first();
                if($user_detail_exist){
                    return response()->json(['error' => __('phone number in use!')], 404);
                }
                if($user->phone_token != $request->otp){
                    return $this->errorResponse(__('OTP is not valid'), 404);
                }
                if($currentTime > $user->phone_token_valid_till){
                    return $this->errorResponse(__('OTP has been expired.'), 404);
                }
                $user->phone_token = NULL;
                $user->phone_number = $request->phone_number;
                $user->is_phone_verified = 1;
                $user->phone_token_valid_till = NULL;
                $user->save();
                return $this->successResponse(getUserDetailViaApi($user) , $message);
            }elseif ($request->type == 'email') {
                $user_detail_exist = User::where('email', $request->email)->where('id','!=',$user->id)->first();
                if($user_detail_exist){
                    return $this->errorResponse( __('Email already in use!'), 404);
                }
                if($user->email_token != $request->otp){
                    return $this->errorResponse(__('OTP is not valid'), 404);
                }
                if($currentTime > $user->email_token_valid_till){
                    return $this->errorResponse(__('OTP has been expired.'), 404);
                }
                $user->email_token = NULL;
                $user->is_email_verified = 1;
                $user->email_token_valid_till = NULL;
                $user->save();
                return $this->successResponse(getUserDetailViaApi($user), $message);
            }  
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode()); 
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request){
        $blockToken = new BlockedToken();
        $header = $request->header();
        $blockToken->token = $header['authorization'][0];
        $blockToken->expired = '1';
        $blockToken->save();

        $del_token = UserDevice::where('access_token',$header['authorization'][0])->delete();

        return response()->json([
            'message' => __('Successfully logged out')
        ]);
    }

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50'
        ]);
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $token = Str::random(60);
            $client_name = $client->name;
            $mail_from = $data->mail_from;
            DB::table('password_resets')->insert(['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]);
            $email_template_content = '';
            $email_template = EmailTemplate::where('id', 3)->first();
            if($email_template){
                $email_template_content = $email_template->content;
                $email_template_content = str_ireplace("{reset_link}", url('/reset-password/'.$token), $email_template_content);
            }
            $data = [
                'token' => $token,
                'mail_from' => $mail_from,
                'email' => $request->email,
                'client_name' => $client_name,
                'logo' => $client->logo['original'],
                'subject' => $email_template->subject,
                'email_template_content' => $email_template_content,
            ];
            dispatch(new \App\Jobs\sendForgotPasswordEmail($data))->onQueue('forgot_password_email');
        }
        return response()->json(['success' => __('We have e-mailed your password reset link!')], 200);
    
        
        // $user = User::where('email', $request->email)->first();
        // if(!$user){
        //     return response()->json(['error' => 'Invalid email'], 404);
        // }
        // $notified = 1;
        // $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        // $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        // $newDateTime = Carbon::now()->addMinutes(10)->toDateTimeString();
        // $otp = mt_rand(100000, 999999);
        // $user->email_token = $otp;
        // $user->email_token_valid_till = $newDateTime;
        // if(!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)){
        //     $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
        //     $client_name = $client->name;
        //     $mail_from = $data->mail_from;
        //     $sendto = $user->email;
        //     try{
        //         Mail::send('email.verify',[
        //                 'customer_name' => ucwords($user->name),
        //                 'code_text' => 'We have gotton a forget password request from your account. Please enter below otp of verify that it is you.',
        //                 'code' => $otp,
        //                 'logo' => $client->logo['original'],
        //                 'link'=>"link"
        //             ],
        //             function ($message) use($sendto, $client_name, $mail_from) {
        //             $message->from($mail_from, $client_name);
        //             $message->to($sendto)->subject('OTP to verify account');
        //         });
        //         $notified = 1;
        //     }
        //     catch(\Exception $e){
        //         $user->save();
        //     }
        // }
        // $user->save();
        // if($notified == 1){
        //     return response()->json(['success' => 'An otp has been sent to your email. Please check.'], 200);
        // } 
    }

    /**
     * reset password.
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request, $domain = ''){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'otp' => 'required|string|min:6|max:50',
            'new_password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|same:new_password',
        ]);
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json(['error' => __('User not found.')], 404);
        }
        if($user->email_token != $request->otp){
            return response()->json(['error' => __('OTP is not valid')], 404);
        }
        $currentTime = Carbon::now()->toDateTimeString();
        if($currentTime > $user->phone_token_valid_till){
            return response()->json(['error' => __('OTP has been expired.')], 404);
        }
        $user->password = Hash::make($request['new_password']);
        $user->save(); 
        return response()->json(['message' => __('Password updated successfully.')]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function sacialData(Request $request){
        return response()->json([
            'message' => __('Successfully logged out')
        ]);
    }

    /**     * proceed to user login on phone number     */
    public function proceedToPhoneLogin($req, $domain = ''){
        $user = User::where('phone_number', $req->phone_number)->where('dial_code', $req->dialCode)->where('status', 1)->first();
        if ($user) {
            Auth::login($user);
            $prefer = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format','time_format', 'map_key','sms_provider','verify_email','verify_phone', 'app_template_id', 'web_template_id')->first();
            $verified['is_email_verified'] = $user->is_email_verified;
            $verified['is_phone_verified'] = $user->is_phone_verified;
            $token1 = new Token;
            $token = $token1->make([
                'key' => 'royoorders-jwt',
                'issuer' => 'royoorders.com',
                'expiry' => strtotime('+1 month'),
                'issuedAt' => time(),
                'algorithm' => 'HS256',
            ])->get();
            $token1->setClaim('user_id', $user->id);
            try {
                Token::validate($token, 'secret');
            } catch (\Exception $e) {

            }
            $user_refferal = UserRefferal::where('user_id', $user->id)->first();

            if (!empty($req->fcm_token)) {
                $device = UserDevice::updateOrCreate(
                    ['device_token' => $req->fcm_token],
                    [
                        'user_id' => $user->id,
                        'device_type' => $req->device_type,
                        'access_token' => $token
                    ]
                );
            } else {
                $device = UserDevice::updateOrCreate(
                    ['device_token' => $req->device_token],
                    [
                        'user_id' => $user->id,
                        'device_type' => $req->device_type,
                        'access_token' => $token
                    ]
                );
            }            

            $user->auth_token = $token;
            $user->save();

            $user_cart = Cart::where('user_id', $user->id)->first();
            if($user_cart){
                $unique_identifier_cart = Cart::where('unique_identifier', $req->device_token)->first();
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
                Cart::where('unique_identifier', $req->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
            }
            $checkSystemUser = $this->checkCookies($user->id);
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['auth_token'] =  $token;
            $data['source'] = $user->image;
            $data['verify_details'] = $verified;
            $data['is_admin'] = $user->is_admin;
            $data['client_preference'] = $prefer;
            $data['dial_code'] = $user->dial_code;
            $data['phone_number'] = $user->phone_number;
            $data['cca2'] = $user->country ? $user->country->code : '';
            $data['callingCode'] = $user->country ? $user->country->phonecode : '';
            $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code: '';
            
            $message = __('Logged in successfully');
            // return response()->json(['data' => $data]);
            return $this->successResponse($data, $message);
        }
        else {
            return $this->errorResponse(__('Invalid phone number'), 404);
        }
    }

    /*** Login user via username ***/
    public function loginViaUsername(Request $request, $domain = ''){
        try{
            $errors = array();
            $validator = Validator::make($request->all(), [
                'username'  => 'required',
                'dialCode'  => 'required',
                'countryData'  => 'required|string',
                // 'dial_code'   => 'required|string',
                'device_type'   => 'required|string',
                'device_token'  => 'required|string',
                // 'country_code'  => 'required|string',
            ]);

            if($validator->fails()){
                foreach($validator->errors()->toArray() as $error_key => $error_value){
                    $errors['error'] = __($error_value[0]);
                    return response()->json($errors, 422);
                }
            }
            $phone_regex = '/^[0-9\-\(\)\/\+\s]*$/';
            $email_regex = '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
            $username = $request->username;

            if(preg_match($phone_regex, $username))
            {
                $phone_number = preg_replace('/\D+/', '', $username);
                $dialCode = $request->dialCode;
                $fullNumber = $request->full_number;
                $phoneCode = mt_rand(100000, 999999);
                $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
                $request->request->add(['is_phone'=>1, 'phone_number'=>$phone_number, 'phoneCode'=>$phoneCode, 'sendTime'=>$sendTime, 'codeSent'=>0]);
                
                $user = User::where('dial_code', $dialCode)->where('phone_number', $phone_number)->first();
                if(!$user){
                    $registerUser = $this->registerViaPhone($request)->getData();
                    if($registerUser->status == 'Success'){
                        $user = $registerUser->data;
                    }else{
                        return $this->errorResponse(__('Invalid data'), 404);
                    }
                }else{
                    $user->phone_token = $phoneCode;
                    $user->phone_token_valid_till = $sendTime;
                    $user->save();
                }

                $prefer = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 
                            'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();

                if($dialCode == "971"){
                    $to = '+'.$dialCode."0".$phone_number;
                } else {
                    $to = '+'.$dialCode.$phone_number;
                }
                $provider = $prefer->sms_provider;
                $body = "Please enter OTP ".$phoneCode." to verify your account.";
                if(!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)){
                    $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                    if($send){
                        $request->request->add(['codeSent' => 1]);
                        $message = __('An otp has been sent to your phone. Please check.');
                        $response = $request->all();
                        unset($response->_token);
                        return $this->successResponse($response, $message);
                    }else{
                        return $this->errorResponse(__('Something went wrong in sending OTP. We are sorry to for the inconvenience'), 404);
                    }
                }else{
                    return $this->errorResponse(__('Provider service is not configured. Please contact administration'), 404);
                }
            }
            elseif (preg_match($email_regex, $username))
            {
                $username = str_ireplace(' ', '', $username);

                $user = User::with('country')->where('email', $username)->first();
                if(!$user){
                    $errors['error'] = __('Invalid email');
                    return response()->json($errors, 422);
                }
                if(!Auth::attempt(['email' => $username, 'password' => $request->password])){
                    $errors['error'] = __('Invalid password');
                    return response()->json($errors, 422);
                }
                $user = Auth::user();
                $prefer = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format','time_format', 'map_key','sms_provider','verify_email','verify_phone', 'app_template_id', 'web_template_id')->first();
                $verified['is_email_verified'] = $user->is_email_verified;
                $verified['is_phone_verified'] = $user->is_phone_verified;
                $token1 = new Token;
                $token = $token1->make([
                    'key' => 'royoorders-jwt',
                    'issuer' => 'royoorders.com',
                    'expiry' => strtotime('+1 month'),
                    'issuedAt' => time(),
                    'algorithm' => 'HS256',
                ])->get();
                $token1->setClaim('user_id', $user->id);
                try {
                    Token::validate($token, 'secret');
                } catch (\Exception $e) {

                }
                $user_refferal = UserRefferal::where('user_id', $user->id)->first();
                if (!empty($request->fcm_token)) {
                    $device = UserDevice::updateOrCreate(
                        ['device_token' => $request->fcm_token],
                        [
                            'user_id' => $user->id,
                            'device_type' => $request->device_type,
                            'access_token' => $token
                        ]
                    );
                } else {
                    $device = UserDevice::updateOrCreate(
                        ['device_token' => $request->device_token],
                        [
                            'user_id' => $user->id,
                            'device_type' => $request->device_type,
                            'access_token' => $token
                        ]
                    );
                }
                
                $user->auth_token = $token;
                $user->save();

                $user_cart = Cart::where('user_id', $user->id)->first();
                if($user_cart){
                    $unique_identifier_cart = Cart::where('unique_identifier', $request->device_token)->first();
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
                    Cart::where('unique_identifier', $request->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
                }
                $checkSystemUser = $this->checkCookies($user->id);
                $data['name'] = $user->name;
                $data['email'] = $user->email;
                $data['auth_token'] =  $token;
                $data['source'] = $user->image;
                $data['verify_details'] = $verified;
                $data['is_admin'] = $user->is_admin;
                $data['client_preference'] = $prefer;
                $data['dial_code'] = $user->dial_code;
                $data['phone_number'] = $user->phone_number;
                $data['cca2'] = $user->country ? $user->country->code : '';
                $data['callingCode'] = $user->country ? $user->country->phonecode : '';
                $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code: '';
                return response()->json(['data' => $data]);


                if (Auth::attempt(['email' => $username, 'password' => $request->password, 'status' => 1])) {
                    $userid = Auth::id();
                    if($request->has('access_token')){
                        if($request->access_token){
                            $user_device = UserDevice::where('user_id', $userid)->where('device_token', $request->access_token)->first();
                            if(!$user_device){
                                $user_device = new UserDevice();
                                $user_device->user_id = $userid;
                                $user_device->device_type = 'web';
                                $user_device->device_token = $request->access_token;
                                $user_device->save();
                            }
                        }
                    }
                    $this->checkCookies($userid);
                    $user_cart = Cart::where('user_id', $userid)->first();
                    if ($user_cart) {
                        $unique_identifier_cart = Cart::where('unique_identifier', session()->get('_token'))->first();
                        if ($unique_identifier_cart) {
                            $unique_identifier_cart_products = CartProduct::where('cart_id', $unique_identifier_cart->id)->get();
                            foreach ($unique_identifier_cart_products as $unique_identifier_cart_product) {
                                $user_cart_product_detail = CartProduct::where('cart_id', $user_cart->id)->where('product_id', $unique_identifier_cart_product->product_id)->first();
                                if ($user_cart_product_detail) {
                                    $user_cart_product_detail->quantity = ($unique_identifier_cart_product->quantity + $user_cart_product_detail->quantity);
                                    $user_cart_product_detail->save();
                                    $unique_identifier_cart_product->delete();
                                } else {
                                    $unique_identifier_cart_product->cart_id = $user_cart->id;
                                    $unique_identifier_cart_product->save();
                                }
                            }
                            $unique_identifier_cart->delete();
                        }
                    } else { 
                        Cart::where('unique_identifier', session()->get('_token'))->update(['user_id' => $userid, 'created_by' => $userid, 'unique_identifier' => '']);
                    }
                    $message = 'Logged in successfully';
                    $redirect_to = '';
                    if(session()->has('url.intended')){
                        $redirect_to = session()->get('url.intended');
                        session()->forget('url.intended');
                    }else{
                        $redirect_to = route('user.verify');
                    }
                    $request->request->add(['is_email'=>1, 'redirect_to'=>$redirect_to]);
                    $response = $request->all();
                    return $this->successResponse($response, $message);
                }
                $checkEmail = User::where('email', $username)->first();
                if ($checkEmail) {
                    return $this->errorResponse(__('Incorrect password'), 404);
                }
                return $this->errorResponse(__('You are not registered with us. Please sign up.'), 404);
            }
            else {
                return $this->errorResponse(__('Invalid email or phone number'), 404);
            }
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode()); 
        }
    }

    /**
     * Verify Login user via Phone number and create token
     *
     */
    public function verifyPhoneLoginOtp(Request $request, $domain = ''){
        try {
            $username = $request->username;
            $dialCode = $request->dialCode;
            $phone_number = preg_replace('/\D+/', '', $username);
            $user = User::where('dial_code', $dialCode)->where('phone_number', $phone_number)->first();
            if(!$user){
                $errors['error'] = __('Your phone number is not registered');
                return response()->json($errors, 422);
            }
            $currentTime = Carbon::now()->toDateTimeString();
            $message = 'Account verified successfully.';
            
            if($user->phone_token != $request->verifyToken){
                return $this->errorResponse(__('OTP is not valid'), 404);
            }
            if($currentTime > $user->phone_token_valid_till){
                return $this->errorResponse(__('OTP has been expired.'), 404);
            }
            $request->request->add(['phone_number'=>$phone_number]);
            return $this->proceedToPhoneLogin($request);
        } 
        catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode()); 
        }
    }

    /*** register user via phone number ***/
    public function registerViaPhone($req, $domain = ''){
        try {
            $user = new User();
            $country = Country::where('code', strtoupper($req->countryData))->first();
            // $emailCode = mt_rand(100000, 999999);
            $user->type = 1;
            $user->status = 1;
            $user->role_id = 1;
            $user->name = 'RO'.substr($req->phone_number, -6);
            $user->email = $req->phone_number; //$req->email;
            $user->is_email_verified = 0;
            $user->is_phone_verified = 0;
            $user->country_id = $country->id;
            $user->phone_token = $req->phoneCode;
            $user->dial_code = $req->dialCode;
            // $user->email_token = $emailCode;
            $user->phone_number = $req->phone_number;
            $user->phone_token_valid_till = $req->sendTime;
            // $user->email_token_valid_till = $sendTime;
            // $user->password = Hash::make($req->password);
            $user->save();

            $wallet = $user->wallet;
            $userRefferal = new UserRefferal();
            $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
            if($req->refferal_code != null){
                $userRefferal->reffered_by = $req->refferal_code;
            }
            $userRefferal->user_id = $user->id;
            $userRefferal->save();
            $user_cart = Cart::where('user_id', $user->id)->first();
            if($user_cart){
                $unique_identifier_cart = Cart::where('unique_identifier', $req->device_token)->first();
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
                Cart::where('unique_identifier', $req->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
            }            
            if($user->id > 0){
                if($signReq->refferal_code){
                    $refferal_amounts = ClientPreference::first();
                    if($refferal_amounts){
                        if($refferal_amounts->reffered_by_amount != null && $refferal_amounts->reffered_to_amount != null){
                            $reffered_by = UserRefferal::where('refferal_code', $signReq->refferal_code)->first();
                            $user_refferd_by = $reffered_by->user_id;
                            $user_refferd_by = User::where('id', $reffered_by->user_id)->first();
                            if($user_refferd_by){
                                //user reffered by amount
                                $wallet_user_reffered_by = $user_refferd_by->wallet;
                                $wallet_user_reffered_by->deposit($refferal_amounts->reffered_by_amount, ['Referral code used by <b>' . $signReq->name . '</b>']);
                                $wallet_user_reffered_by->balance;
                                //user reffered to amount
                                $wallet->deposit($refferal_amounts->reffered_to_amount, ['You used referal code of <b>' . $user_refferd_by->name . '</b>']);
                                $wallet->balance;
                            }
                        }
                    }
                }
               
                return $this->successResponse($user , 'Successfully registered');
            }else{
                return $this->errorResponse('Something went wrong. Please try again.', 422);
            }
        } 
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
