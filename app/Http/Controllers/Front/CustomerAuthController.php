<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Client, Category, Brand, Cart, Product, ClientPreference, Vendor, ClientCurrency, User, Country, UserDevice};
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Password;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Auth;

class CustomerAuthController extends FrontController
{
    /**     * Display login Form     */
    public function loginForm($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);

        return view('forntend/account/login')->with(['navCategories' => $navCategories]);
    }

    /**     * Display register Form     */
    public function registerForm($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);

        return view('forntend/account/register')->with(['navCategories' => $navCategories]);
    }

    /**     * Display forgotPassword Form     */
    public function forgotPasswordForm($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);

        return view('forntend/account/forgotPassword')->with(['navCategories' => $navCategories]);
    }

    /**     * Display resetPassword Form     */
    public function resetPasswordForm($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);

        return view('forntend/account/resetPassword')->with(['navCategories' => $navCategories]);
    }

    /**     * Display resetPassword Form     */
    public function resetSuccess($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);

        return view('forntend/account/resetSuccess')->with(['navCategories' => $navCategories]);
    }


    /**     * check if cookie already exist     */
    public function checkCookies($userid)
    {
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
    public function login(LoginRequest $req, $domain = '')
    {
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            $userid = Auth::id();
            $this->checkCookies($userid);
            return redirect()->route('user.verify');
        }

        $checkEmail = User::where('email', $req->email)->first();
        if ($checkEmail) {
            return redirect()->back()->with('err_password', 'Password not matched. Please enter correct password.');
        }
        return redirect()->back()->with('err_email', 'Email not exist. Please enter correct email.');
    }

    /**     * Display register Form     */
    public function register(SignupRequest $req, $domain = '')
    {
        $user = new User();

        $county = Country::where('code', strtoupper($req->countryData))->first();

        $phoneCode = mt_rand(100000, 999999);
        $emailCode = mt_rand(100000, 999999);
        $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();

        $user->name = $req->name;
        $user->email = $req->email;
        $user->phone_number = $req->full_number;
        $user->type = 1;
        $user->role_id = 1;
        $user->country_id = $county->id;


        $user->password = Hash::make($req->password);
        $user->is_email_verified = 0;
        $user->is_phone_verified = 0;
        $user->status = 1;
        $user->phone_token = $phoneCode;
        $user->email_token = $emailCode;
        $user->phone_token_valid_till = $sendTime;
        $user->email_token_valid_till = $sendTime;
        $user->save();

        if ($user->id > 0) {
            $user_device[] = [
                'user_id' => $user->id,
                'device_type' => 'web',
                'device_token' => 'web',
                'access_token' => ''
            ];
            UserDevice::insert($user_device);
            Auth::login($user);
            $this->checkCookies($user->id);
            return redirect()->route('user.verify');
        }
    }

    /**     * Display forgotPassword Form     */
    public function forgotPassword(Request $request, $domain = '')
    {
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
            $client_name = $client->name;
            $mail_from = $data->mail_from;
            $sendto = $user->email;
            try {
                Mail::send(
                    'email.verify',
                    [
                        'customer_name' => ucwords($user->name),
                        'code_text' => 'We have gotton a forget password request from your account. Please enter below otp of verify that it is you.',
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

        $user->save();
        if ($notified == 1) {
            return redirect()->route('customer.resetPassword');
        }
    }

    /**     * Display resetPassword Form     */
    public function resetPassword(Request $request, $domain = '')
    {
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

    /**     * Validate existing email     */
    public function validateEmail(Request $request, $domain = '')
    {
    }

    /**     * Validate existing email     */
    public function fblogin(Request $request, $domain = '')
    {
        dd($request->all());
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('customer.login');
    }
}
