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

class RatingController extends FrontController{
	
    use ApiResponser;
    /**
     * update order product rating

     */
    public function updateProductRating(OrderProductRatingRequest $request){
         print_r($request->all());
         die();
        try {
            $user = Auth::user();
            $order_deliver = 0;
            $order_details = OrderProduct::where('id',$request->order_vendor_product_id)->whereHas('order',function($q){$q->where('user_id',Auth::id());})->first();
            if($order_details)
            $order_deliver = VendorOrderStatus::where(['order_id' => $request->order_id,'vendor_id' => $order_details->vendor_id,'order_status_option_id' => 5])->count();
            
            if($order_deliver > 0){
                $ratings = OrderProductRating::updateOrCreate(['order_vendor_product_id' => $request->order_vendor_product_id,
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'user_id' => Auth::id()],['rating' => $request->rating,'review' => $request->review??null]);

                if ($request->has('files')) {
                    $files = $request->file('files');
                    foreach($files as $file) {
                            $file = time().'_'.$file->getClientOriginalName();
                            Storage::disk('s3')->put('review', $file, 'public');
                            $img = new OrderProductRatingFile();
                            $img->order_product_rating_id = $ratings->id;
                            $img->file = $file;
                            $img->save();
                        }
                }

                if(isset($request->remove_files) && is_array($request->remove_files))    # send index array of deleted images 
                $removefiles = OrderProductRatingFile::where('order_product_rating_id',$ratings->id)->whereIn('id',$request->remove_files)->delete();
       
            }
            if(isset($ratings)) {
                return $this->successResponse($ratings,'Rating Submitted.');
            }
            return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * ratings details
    */
    public function getProductRating(Request $request){
        try {
            $ratings = OrderProductRating::where('id',$request->id)->with('reviewFiles')->first();
       
            if(isset($ratings))
            return $this->successResponse($ratings,'Rating Details.');
           
            return $this->errorResponse('Invalid rating', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


}
