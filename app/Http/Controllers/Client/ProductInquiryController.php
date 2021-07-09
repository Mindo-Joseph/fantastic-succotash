<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\Role;
use App\Models\User;

class ProductInquiryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inquries = ProductInquiry::with('product')->paginate(10);
        $total_vendor = ProductInquiry::distinct()->count('vendor_id');
        $total_product = ProductInquiry::distinct()->count('product_id');
        return view('backend/inquries/index')->with(['inquries' => $inquries, 'total_vendor' => $total_vendor, 'total_product' => $total_product]);
    }
}
