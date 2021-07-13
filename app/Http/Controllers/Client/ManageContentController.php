<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promocode;
use App\Models\User;
use App\Models\Variant;
use App\Models\Vendor;

class ManageContentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAllSoftDeleted()
    {
        $cat = Category::onlyTrashed()->forceDelete();
        $ven = Vendor::where('status', 2)->forceDelete();
        $pro = Product::onlyTrashed()->forceDelete();
        $promo_codes = Promocode::where('is_deleted', 1)->forceDelete();
        $user = User::where('status', 3)->forceDelete();
        $banners = Banner::where('status', 2)->forceDelete();
        $variants = Variant::where('status', 2)->forceDelete();
        return response()->json(['success' => 'Cleaned Successfully']);
    }
}
