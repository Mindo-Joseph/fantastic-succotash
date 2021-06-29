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
     
        try {
            $user = Auth::user();
            $order_deliver = 0;
            $order_details = OrderProduct::where('id',$request->order_vendor_product_id)->whereHas('order',function($q){$q->where('user_id',Auth::id());})->first();
            if($order_details)
            $order_deliver = VendorOrderStatus::where(['order_id' => $order_details->order_id,'vendor_id' => $order_details->vendor_id,'order_status_option_id' => 5])->count();
            
            if($order_deliver > 0){
                $ratings = OrderProductRating::updateOrCreate(['order_vendor_product_id' => $request->order_vendor_product_id,
                'order_id' => $order_details->order_id,
                'product_id' => $order_details->product_id,
                'user_id' => Auth::id()],['rating' => $request->rating,'review' => $request->review??$request->hidden_review]);

               if ($image = $request->file('images')) {
                    foreach ($image as $files) {
                    $file =  substr(md5(microtime()), 0, 15).'_'.$files->getClientOriginalName();
                    $storage = Storage::disk('s3')->put('/review', $files, 'public');
                    $img = new OrderProductRatingFile();
                    $img->order_product_rating_id = $ratings->id;
                    $img->file = $storage;
                    $img->save();
                   
                    }
                }
               
              if(isset($request->remove_files) && is_array($request->remove_files))    # send index array of deleted images 
                $removefiles = OrderProductRatingFile::where('order_product_rating_id',$ratings->id)->whereIn('id',$request->remove_files)->delete();
       
            }
            if(isset($ratings)) {
                return $this->successResponse($ratings,'Rating Submitted.');
            }
            return $this->errorResponse('Invalid order', 200);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * ratings details
    */
    public function getProductRating(Request $request){
        try {
            $rating_details = OrderProductRating::where('id',$request->id)->with('reviewFiles')->first();
            if(isset($rating_details)){
              
                if ($request->ajax()) {
                 return \Response::json(\View::make('frontend.modals.update-review-rating', array('rating'=>  $rating_details->rating,'order_vendor_product_id' => $request->order_vendor_product_id ,'rating_details' => $rating_details))->render());
                }

                return $this->successResponse($rating_details,'Rating Details.');
            }
            return \Response::json(\View::make('frontend.modals.update-review-rating', array('rating'=> 0 ,'order_vendor_product_id' => $request->order_vendor_product_id ,'rating_details' => $rating_details))->render());
           
            return $this->errorResponse('Invalid rating', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


}
