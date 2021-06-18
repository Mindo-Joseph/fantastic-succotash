<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Session;

class WalletController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $langId = Session::get('customerLanguage');
        $user = User::with('country')->find(Auth::user()->id);
        $navCategories = $this->categoryNav($langId);
        $auth_user = Auth::user();
        $user_transactions = Transaction::where('payable_id', $auth_user->id)->get();
        return view('frontend/account/wallet')->with(['user' => $user, 'navCategories' => $navCategories, 'user_transactions' => $user_transactions]);
    }
}
