<?php

namespace App\Http\Controllers\Client\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromoCodeController extends Controller
{
    public function index(Request $request){
        return view('backend/accounting/promocode');
    }
}
