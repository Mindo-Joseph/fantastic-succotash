<?php

namespace App\Http\Controllers\Client;

use Auth;
use App\Models\Tax;
use App\Models\Order;
use App\Models\User;
use App\Models\VendorOrderDispatcherStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;
use App\Models\{OrderStatusOption,DispatcherStatusOption, VendorOrderStatus,ClientPreference,OrderVendorProduct,OrderVendor,UserAddress,Vendor,OrderReturnRequest};
use DB;
use GuzzleHttp\Client;
use App\Http\Traits\ApiResponser;
class OrderController extends BaseController{

    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $user = Auth::user();
        $orders = Order::with(['vendors.products','orderStatusVendor', 'address','user'])->orderBy('id', 'DESC');
        if (Auth::user()->is_superadmin == 0) {
            $orders = $orders->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $orders = $orders->paginate(10);
        foreach ($orders as $order) {
            $order->address = $order->address ? $order->address['address'] : '';
            $order->created_date = convertDateTimeInTimeZone($order->created_at, $user->timezone, 'd-m-Y, H:i A');
            foreach ($order->vendors as $vendor) {
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                $vendor->order_status = $vendor_order_status ? $vendor_order_status->OrderStatusOption->title : '';
                foreach ($vendor->products as $product) {
                    $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
                }
            }
        }
        $return_requests = OrderReturnRequest::where('status','Pending');
        if (Auth::user()->is_superadmin == 0) {
            $return_requests = $return_requests->whereHas('order.vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $return_requests = $return_requests->count();
        return view('backend.order.index', compact('orders','return_requests'));
    }

    public function postOrderFilter(Request $request, $domain = ''){
        $user = Auth::user();
        $filter_order_status = $request->filter_order_status;
        $orders = Order::with(['vendors.products','vendors.status','orderStatusVendor', 'address','user'])->orderBy('id', 'DESC');
        if (Auth::user()->is_superadmin == 0) {
            $orders = $orders->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        if($filter_order_status){
            switch ($filter_order_status) { 
                case 'pending_orders':
                    $orders = $orders->whereHas('vendors', function ($query){
                        $query->where('order_status_option_id', 1);
                   });
                break;
                case 'active_orders':
                    $order_status_options = [2,4,5];
                    $orders = $orders->whereHas('vendors', function ($query) use($order_status_options){
                        $query->whereIn('order_status_option_id', $order_status_options);
                    });
                break;
                case 'orders_history':
                    $order_status_options = [6,3];
                    $orders = $orders->whereHas('vendors', function ($query) use($order_status_options){
                        $query->whereIn('order_status_option_id', $order_status_options);
                    });
                break;
            }
        }
        $orders = $orders->paginate(50);
        foreach ($orders as $order) {
            $order->created_date = convertDateTimeInTimeZone($order->created_at, $user->timezone, 'd-m-Y, H:i A');
            foreach ($order->vendors as $vendor) {
                $vendor->vendor_detail_url = route('order.show.detail', [$order->id, $vendor->vendor_id]);
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                $vendor->order_status = $vendor_order_status ? $vendor_order_status->OrderStatusOption->title : '';
                $product_total_count = 0;
                foreach ($vendor->products as $product) {
                        $product_total_count += $product->quantity * $product->price;
                        $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
                }
                $vendor->product_total_count = $product_total_count;
                $vendor->final_amount = $vendor->taxable_amount+ $product_total_count;
            }
            if($order->vendors){
              unset($order);
            }
        }
        return $this->successResponse($orders,'',201);
    }
    /**
     * Display the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */

    public function getOrderDetail($domain = '', $order_id, $vendor_id){
        $vendor_order_status_option_ids = [];
        $vendor_order_status_created_dates = [];
        $order = Order::with(array(
                'vendors' => function($query) use ($vendor_id){
                    $query->where('vendor_id', $vendor_id);
                },
                'vendors.products.prescription' => function($query) use ($vendor_id, $order_id){
                    $query->where('vendor_id', $vendor_id)->where('order_id', $order_id);
                },
                'vendors.products' => function($query) use ($vendor_id){
                    $query->where('vendor_id', $vendor_id);
                }))->findOrFail($order_id);
        foreach ($order->vendors as $key => $vendor) {
            foreach ($vendor->products as $key => $product) {
                $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
            }
        }
        $order_status_options = OrderStatusOption::where('type', 1)->get();
        $dispatcher_status_options = DispatcherStatusOption::with(['vendorOrderDispatcherStatus' => function ($q) use($order_id,$vendor_id){
            $q->where(['order_id' => $order_id,'vendor_id' => $vendor_id]);
        }])->get();
        $vendor_order_statuses = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->get();
        foreach ($vendor_order_statuses as $vendor_order_status) {
            $vendor_order_status_created_dates[$vendor_order_status->order_status_option_id]= $vendor_order_status->created_at;
            $vendor_order_status_option_ids[]= $vendor_order_status->order_status_option_id;
        }
         return view('backend.order.view')->with(['vendor_id' => $vendor_id, 'order' => $order, 
        'vendor_order_status_option_ids' => $vendor_order_status_option_ids,
        'order_status_options' => $order_status_options, 
        'dispatcher_status_options' => $dispatcher_status_options, 
        'vendor_order_status_created_dates'=> $vendor_order_status_created_dates]);
    }

     /**
     * Change the status of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $domain = ''){   
        DB::beginTransaction();
        try {
            $timezone = Auth::user()->timezone;
            $vendor_order_status_check = VendorOrderStatus::where('order_id', $request->order_id)->where('vendor_id', $request->vendor_id)->where('order_status_option_id', $request->status_option_id)->first();
            if (!$vendor_order_status_check) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $request->order_id;
                $vendor_order_status->vendor_id = $request->vendor_id;
                $vendor_order_status->order_vendor_id = $request->order_vendor_id;
                $vendor_order_status->order_status_option_id = $request->status_option_id;
                $vendor_order_status->save();
                if ($request->status_option_id == 2) {
                    $order_dispatch = $this->checkIfanyProductLastMileon($request);
                    if($order_dispatch && $order_dispatch == 1)
                    $stats = $this->insertInVendorOrderDispatchStatus($request);
                }
                OrderVendor::where('vendor_id', $request->vendor_id)->where('order_id', $request->order_id)->update(['order_status_option_id' => $request->status_option_id]);
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'created_date' => convertDateTimeInTimeZone($vendor_order_status->created_at, $timezone, 'l, F d, Y, H:i A'),
                    'message' => 'Order Status Updated Successfully.'
                ]);
            }
            } catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
           
        }
        
    }
    /// ******************   insert In Vendor Order Dispatch Status   ************************ ///////////////
    public function insertInVendorOrderDispatchStatus($request)
    {
        $update = VendorOrderDispatcherStatus::updateOrCreate(['dispatcher_id' => null,
        'order_id' =>  $request->order_id,
        'dispatcher_status_option_id' => 1,
        'vendor_id' =>  $request->vendor_id]);
    }

    /// ******************  check If any Product Last Mile on   ************************ ///////////////
    public function checkIfanyProductLastMileon($request)
    {   
        $order_dispatchs = 2;
        $dispatch_domain = $this->getDispatchDomain();
        if ($dispatch_domain && $dispatch_domain != false) {
            $checkdeliveryFeeAdded = OrderVendor::where(['order_id' => $request->order_id,'vendor_id' => $request->vendor_id])->first();
            if($checkdeliveryFeeAdded && $checkdeliveryFeeAdded->delivery_fee > 0.00)
            $order_dispatchs = $this->placeRequestToDispatch($request->order_id,$request->vendor_id,$dispatch_domain);
            if($order_dispatchs && $order_dispatchs == 1)
            return 1;
        }
        return 2;
    }
    // place Request To Dispatch
    public function placeRequestToDispatch($order,$vendor,$dispatch_domain){
        try {       
            
                    $order = Order::find($order);
                    $customer = User::find($order->user_id);
                    $cus_address = UserAddress::find($order->address_id);
                    $tasks = array();
                    if ($order->payment_method == 2) {
                        $cash_to_be_collected = 'Yes';
                        $payable_amount = $order->payable_amount;
                    } else {
                        $cash_to_be_collected = 'No';
                        $payable_amount = 0.00;
                    }
                        $dynamic = uniqid($order->id.$vendor);
                        $call_back_url = route('dispatch-order-update',$dynamic);

                        $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
                        $tasks = array();
                        $meta_data = '';
                        $tasks[] = array('task_type_id' => 1,
                                                        'latitude' => $vendor_details->latitude??'',
                                                        'longitude' => $vendor_details->longitude??'',
                                                        'short_name' => '',
                                                        'address' => $vendor_details->address??'',
                                                        'post_code' => '',
                                                        'barcode' => '',
                                                        );
                                        
                        $tasks[] = array('task_type_id' => 2,
                                                        'latitude' => $cus_address->latitude??'',
                                                        'longitude' => $cus_address->longitude??'',
                                                        'short_name' => '',
                                                        'address' => $cus_address->address??'',
                                                        'post_code' => $cus_address->pincode??'',
                                                        'barcode' => '',
                                                        );
                                   
                        $postdata =  ['customer_name' => $customer->name ?? 'Dummy Customer',
                                                        'customer_phone_number' => $customer->phone_number ?? '+919041969648',
                                                        'customer_email' => $customer->email ?? 'dineshk@codebrewinnovations.com',
                                                        'recipient_phone' => $customer->phone_number ?? '+919041969648',
                                                        'recipient_email' => $customer->email ?? 'dineshk@codebrewinnovations.com',
                                                        'task_description' => "Order From :".$vendor_details->name,
                                                        'allocation_type' => 'u',
                                                        'task_type' => 'now',
                                                        'cash_to_be_collected' => $payable_amount??0.00,
                                                        'barcode' => '',
                                                        'call_back_url' => $call_back_url??null,
                                                        'task' => $tasks
                                                        ];

                      
                        $client = new Client(['headers' => ['personaltoken' => $dispatch_domain->delivery_service_key,
                                                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                                                        'content-type' => 'application/json']
                                                            ]);
                                                
                        $url = $dispatch_domain->delivery_service_key_url;
                        $res = $client->post(
                            $url.'/api/task/create',
                            ['form_params' => (
                                $postdata
                            )]
                        );
                        $response = json_decode($res->getBody(), true);
                        if($response && $response['task_id'] > 0){
                            $up_web_hook_code = OrderVendor::where(['order_id' => $order->id,'vendor_id' => $vendor])
                                    ->update(['web_hook_code' => $dynamic]);
                            return 1;
                        }
                        return 2;
                        
            }    
            catch(\Exception $e)
            {
                return 2;
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
                        
            }
           
           
    }
     
    # get prefereance if last mile on or off and all details updated in config
    public function getDispatchDomain(){
        $preference = ClientPreference::first();
        if($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }



     /**
     * Display a listing of the order return request.
     *
     * @return \Illuminate\Http\Response
     */
    public function returnOrders(Request $request, $domain = '',$status){
        try {
            
            $orders_list = OrderReturnRequest::where('status',$status)->with('product')->orderBy('updated_at','DESC');
            if (Auth::user()->is_superadmin == 0) {
                $orders_list = $orders_list->whereHas('order.vendors.vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $orders[$status] = $orders_list->paginate(20);
            return view('backend.order.return'
            , [
                'orders' => $orders,
                'status' => $status
            ]);

           
            } catch (\Throwable $th) {
                return redirect()->back();
            }
    }


     /**
     * return orders details
    */
    public function getReturnProductModal(Request $request, $domain = ''){
        try {
            $return_details = OrderReturnRequest::where('id',$request->id)->with('returnFiles')->first();
            if(isset($return_details)){
              
                if ($request->ajax()) {
                 return \Response::json(\View::make('frontend.modals.update-return-product-client', array('return_details' => $return_details))->render());
                }
            }
            return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

     /**
     * return  order product 
    */
    public function updateProductReturn(Request $request){
     
        try {
           
            $returns = OrderReturnRequest::where('id',$request->id)->update(['status'=>$request->status??null]);
            if(isset($returns)) {
                return $this->successResponse($returns,'Updated.');
            }
            return $this->errorResponse('Invalid order', 200);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
