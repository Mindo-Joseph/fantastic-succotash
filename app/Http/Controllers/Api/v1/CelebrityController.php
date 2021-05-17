<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Brand};
use Validation;
use DB;
use App\Http\Traits\ApiResponser;

class VendorController extends BaseController
{
    private $field_status = 2;
    use ApiResponser;

    /**     *       Get Celebrity     *       */
    public function celebrityList(Request $request, $keyword = 'all')
    {
        try {
            $celebrity = Celebrity::with('country')->where('status', '!=', 3)
                            ->select('id', 'name', 'avatar', 'address', 'country_id')->get();
            return $this->successResponse($celebrity);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**     *       Get Celebrity     *       */
    public function celebrityList1(Request $request)
    {
        try {
            $celebrity = Celebrity::with('country')->where('status', '!=', 3)
                            ->select('id', 'name', 'avatar', 'address', 'country_id')->get();
            return $this->successResponse($celebrity);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    
}