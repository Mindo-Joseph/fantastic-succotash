<?php

namespace App\Http\Controllers\Front;

use App\Models\{VendorOrderDispatcherStatus};
use Illuminate\Http\Request;
use App\Http\Requests\DispatchOrderStatusUpdateRequest;
use App\Http\Controllers\Front\FrontController;
use Carbon\Carbon;
use Auth;
use Session;
use DB;

class DispatcherController extends FrontController
{
   
   
   
    /******************    ---- order status update from dispatch (Need to pass all latitude / longitude of pickup & drop ) -----   ******************/
    public function dispatchOrderStatusUpdate(DispatchOrderStatusUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $update = VendorOrderDispatcherStatus::Create(['dispatcher_id' => null,
            'order_id' =>  $request->order_id,
            'dispatcher_status_option_id ' =>  $request->dispatcher_status_option_id,
            'vendor_id' =>  $request->vendor_id ]);
    
            DB::commit();
            return response()->json([
            'status' => 200,
            'message' => 'Order status updated.'
        ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
