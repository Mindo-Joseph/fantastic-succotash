<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;

class WebStylingController extends BaseController{
    //
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        // $wallets = Wallet::with('user')->get();
        return view('backend/web_styling/index');
    }
}
