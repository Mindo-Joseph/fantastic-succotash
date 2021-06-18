<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class WalletController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $users = User::where('id', $user_id)->with('wallet')->get();

        foreach ($users as $user) {
            pr($user->balance);
        }

        dd("ewgwe");
        $auth_user = Auth::user();
        $user_transactions = Transaction::where('payable_id', Auth::user()->id)->get();
        // $auth_user->with('wallets');
        dd($user_transactions);
    }
}
