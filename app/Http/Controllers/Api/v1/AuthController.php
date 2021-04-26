<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\{LoginRequest, SignupRequest};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Client, ClientPreference, BlockedToken, Otp, Country, UserDevice, UserVerification, ClientLanguage};
use Validation;
use DB;
use JWT\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Password;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Mail;

class AuthController extends BaseController
{
    /**
     * Get Country List
     * * @return country array
     */
    public function countries(Request $request)
    {
        $country = Country::select('id', 'code', 'name', 'nicename', 'phonecode')->get();
        return response()->json([
            'data' => $country
        ]);
    }

    /**
     * Login user and create token
     *
     */
    public function login(LoginRequest $loginReq)
    {
        $errors = array();
        $user = User::where('email', $loginReq->email)->first();

        if(!$user){
            $errors['error'] = 'Invalid email';
            return response()->json($errors, 422);
        }

        if(!Auth::attempt(['email' => $loginReq->email, 'password' => $loginReq->password])){
            $errors['error'] = 'Invalid password';
            return response()->json($errors, 422);
        }

        $user = Auth::user();

        $prefer = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format','time_format', 'map_key','sms_provider','verify_email','verify_phone', 'app_template_id', 'web_template_id')->first();

        if(($prefer->verify_email == 1) || ($prefer->verify_phone == 1)){
            /*if(!$verified || ($verified->is_verified  != 1)){
                $errors['errors']['email'] = 'Email or password not verified';
                return response()->json($errors, 422);
            }*/
        }
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

        $device = UserDevice::where('user_id', $user->id)->first();
        if(!$device){
            $device = new UserDevice();
            $device->user_id = $user->id;
        }
        $device->device_type = $loginReq->device_type;
        $device->device_token = $loginReq->device_token;
        $device->save();
        
        $user->auth_token = $token;
        $user->save();

        $data['auth_token'] =  $token;
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['phone_number'] = $user->phone_number;
        $data['client_preference'] = $prefer;
        $data['verify_details'] = $verified;

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * User registraiotn
     * @return [status, email, need_email_verify, need_phone_verify]
     */
    public function signup(Request $signReq)
    {
        $validator = Validator::make($signReq->all(), [
            'name'          => 'required|string|min:3|max:50',
            'email'         => 'required|email|max:50||unique:users',
            'password'      => 'required|string|min:6|max:50',
            'phone_number'  => 'required|string|min:10|max:15|unique:users',
            'device_type'   => 'required|string',
            'device_token'  => 'required|string'
        ]);
 
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = $error_value[0];
                return response()->json($errors, 422);
            }
        }
        $user = new User();

        foreach ($signReq->only('name', 'email', 'phone_number', 'country_id') as $key => $value) {
            $user->{$key} = $value;
        }

        $phoneCode = mt_rand(100000, 999999);
        $emailCode = mt_rand(100000, 999999);
        $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();

        $user->password = Hash::make($signReq->password);
        $user->type = 1;
        $user->is_email_verified = 0;
        $user->is_phone_verified = 0;
        $user->role_id = 1;
        $user->status = 1;
        $user->phone_token = $phoneCode;
        $user->email_token = $emailCode;
        $user->phone_token_valid_till = $sendTime;
        $user->email_token_valid_till = $sendTime;
        $user->save();

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
            $response['status'] = 'Success';
            $response['auth_token'] =  $token;
            $response['name'] = $user->name;
            $response['email'] = $user->email;
            $response['phone_number'] = $user->phone_number;
            $verified['is_email_verified'] = 0;
            $verified['is_phone_verified'] = 0;

            $prefer = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 
                        'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
            $preferData['theme_admin'] = $prefer->theme_admin;
            $preferData['distance_unit'] = $prefer->distance_unit;
            $preferData['map_provider'] = $prefer->map_provider;
            $preferData['date_format'] = $prefer->date_format;
            $preferData['time_format'] = $prefer->time_format;
            $preferData['map_key'] = $prefer->map_key;
            $preferData['sms_provider'] = $prefer->sms_provider;
            $preferData['verify_email'] = $prefer->verify_email;
            $preferData['verify_phone'] = $prefer->verify_phone;
            $preferData['app_template_id'] = $prefer->app_template_id;
            $preferData['web_template_id'] = $prefer->web_template_id;

