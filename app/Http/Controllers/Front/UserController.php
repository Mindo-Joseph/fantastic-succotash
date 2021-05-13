<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Client, Category, Brand, Product, ClientLanguage, User, ClientCurrency, ClientPreference, Country, UserAddress, UserVerification};
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
    public function verifyAccount(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $preference = ClientPreference::select('verify_email', 'verify_phone')->where('id', '>', 0)->first();

        //$verify = User::select('is_email_verified', 'is_phone_verified')
        //->where('user_id', Auth::user()->id)->first();

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

        /**     * Display resetPassword Form     */
        return view('forntend/account/verifyAccount')->with(['preference' => $preference, 'navCategories' => $navCategories]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendToken(Request $request, $domain = '', $uid = 0)
    {
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user) {
            return redirect()->back()->with('err_user', 'User not found.');
        }

        if ($user->is_email_verified == 1 && $user->is_phone_verified == 1) {
            return redirect()->back()->with('err_user', 'Account already verified.');
        }

        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        $notified = 0;
        $newDateTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
        if ($request->type == "phone") {
            if ($user->is_phone_verified == 0) {

                $otp = mt_rand(100000, 999999);
                $user->phone_token = $otp;
                $user->phone_token_valid_till = $newDateTime;
                $provider = $data->sms_provider;
                $to = $user->phone_number;
                $body = "Dear " . ucwords($user->name) . ", Please enter OTP " . $otp . " to verify your account.";

                if (!empty($data->sms_key) && !empty($data->sms_secret) && !empty($data->sms_from)) {
                    $send = $this->sendSms($provider, $data->sms_key, $data->sms_secret, $data->sms_from, $to, $body);
                    if ($send) {
                        $notified = 1;
                    }
                }
            }
        }

        if ($user->is_email_verified == 0) {

            $otp = mt_rand(100000, 999999);
            $user->email_token = $otp;
            $user->email_token_valid_till = $newDateTime;
            if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {

                $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);

                $client_name = $client->name;
                $mail_from = $data->mail_from;
                $sendto = $user->email;
                try {
                    Mail::send(
                        'email.verify',
                        [
                            'customer_name' => ucwords($user->name),
                            'code_text' => 'Enter below code to verify yoour account',
                            'code' => $otp,
                            'logo' => $client->logo['original'],
                            'link' => "link"
                        ],
                        function ($message) use ($sendto, $client_name, $mail_from) {
                            $message->from($mail_from, $client_name);
                            $message->to($sendto)->subject('OTP to verify account');
                        }
                    );
                    $notified = 1;
                } catch (\Exception $e) {
                    $user->save();
                }
            }
        }
        $user->save();
        if ($notified == 1) {
            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been sent.Please check.',

            ]);
            // dd("dgroeiuger");
            return response()->json(['success' => 'An otp has been sent to your email. Please check.'], 200);
        } else {
            return redirect()->back()->with('err_user', 'Provider service is not configured. Please contact administration.');
            // return response()->json(['success' => 'Provider service is not configured. Please contact administration.'], 404); 
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
        if (!$user || !$request->has('type')) {
            return response()->json(['error' => 'User not found!'], 404);
        }
        $currentTime = \Carbon\Carbon::now()->toDateTimeString();

        $message = 'Account verified successfully.';
        if ($request->has('is_forget_password') && $request->is_forget_password == 1) {
            $message = 'OTP matched successfully.';
        }

        if ($request->type == 'phone') {

            if ($user->phone_token != $request->otp) {
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

            if ($user->email_token != $request->otp) {
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
        dd("ewgwg");
    }

    /**
     * Display a listing of the resource.
     */
    public function checkout($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('forntend/checkout')->with(['navCategories' => $navCategories]);
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
