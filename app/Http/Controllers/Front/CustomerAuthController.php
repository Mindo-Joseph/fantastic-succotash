<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, User, Country, UserDevice, UserVerification};
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Password;
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


    /**     * Display login Form     */
    public function login(LoginRequest $req, $domain = '')
    {

        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            $userid = Auth::id();
            return redirect()->route('user.verify');
        }

        $checkEmail = User::where('email', $req->email)->first();
        if($checkEmail){
            return redirect()->back()->with('err_password', 'Password not matched. Please enter correct password.');
        }
        return redirect()->back()->with('err_email', 'Email not exist. Please enter correct email.');
    }

    /**     * Display register Form     */
    public function register(SignupRequest $req, $domain = '')
    {
        $user = new User();

        $county = Country::where('code', strtoupper($req->countryData))->first();
        
        $user->name = $req->name;
        $user->email = $req->email;
        $user->phone_number = $req->full_number;
        $user->password = Hash::make($req->password);
        $user->type = 1;
        $user->status = 1;
        $user->role_id = 1;
        $user->country_id = $county->id;
        $user->save();

        if($user->id > 0){
             $user_device[] = [
                'user_id' => $user->id,
                'device_type' => 'web',
                'device_token' => 'web',
                'access_token' => ''
            ];
            UserDevice::insert($user_device);

            $user_verify[] = [
                'user_id' => $user->id,
                'is_email_verified' => 0,
                'is_phone_verified' => 0
            ];
            UserVerification::insert($user_verify);
            Auth::login($user);
            return redirect()->route('user.verify');
        }
    }

    /**     * Display forgotPassword Form     */
    public function forgotPassword(Request $request, $domain = '')
    {
        
    }

    /**     * Display resetPassword Form     */
    public function resetPassword(Request $request, $domain = '')
    {
        
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
    
}


