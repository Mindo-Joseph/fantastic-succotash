<?php

namespace App\Http\Controllers\Client\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request){
        return view('backend.accounting.vendor');
    }
}
