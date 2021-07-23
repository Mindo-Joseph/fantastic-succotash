<?php

namespace App\Http\Controllers\Front;
use DB;
use Auth;
use Session;
use Password;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Models\{AppStyling, AppStylingOption, Currency, Client, Category, Brand, Cart, ReferAndEarn, ClientPreference, Vendor, ClientCurrency, User, Country, UserRefferal, Wallet, WalletHistory, CartProduct, PaymentOption, UserVendor,Permissions, UserPermissions, VendorDocs, VendorRegistrationDocument, EmailTemplate};

class ForgotPasswordController extends FrontController{
    public function getResetPasswordForm($domain = ''){
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/resetPassword')->with(['navCategories' => $navCategories]);
    }

    public function resetSuccess($domain = ''){
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/resetSuccess')->with(['navCategories' => $navCategories]);
    }
    public function getForgotPasswordForm($domain = ''){
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/forgotPassword')->with(['navCategories' => $navCategories]);
    }
    public function postForgotPassword(Request $request, $domain = ''){
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
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        $user->email_token = $otp;
        $otp = mt_rand(100000, 999999);
        $newDateTime = Carbon::now()->addMinutes(10)->toDateTimeString();
        $user->email_token_valid_till = $newDateTime;
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $sendto = $user->email;
            $client_name = $client->name;
            $mail_from = $data->mail_from;
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
}
