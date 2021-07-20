<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Client, Category, Brand, Product, ClientLanguage, User, ClientCurrency, ClientPreference, Country, UserAddress, UserVerification,};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
//use Illuminate\Support\Facades\Redis;
use Auth;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\Storage;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Mail;


class UserController extends FrontController
{
    private $field_status = 2;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyAccount(Request $request, $domain = ''){
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $user = User::where('id', Auth::user()->id)->first();
        $preference = ClientPreference::select('verify_email', 'verify_phone')->where('id', '>', 0)->first();
        if ($preference->verify_email == 0 && $preference->verify_phone == 0) {
            return redirect()->route('userHome');
        } elseif (Auth::user()->is_email_verified == 1 && Auth::user()->is_phone_verified == 1) {
            return redirect()->route('userHome');
        } elseif ($preference->verify_email == 1 && $preference->verify_phone == 0) {
            if (Auth::user()->is_email_verified == 1) {
                return redirect()->route('userHome');
            }
        } elseif ($preference->verify_email == 0 && $preference->verify_phone == 1) {
            if (Auth::user()->is_phone_verified == 1) {
                return redirect()->route('userHome');
            }
        }
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/verifyaccountnew')->with(['preference' => $preference, 'navCategories' => $navCategories, 'user' => $user]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendToken(Request $request, $domain = '', $uid = 0){
        $notified = 0;
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user) {
            return redirect()->back()->with('err_user', 'User not found.');
        }
        if ($user->is_email_verified == 1 && $user->is_phone_verified == 1) {
            return redirect()->back()->with('err_user', 'Account already verified.');
        }
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        $newDateTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
        if ($request->type == "phone") {
            
            $message = "An otp has been sent to your phone. Please check";
            if ($user->is_phone_verified == 0) {
                $otp = mt_rand(100000, 999999);
                $user->phone_token = $otp;
                $user->phone_token_valid_till = $newDateTime;
                $provider = $data->sms_provider;
                $to = '+'.$request->dial_code.$request->phone;
                $body = "Dear " . ucwords($user->name) . ", Please enter OTP " . $otp . " to verify your account.";
                if (!empty($data->sms_key) && !empty($data->sms_secret) && !empty($data->sms_from)) {
                    $send = $this->sendSms($provider, $data->sms_key, $data->sms_secret, $data->sms_from, $to, $body);
                    if ($send) {
                        $notified = 1;
                    }
                }
            }
        }else{
            if ($user->is_email_verified == 0) {
                $message = "An otp has been sent to your email. Please check";
                $otp = mt_rand(100000, 999999);
                $user->email_token = $otp;
                $user->email_token_valid_till = $newDateTime;
                if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
                    $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
                    $client_name = $client->name;
                    $mail_from = $data->mail_from;
                    $sendto = $user->email;
                    try {
                        $data = [
                            'code' => $otp,
                            'link' => "link",
                            'email' => $sendto,
                            'mail_from' => $mail_from,
                            'client_name' => $client_name,
                            'logo' => $client->logo['original'],
                            'customer_name' => ucwords($user->name),
                            'code_text' => 'Enter below code to verify yoour account',
                        ];
                        dispatch(new \App\Jobs\SendVerifyEmailJob($data))->onQueue('verify_email');
                        $notified = 1;
                    } catch (\Exception $e) {
                        $user->save();
                    }
                }
            }
        }
        $user->save();
        if ($notified == 1) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
            ]);
        } else {
            return redirect()->back()->with('err_user', 'Provider service is not configured. Please contact administration.');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyToken(Request $request, $domain = ''){
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user || !$request->has('type')) {
            return response()->json(['error' => 'User not found!'], 404);
        }
        if(!$request->verifyToken){
            return response()->json(['error' => 'OTP required!'], 404);
        }
        $currentTime = \Carbon\Carbon::now()->toDateTimeString();
        $message = 'Account verified successfully.';
        if ($request->has('is_forget_password') && $request->is_forget_password == 1) {
            $message = 'OTP matched successfully.';
        }
        if ($request->type == 'phone') {
            if ($user->phone_token != $request->verifyToken) {
                return response()->json(['error' => 'OTP is not valid'], 404);
            }
            if ($currentTime > $user->phone_token_valid_till) {
                return response()->json(['error' => 'OTP has been expired.'], 404);
            }
            $user->phone_token = NULL;
            $user->phone_token_valid_till = NULL;
            $user->is_phone_verified = 1;
        }
        if ($request->type == 'email') {
            if ($user->email_token != $request->verifyToken) {
                die();
                return response()->json(['error' => 'OTP is not valid'], 404);
            }
            if ($currentTime > $user->email_token_valid_till) {
                return response()->json(['error' => 'OTP has been expired.'], 404);
            }
            $user->email_token = NULL;
            $user->email_token_valid_till = NULL;
            $user->is_email_verified = 1;
        }
        $user->save();
        return response()->json(['success' => 'OTP verified'], 202);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserLogin($domain = '')
    {
        if (Auth::user()) {
            return response()->json("yes");
        } else {
            return response()->json("no");
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function checkout($domain = ''){
        $countries = Country::get();
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        return view('frontend.checkout')->with(['navCategories' => $navCategories, 'addresses' => $addresses, 'countries' => $countries]);
    }

    /**
     * get Current User Address
     */
    public function getUserAddress($domain = ''){
        $country = [];
        $address = UserAddress::where('user_id', Auth::user()->id)->where('is_primary', '1')->first();
        if($address){
            $country = Country::where('id' , $address->country_id)->first();
        }
        return response()->json(['address' => $address, 'country'=>$country]);
    }
}
