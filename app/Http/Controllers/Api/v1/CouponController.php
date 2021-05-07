<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Cart, CartProduct, CartCoupon, Promocode, PromocodeRestriction};
use Validation;
use DB;
use Illuminate\Support\Facades\Hash;

class CouponController extends BaseController
{
    private $field_status = 2;
    /**         list Promocode        */
    public function list(Request $request, $cartId = 0)
    {
        $promocode = Promocode::with('type', 'restriction')->where('is_deleted', '0')->whereDate('expiry_date', '>=', Carbon::now())->get();
        dd($promocode->toArray());
    }

    /** apply promocode      */
    public function apply(Request $request)
    {
    }

    /** remove promoce      */
    public function remove(Request $request)
    {
    }
}