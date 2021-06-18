<?php

namespace App\Http\Controllers\Api\v1;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Request;
use App\Models\{User, Transaction};
use App\Http\Controllers\Controller;

class WalletController extends Controller{
	
    public function getFindMyWalletDetails(){
    	$user = Auth::user();
        $user = User::with('country')->find($user->id);
        $paginate = $request->has('limit') ? $request->limit : 12;
        $transactions = Transaction::where('payable_id', $user->id)->get($paginate);
        $data = ['wallet_amount' => $user->balance, 'transactions' => $transactions];
        return $this->successResponse($data, '', 200);
    }
}
