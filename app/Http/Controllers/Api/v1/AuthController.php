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
     * Login user and create token
     *
     * @param  [string] phone_number
     * @param  [string] OTP
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    /*public function sendOtp(Request $request)
    {
    	//echo "Connected ".DB::connection()->getDatabaseName();
        $request->validate([
            'phone_number' => 'required|numeric',
        ]);
        
        $agent = Agent::where('phone_number', $request->phone_number)->first();

        if (!$agent) {
	        return response()->json([
	            'message' => 'User not found'], 404);
	    }
        $otp = new Otp();
        $otp->phone = $data['phone_number'] = $agent->phone_number;
        $otp->opt = $data['otp'] = rand(111111,999999);
        $otp->valid_till = $data['valid_till'] = Date('Y-m-d H:i:s', strtotime("+10 minutes"));

        $otp->save();

        //parent::sendSms($request->phone_number, 'Your OTP for login into Royo App is ' . $data['otp']);

        return response()->json([
            'data' => $data,
        ]);

    }*/

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
     * @param  [string] phone_number
     * @param  [string] OTP
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
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

        $verified = UserVerification::select('user_id', 'is_email_verified', 'is_phone_verified')
                    ->where('user_id', $user->id)->first();

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
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|max:50||unique:users',
            'password' => 'required|string|min:6|max:50',
            'phone_number' => 'required|string|min:10|max:15|unique:users',
            'device_type' => 'required|string',
            'device_token' => 'required|string'
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
        $user->password = Hash::make($signReq->password);
        //$user->encpass = $signReq->password;
        $user->type = 1;
        $user->role_id = 1;
        $user->status = 1;
        $user->save();
        

        if($user->id > 0){

            $user_device[] = [
                'user_id' => $user->id,
                'device_type' => $signReq->device_type,
                'device_token' => $signReq->device_token,
                'access_token' => ''
            ];
            UserDevice::insert($user_device);

            $user_verify[] = [
                'user_id' => $user->id,
                'is_email_verified' => 0,
                'is_phone_verified' => 0
            ];
            UserVerification::insert($user_verify);

            /*data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 
                        'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from')
                        ->where('id', '>', 0)->first();*/





            $prefer = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
            //$response['need_email_verify'] = $prefer->verify_email;
            //$response['need_phone_verify'] = $prefer->verify_phone;

            $token1 = new Token;

            $token = $token1->make([
                'key' => 'royoorders'.$user->id,
                'issuer' => 'royoorders.com',
                'expiry' => strtotime('+1 month'),
                'issuedAt' => time(),
                'algorithm' => 'HS256',
            ])->get();
            $token1->setClaim('user_id', $user->id);

            $user->auth_token = $token;
            $user->save();

            $verified = UserVerification::select('user_id', 'is_email_verified', 'is_phone_verified')
                    ->where('user_id', $user->id)->first();

            $response['status'] = 'Success';
            $response['auth_token'] =  $token;
            $response['name'] = $user->name;
            $response['email'] = $user->email;
            $response['phone_number'] = $user->phone_number;
            $response['client_preference'] = $prefer;
            $response['verify_details'] = $verified;

            return response()->json([
                'data' => $response
            ]);
        }else{
            $errors['errors']['user'] = 'Something went wrong. Please try again.';
        }
    }

    /**
     * Get Country List
     * * @return country array
     */
    public function sendVerificationOtp(Request $request)
    {
        $errors = array();
        if(!$request->has('email') && !$request->has('phone_number')){
            $errors['email'] = 'Please enter email';
            $errors['phone_number'] = 'Please enter phone number';
            return response()->json(['errors' => $errors], 422);
        }

        $user = User::where('id' > 0);
        if($request->has('email')){
            $user = $user->where('email', $request->email);
        }
        if($request->has('phone_number')){
            $user = $user->where('phone_number', $request->phone_number);
        }
        $user = $user->first();
        if(!$user){
            $errors['user'] = 'User not found';
            return response()->json(['errors' => $errors], 404);
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
            return response()->json(['errors' => 'User not found.'], 404);
        }

        if($request->has('type')){
            $client = Client::select('id', 'name', 'email', 'phone_number')->where('id', '>', 0)->first();

            $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 
                        'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from')
                        ->where('id', '>', 0)->first();

            $verify = UserVerification::where('user_id', $user->id)->first();
            if(!$verify){
                $verify = new UserVerification();
                $verify->user_id = $user->id;
                $verify->is_email_verified = 0;
                $verify->is_phone_verified = 0; 
            }
            $newDateTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
            if($request->type == 'phone'){

                $phoneCode = mt_rand(100000, 999999);
                $verify->phone_token = $phoneCode;
                $verify->phone_token_valid_till = $newDateTime;
                $to = $user->phone_number;
                $body = "Dear ".ucwords($user->name).",</br> Please enter OTP ".$phoneCode." to verify your account.";

                $provider = $data->sms_provider;
                if(empty($data->sms_key) || empty($data->sms_secret) || empty($data->sms_from)){
                    $send = $this->sendSms($provider, $data->sms_key, $data->sms_secret, $data->sms_from, $to, $body);

                    if($send){
                        return response()->json(['success' => 'An otp has been sent to your phone. Please enter to verify your account.'], 404);
                    }
                }
                return response()->json(['error' => 'SMS provider is not configured. Please contact administration.'], 404);
            }

            if($request->type == 'email'){

                $mailCode = mt_rand(100000, 999999);
                $verify->email_token = $mailCode;
                $verify->email_token_valid_till = $newDateTime;

                if(empty($data->mail_driver) || empty($data->mail_host) || empty($data->mail_port) || empty($data->mail_port) || empty($data->mail_password) || empty($data->mail_encryption)){
                    return response()->json(['error' => 'Mail server is not configured. Please contact administration.'], 404);
                }

                $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);

                if($confirured == 2){
                    return response()->json(['error' => 'Mail server is not configured. Please contact administration.'], 404);
                }

                $client_name = $client->name;
                $mail_from = $client->email;
                $sendto = $user->email;

                try{
                    Mail::send('email.verify',[
                                'customer_name' => ucwords($user->name),
                                'code_text' => 'Enter below code to verify yoour account',
                                'code' => $mailCode,
                                'logo' => 'Enter below code to verify yoour account',
                                'link'=>"link"
                            ],
                            function ($message) use($sendto, $client_name, $mail_from) {
                            $message->from($mail_from, $client_name);
                            $message->to($sendto)->subject('OTP to verify account');
                    });
                }
                catch(\Exception $e){
                    return response()->json(['errors' => 'Unable to send email. Please check email or try later.'], 404);
                }
                return response()->json(['success' => 'An otp has been sent to your email. Please check.'], 404); 
            }
            $verify->save();
        }
             
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function sendVerificationMail($code)
    {
        $client = Client::select('id', 'name', 'email', 'phone_number')->where('id', '>', 0)->first();

        //$user->notify(new VerifyEmail());
        $link = $mailCode;
        $this->setMailDetail($client);

        \Mail::send('email.verify', 
            ['customer_name' => 
            $order_details->customer->name,
            'content' => $sms_body,
            'agent_name' => $order_details->agent->name,
            'agent_profile' =>$agent_profile,
            'number_plate' =>$order_details->agent->plate_number,
            'client_logo'=>$client_logo,
            'link'=>$link], function ($message) use($sendto,$client_details,$mail) {
                $message->from($mail->from_address,$client_details->name);
                $message->to($sendto)->subject('Order Update | '.$client_details->company_name);
         });
        return '1';
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
        if(!Auth::attempt(['email' => request('email')])){ 
            return response()->json(['email' => 'Invalid email'], 422);
        }
    }

    public function forgotPassword(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        }

        $set_token = substr(md5(microtime()), 0, 30);

        /*\DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $set_token,
            'created_at' => Carbon::now()
        ]);*/
        $user = User::where('email', $request->email)->first();
        if(!$user){
            $errors['errors']['email'] = 'Invalid email';
            return response()->json($errors, 422);
        }
        $arr['email'] = $request->email;
        try {

            $send = $user->sendPasswordResetNotification($set_token);
            
            return response()->json([
                'message' => 'Password reset link has been sent to your registered email',
                'data' => $arr
            ]);

            //$notify = Mail::to($request->only('email'))->send(new PasswordReset($request->email, $set_token));
            //$notify = $user->notify(new PasswordReset($request->only('email')));

            //Mail::mailer('postmark')->to($request->only('email'))->send(new PasswordReset($request->only('email')));
            /*$response = Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Reset passowrd email.');*/
            //});
           /* switch ($response) {
                case Password::RESET_LINK_SENT:
                    return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                case Password::INVALID_USER:
                    return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
            }*/
        } catch (\Swift_TransportException $ex) {
            $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
        } catch (Exception $ex) {
            $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
        }

        return response()->json([
            'data' => $arr
        ]);
    }


    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);
    }
}
