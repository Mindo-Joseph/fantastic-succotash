<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, ClientCurrency, Celebrity, Brand};
use Validation;
use DB;
use App\Http\Traits\ApiResponser;

class CelebrityController extends BaseController
{
    private $field_status = 2;
    use ApiResponser;

    /**     *       Get Celebrity     *       */
    public function celebrityList($keyword = 'all')
    {
        try {
            if(empty($keyword) || strtolower($keyword) == 'all'){
                $celebrity = Celebrity::with('country')->where('status', '!=', 3)
                            ->select('id', 'name', 'avatar', 'address', 'country_id')->get();
                return $this->successResponse($celebrity);
            }
            $chars = str_split($keyword);

            $celebrity = Celebrity::with('country')->select('id', 'name', 'avatar', 'address', 'country_id')
                            ->where('status', '!=', 3)
                            ->where(function ($q) use ($chars) {
                                foreach ($chars as $key => $value) {
                                    if($key == 0){
                                        $q->where('name', 'LIKE', $value . '%');
                                    }else{
                                        $q->orWhere('name', 'LIKE', $value . '%');
                                    }
                                }
                            })->orderBy('name', 'asc')->get();
            return $this->successResponse($celebrity);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**     *       Get Celebrity Products    *       */
    public function celebrityProducts(Request $request, $cid = 0)
    {
        try {
            $celebrity = Celebrity::with('brands')->where('status', '!=', 3)
                            ->select('id', 'name', 'avatar', 'address', 'country_id')->where('id', $cid)->get();
            if(!$celebrity){
                return $this->errorResponse('Celebrity not found.', 404);
            }

            
            //$cid



            return $this->successResponse($celebrity);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    
}