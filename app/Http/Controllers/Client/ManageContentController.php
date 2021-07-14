<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{Banner,Category,Product,Promocode,User,Variant,Vendor};

class ManageContentController extends BaseController
{
    public function deleteAllSoftDeleted()
    {
        try {
            \DB::beginTransaction();
            $pro = Product::onlyTrashed()->forceDelete();
            $cat = Category::onlyTrashed()->forceDelete();
            $user = User::where('status', 3)->forceDelete();
            $ven = Vendor::where('status', 2)->forceDelete();
            $banners = Banner::where('status', 2)->forceDelete();
            $variants = Variant::where('status', 2)->forceDelete();
            $promo_codes = Promocode::where('is_deleted', 1)->forceDelete();
            \DB::commit();
            return response()->json(['success' => 'Cleaned Successfully']);
        } catch (\PDOException $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
