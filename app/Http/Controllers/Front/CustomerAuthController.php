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
use App\Models\{AppStyling, AppStylingOption, Currency, Client, Category, Brand, Cart, ReferAndEarn, ClientPreference, Vendor, ClientCurrency, User, Country, UserRefferal, Wallet, WalletHistory, CartProduct, PaymentOption};
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;

class CustomerAuthController extends FrontController
{

    public function getTestHtmlPage()
    {
        $monthlysales = \DB::table('orders')
            ->select(\DB::raw('sum(payable_amount) as y'), \DB::raw('count(*) as z'), \DB::raw('date(created_at) as x'))
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->groupBy('x')
            ->get();
        $dates = array();
        $revenue = array();
        $sales = array();
        foreach ($monthlysales as $monthly) {
            $dates[] = $monthly->x;
            $revenue[] = $monthly->y;
            $sales[] = $monthly->z;
        }
        $data = ['dates' => $dates, 'revenue' => $revenue, 'sales' => $sales];
        return $this->successResponse($data, '', 200);
    }

    public function loginForm($domain = '')
    {
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend.account.loginnew')->with(['navCategories' => $navCategories]);
    }

    public function registerForm($domain = '', Request $request)
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        if (!Session::get('referrer')) {
            return view('frontend.account.registernew')->with(['navCategories' => $navCategories]);
        } else {
            return view('frontend.account.registernew')->with(['navCategories' => $navCategories, 'code' => Session::get('referrer')]);
        }
    }

    /**     * Display forgotPassword Form     */
    public function forgotPasswordForm($domain = '')
    {
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/forgotPassword')->with(['navCategories' => $navCategories]);
    }

    /**     * Display resetPassword Form     */
    public function resetPasswordForm($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/resetPassword')->with(['navCategories' => $navCategories]);
    }

    /**     * Display resetPassword Form     */
    public function resetSuccess($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/resetSuccess')->with(['navCategories' => $navCategories]);
    }


    /**     * check if cookie already exist     */
    public function checkCookies($userid)
    {
        if (\Cookie::has('uuid')) {
            $existCookie = \Cookie::get('uuid');
            $userFind = User::where('system_id', $existCookie)->first();
            if ($userFind) {
                $cart = Cart::where('user_id', $userFind->id)->first();
                if ($cart) {
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
            $wallet = $user->wallet;
            $userRefferal = new UserRefferal();
            $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
            if ($req->refferal_code != null) {
                $userRefferal->reffered_by = $req->refferal_code;
            }
            $userRefferal->user_id = $user->id;
            $userRefferal->save();
            if ($user->id > 0) {
                if ($req->refferal_code != null) {
                    $refferal_amounts = ClientPreference::first();
                    if ($refferal_amounts) {
                        if ($refferal_amounts->reffered_by_amount != null && $refferal_amounts->reffered_to_amount != null) {
                            $reffered_by = UserRefferal::where('refferal_code', $req->refferal_code)->first();
                            $user_refferd_by = $reffered_by->user_id;
                            $user_refferd_by = User::where('id', $reffered_by->user_id)->first();
                            if ($user_refferd_by) {
                                //user reffered by amount
                                $wallet_user_reffered_by = $user_refferd_by->wallet;
                                $wallet_user_reffered_by->deposit($refferal_amounts->reffered_by_amount, ['Referral code used by <b>' . $req->name . '</b>']);
                                $wallet_user_reffered_by->balance;
                                //user reffered to amount
                                $wallet->deposit($refferal_amounts->reffered_to_amount, ['You used refferal code of <b>' . $user_refferd_by->name . '</b>']);
                                $wallet->balance;
                            }
                        }
                    }
                }
                Auth::login($user);
                $this->checkCookies($user->id);
                Session::forget('referrer');
                return redirect()->route('user.verify');
            }
        } catch (Exception $e) {
            die();
        }
    }

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
            $sendto = $user->email;
            $client_name = $client->name;
            $mail_from = $data->mail_from;
            try {
                Mail::send('email.verify', [
                    'customer_name' => ucwords($user->name),
                    'code_text' => 'We have gotton a forget password request from your account. Please enter below otp of verify that it is you.',
                    'code' => $otp,
                    'logo' => $client->logo['original'],
                    'link' => "link"
                ], function ($message) use ($sendto, $client_name, $mail_from) {
                    $message->from($mail_from, $client_name);
                    $message->to($sendto)->subject('OTP to verify account');
                });
                if (Mail::failures()) {
                    pr(Mail::failures());
                    die;
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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('customer.login');
    }
}
