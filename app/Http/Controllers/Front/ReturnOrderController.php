<?php

namespace App\Http\Controllers\Front;

use DB;
use Config;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\Web\OrderProductRatingRequest;
use App\Models\{Order,OrderProductRating,VendorOrderStatus,OrderProduct,OrderProductRatingFile};
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Session;

class ReturnOrderController extends FrontController{
	
    use ApiResponser;
    
    /**
     * order details in modal
    */
    public function getOrderDatainModel(Request $request){
        try {
            $order_details = Order::with(['vendors.products','products.productRating', 'user', 'address'])->whereHas('vendors',function($q)use($request){
                $q->where('vendor_id', $request->vendor_id);
            })
            ->where('orders.user_id', Auth::user()->id)->where('orders.id', $request->id)->orderBy('orders.id', 'DESC')->first();

            if(isset($order_details)){
              
                if ($request->ajax()) {
                 return \Response::json(\View::make('frontend.modals.return-product-order', array('order' => $order_details))->render());
                }
            }
            return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    /**
     * order details in for return order
    */
    public function getReturnProducts(Request $request, $domain = ''){
        try {
            $langId = Session::get('customerLanguage');
            $navCategories = $this->categoryNav($langId);

            $order_details = Order::with(['vendors.products','products.productRating', 'user', 'address'])->whereHas('products',function($q)use($request){
                $q->whereIn('id', $request->return_ids);
            })
            ->where('orders.user_id', Auth::user()->id)->orderBy('orders.id', 'DESC')->first();

            if(isset($order_details)){
              return view('frontend.account.return-order')->with(['order' => $order_details,'navCategories' => $navCategories,]);
            }
            return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


}
