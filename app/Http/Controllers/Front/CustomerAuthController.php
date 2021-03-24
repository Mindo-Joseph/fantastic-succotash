<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency};
use Illuminate\Http\Request;
use App\Http\Requests\{LoginRequest, SignupRequest};
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class CustomerAuthController extends FrontController
{
    /**     * Display login Form     */
    public function loginForm($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('forntend/login')->with(['navCategories' => $navCategories]);
    }

    /**     * Display register Form     */
    public function registerForm($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('forntend/register')->with(['navCategories' => $navCategories]);
    }

    /**     * Display forgotPassword Form     */
    public function forgotPasswordForm($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('forntend/forgotPassword')->with(['navCategories' => $navCategories]);
    }

    /**     * Display resetPassword Form     */
    public function resetPasswordForm($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('forntend/resetPassword')->with(['navCategories' => $navCategories]);
    }


    /**     * Display login Form     */
    public function login(LoginRequest $request, $domain = '')
    {
        dd($request->all());
        /*
        @if (Auth::check())
          //show logged in navbar
        @else
          //show logged out navbar
        @endif*/
    }

    /**     * Display register Form     */
    public function register(SignupRequest $req, $domain = '')
    {
        dd($request->all());
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
}