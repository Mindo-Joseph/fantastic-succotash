<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Timezonelist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Front\FrontController;
use App\Models\{UserWishlist, User, Product, UserAddress, UserRefferal, ClientPreference, Client, Order, Transaction};

class SubscriptionController extends FrontController
{
    /**
     * get user subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscriptions(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend.account.subscriptions')->with(['navCategories' => $navCategories]);
    }
}