            $response['client_preference'] = $preferData;
            $response['verify_details'] = $verified;

            $user_device[] = [
                'user_id' => $user->id,
                'device_type' => $signReq->device_type,
                'device_token' => $signReq->device_token,
                'access_token' => ''
            ];
            UserDevice::insert($user_device);

            if(!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)){

                $provider = $prefer->sms_provider;
                $to = $user->phone_number;
                $body = "Dear ".ucwords($user->name).", Please enter OTP ".$phoneCode." to verify your account.";
                $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                $response['send_otp'] = 1;
            }

            if(!empty($prefer->mail_driver) && !empty($prefer->mail_host) && !empty($prefer->mail_port) && !empty($prefer->mail_port) && !empty($prefer->mail_password) && !empty($prefer->mail_encryption)){

                $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();

                $confirured = $this->setMailDetail($prefer->mail_driver, $prefer->mail_host, $prefer->mail_port, $prefer->mail_username, $prefer->mail_password, $prefer->mail_encryption);

                $client_name = $client->name;
                $mail_from = $prefer->mail_from;
                $sendto = $signReq->email;
                try{
                    Mail::send('email.verify',[
                            'customer_name' => ucwords($signReq->name),
                            'code_text' => 'Enter below code to verify yoour account',
                            'code' => 'qweqwewqe',
                            'logo' => $client->logo['original'],
                            'link'=>"link"
                        ],
                        function ($message) use($sendto, $client_name, $mail_from) {
                        $message->from($mail_from, $client_name);
                        $message->to($sendto)->subject('OTP to verify account');
                    });
                    $response['send_email'] = 1;
                }
                catch(\Exception $e){
                    return response()->json(['data' => $response]);
                }
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
    public function sendToken(Request $request, $domain = '', $uid = 0)
    {
        $user = User::where('id', Auth::user()->id)->first();
        if(!$user){
            return response()->json(['error' => 'User not found.'], 404);
        }

        if($user->is_email_verified == 1 && $user->is_phone_verified == 1){
            return response()->json(['message' => 'Account already verified.'], 200); 
        }
        $notified = 1;

        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from','mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();

        $newDateTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
        if($user->is_phone_verified == 0){

            $otp = mt_rand(100000, 999999);
            $user->phone_token = $otp;
            $user->phone_token_valid_till = $newDateTime;
            $provider = $data->sms_provider;
            $to = $user->phone_number;
            $body = "Dear ".ucwords($user->name).", Please enter OTP ".$otp." to verify your account.";

            if(!empty($data->sms_key) && !empty($data->sms_secret) && !empty($data->sms_from)){
                $send = $this->sendSms($provider, $data->sms_key, $data->sms_secret, $data->sms_from, $to, $body);
                if($send){
                    $notified = 1;
                }
            }
        }

        if($user->is_email_verified == 0){

            $otp = mt_rand(100000, 999999);
            $user->email_token = $otp;
            $user->email_token_valid_till = $newDateTime;
            if(!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)){

                $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);

                $client_name = $client->name;
                $mail_from = $data->mail_from;
                $sendto = $user->email;
                try{
                    Mail::send('email.verify',[
                            'customer_name' => ucwords($user->name),
                            'code_text' => 'Enter below code to verify yoour account',
                            'code' => $otp,
                            'logo' => $client->logo['original'],
                            'link'=>"link"
                        ],
                        function ($message) use($sendto, $client_name, $mail_from) {
                        $message->from($mail_from, $client_name);
                        $message->to($sendto)->subject('OTP to verify account');
                    });
                    $notified = 1;
                }
                catch(\Exception $e){
                    $user->save();
                    //return response()->json(['errors' => $e->getMessage()], 404);
                    //return response()->json(['errors' => 'Mail server is not configured. Please contact admin.'], 404);
                }
            }
        }
        $user->save();
        if($notified == 1){
            return response()->json(['success' => 'An otp has been sent to your email. Please check.'], 200); 
        }else{
            return response()->json(['success' => 'Provider service is not configured. Please contact administration.'], 404); 
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyToken(Request $request, $domain = '')
    {
        $user = User::where('id', Auth::user()->id)->first();
        if(!$user || !$request->has('type')){
            return response()->json(['error' => 'User not found.'], 404);
        }
        $currentTime = \Carbon\Carbon::now()->toDateTimeString();

        $message = 'Account verified successfully.';
        if($request->has('is_forget_password') && $request->is_forget_password == 1){
            $message = 'OTP matched successfully.';
        }

        if($request->type == 'phone'){

            if($user->phone_token != $request->otp){
                return response()->json(['error' => 'OTP is not valid'], 404);
            }

            if($currentTime > $user->phone_token_valid_till){
                return response()->json(['error' => 'OTP has been expired.'], 404);
            }
            $user->phone_token = NULL;
            $user->phone_token_valid_till = NULL;
            $user->is_phone_verified = 1;
        }

        if($request->type == 'email'){

            if($user->email_token != $request->otp){
                return response()->json(['error' => 'OTP is not valid'], 404);
            }

            if($currentTime > $user->email_token_valid_till){
                return response()->json(['error' => 'OTP has been expired.'], 404);
            }
            $user->email_token = NULL;
            $user->email_token_valid_till = NULL;
            $user->is_email_verified = 1;
        }
        $user->save();
        return response()->json([
            'message' => $message,
            'data' => array('Success' => True)
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $blockToken = new BlockedToken();
        $header = $request->header();
        $blockToken->token = $header['authorization'][0];
        $blockToken->expired = '1';
        $blockToken->save();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get Country List
     */
    public function validateEmail(Request $request)
    {
        // if(!Auth::attempt(['email' => request('email')])){ 
        //     return response()->json(['email' => 'Invalid email'], 422);
        // }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50'
        ]);
 
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = $error_value[0];
                return response()->json($errors, 422);
            }
        }
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json(['error' => 'Invalid email'], 404);
        }
        $notified = 1;

        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
       
        $newDateTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
        $otp = mt_rand(100000, 999999);
        $user->email_token = $otp;
        $user->email_token_valid_till = $newDateTime;
        if(!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)){

            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);

            $client_name = $client->name;
            $mail_from = $data->mail_from;
            $sendto = $user->email;
            try{
                Mail::send('email.verify',[
                        'customer_name' => ucwords($user->name),
                        'code_text' => 'We have gotton a forget password request from your account. Please enter below otp of verify that it is you.',
                        'code' => $otp,
                        'logo' => $client->logo['original'],
                        'link'=>"link"
                    ],
                    function ($message) use($sendto, $client_name, $mail_from) {
                    $message->from($mail_from, $client_name);
                    $message->to($sendto)->subject('OTP to verify account');
                });
                $notified = 1;
            }
            catch(\Exception $e){
                $user->save();
                //return response()->json(['errors' => $e->getMessage()], 404);
                //return response()->json(['errors' => 'Mail server is not configured. Please contact admin.'], 404);
            }
        }

        $user->save();
        if($notified == 1){
            return response()->json(['success' => 'An otp has been sent to your email. Please check.'], 200);
        } 
    }

    /**
     * reset password.
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request, $domain = '')
    {
        $validator = Validator::make($signReq->all(), [
            'email' => 'required|string',
            'otp' => 'required|string|min:6|max:50',
            'new_password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|same:new_password',
        ]);
 
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = $error_value[0];
                return response()->json($errors, 422);
            }
        }
        $user = User::where('email', $request->email)->first();
        if(!$user)
        {
            return response()->json(['error' => 'User not found.'], 404);
        }
        if($user->email_token != $request->otp){
            return response()->json(['error' => 'OTP is not valid'], 404);
        }
        $currentTime = \Carbon\Carbon::now()->toDateTimeString();
        if($currentTime > $user->phone_token_valid_till){
            return response()->json(['error' => 'OTP has been expired.'], 404);
        }
        
        $user->password = Hash::make($request['new_password']);
        $user->save(); 
        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function sacialData(Request $request)
    {
        

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
