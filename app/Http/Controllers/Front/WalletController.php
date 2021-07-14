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
        $user_transactions = Transaction::where('payable_id', $auth_user->id)->orderBy('id', 'desc')->paginate(10);
        return view('frontend/account/wallet')->with(['user' => $user, 'navCategories' => $navCategories, 'user_transactions' => $user_transactions]);
    }

    /**
     * Credit Money Into Wallet
     *
     * @return \Illuminate\Http\Response
     */
    public function creditWallet(Request $request, $domain = '')
    {
        if( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
            $user = User::where('auth_token', $request->auth_token)->first();
        }else{
            $user = Auth::user();
        }
        if($user){
            // $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
            $credit_amount = $request->wallet_amount;
            $wallet = $user->wallet;
            // dd($wallet->toArray());
            if ($credit_amount > 0) {
                $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> by transaction reference <b>'.$request->transaction_id.'</b>']);
                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balanceFloat;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                Session::put('success', $message);
                return $this->successResponse($response, $message, 201);
            }
            else{
                return $this->errorResponse('Amount is not sufficient', 402);
            }
        }
        else{
            return $this->errorResponse('Invalid User', 402);
        }
    }

    /**
     * Credit Money Into Wallet Through gateway redirection
     *
     * @return \Illuminate\Http\Response
     */
    public function postPaymentCreditWallet(Request $request, $domain = '')
    {
        if( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
            return $this->creditWallet($request);
        }else{
            return $this->errorResponse('Invalid User', 402);
        }
    }
}
