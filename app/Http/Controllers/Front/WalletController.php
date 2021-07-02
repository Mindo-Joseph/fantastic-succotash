<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use App\Models\User;
use App\Models\Transaction;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Session;

class WalletController extends FrontController
{
    use ApiResponser;
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

    /**
     * Credit Money Into Wallet
     *
     * @return \Illuminate\Http\Response
     */
    public function creditWallet(Request $request, $domain = '')
    {
        $user = Auth::user();
        if($user){
            // $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
            $credit_amount = $request->wallet_amount;
            $wallet = $user->wallet;
            // dd($wallet->toArray());
            if ($credit_amount > 0) {
                $wallet->deposit($credit_amount, ['Wallet has been <b>Credited</b> by transaction reference <b>'.$request->transaction_id.'</b>']);
                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balance;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                Session::put('success', $message);
                return $this->successResponse($response, $message);
            }
            else{
                return $this->errorResponse('Amount is not sufficient', 402);
            }
        }
        else{
            return $this->errorResponse('Invalid User', 402);
        }
    }
}
