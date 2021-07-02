<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Category, Brand, Product, Celebrity, ClientLanguage, Vendor, VendorCategory, ClientCurrency, ProductVariantSet, ServiceArea};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class CelebrityController extends FrontController
{
    
    /** 
     * Display product list By Celebrity id
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $domain = '', $slug = 0)
    {
        
    }
}