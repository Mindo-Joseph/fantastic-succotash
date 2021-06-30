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
use App\Http\Requests\Web\OrderProductReturnRequest;
use App\Models\{Order,OrderProductRating,VendorOrderStatus,OrderProduct,OrderProductRatingFile,ReturnReason,OrderReturnRequest,OrderReturnRequestFile};
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
            $reasons = ReturnReason::where('status','Active')->orderBy('order','asc')->get();
            $order_details = Order::with(['vendors.products','products.productRating', 'user', 'address'])->whereHas('products',function($q)use($request){
                $q->where('id', $request->return_ids);
            })
            ->where('orders.user_id', Auth::user()->id)->orderBy('orders.id', 'DESC')->first();

            if(isset($order_details)){
              return view('frontend.account.return-order')->with(['order' => $order_details,'navCategories' => $navCategories,'reasons' => $reasons]);
            }
            return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    /**
     * return  order product 
    */
    public function updateProductReturn(OrderProductReturnRequest $request){
     
        try {
            $user = Auth::user();
            $order_deliver = 0;
            $order_details = OrderProduct::where('id',$request->order_vendor_product_id)->whereHas('order',function($q){$q->where('user_id',Auth::id());})->first();
            if($order_details)
            $order_deliver = VendorOrderStatus::where(['order_id' => $order_details->order_id,'vendor_id' => $order_details->vendor_id,'order_status_option_id' => 5])->count();
            
            if($order_deliver > 0){
                $returns = OrderReturnRequest::updateOrCreate(['order_vendor_product_id' => $request->order_vendor_product_id,
                'order_id' => $order_details->order_id,
                'return_by' => Auth::id()],['reason' => $request->reason??null,'coments' => $request->coments??null]);

               if ($image = $request->file('images')) {
                    foreach ($image as $files) {
                    $file =  substr(md5(microtime()), 0, 15).'_'.$files->getClientOriginalName();
                    $storage = Storage::disk('s3')->put('/return', $files, 'public');
                    $img = new OrderReturnRequestFile();
                    $img->order_return_request_id = $returns->id;
                    $img->file = $storage;
                    $img->save();
                   
                    }
                }
               
              if(isset($request->remove_files) && is_array($request->remove_files))    # send index array of deleted images 
                $removefiles = OrderReturnRequestFile::where('order_return_request_id',$returns->id)->whereIn('id',$request->remove_files)->delete();
       
            }
            if(isset($returns)) {
                return $this->successResponse($returns,'Return Submitted.');
            }
            return $this->errorResponse('Invalid order', 200);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }


    


}
