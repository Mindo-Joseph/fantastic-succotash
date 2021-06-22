<?php

namespace App\Http\Controllers\Client;

use Auth;
use App\Models\Tax;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;
use App\Models\{OrderStatusOption,DispatcherStatusOption, VendorOrderStatus,ClientPreference,OrderVendorProduct,OrderVendor,UserAddress,Vendor};
use DB;
use GuzzleHttp\Client;
class OrderController extends BaseController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $orders = Order::with(['vendors.products', 'address','user'])->orderBy('id', 'DESC');
        if (Auth::user()->is_superadmin == 0) {
            $orders = $orders->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $orders = $orders->paginate(10);
        foreach ($orders as $order) {
            foreach ($order->vendors as $vendor) {
                foreach ($vendor->products as $product) {
                    $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
                }
            }
        }
        return view('backend.order.index', compact('orders'));
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
                'vendors.products' => function($query) use ($vendor_id){
                    $query->where('vendor_id', $vendor_id);
                }))->findOrFail($order_id);
        foreach ($order->vendors as $key => $vendor) {
            foreach ($vendor->products as $key => $product) {
                $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
            }
        }
        $order_status_options = OrderStatusOption::all();
        $dispatcher_status_options = DispatcherStatusOption::all();
        $vendor_order_statuses = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->get();
        foreach ($vendor_order_statuses as $vendor_order_status) {
            $vendor_order_status_created_dates[$vendor_order_status->order_status_option_id]= $vendor_order_status->created_at;
            $vendor_order_status_option_ids[]= $vendor_order_status->order_status_option_id;
        }
        return view('backend.order.view')->with(['vendor_id' => $vendor_id, 'order' => $order, 'vendor_order_status_option_ids' => $vendor_order_status_option_ids,'order_status_options' => $order_status_options, 'dispatcher_status_options' => $dispatcher_status_options, 'vendor_order_status_created_dates'=> $vendor_order_status_created_dates]);
    }

     /**
     * Change the status of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $domain = '')
    {   
        DB::beginTransaction();
        try {
            $timezone = Auth::user()->timezone;
            $vendor_order_status_check = VendorOrderStatus::where('order_id', $request->order_id)->where('vendor_id', $request->vendor_id)->where('order_status_option_id', $request->status_option_id)->first();
            if (!$vendor_order_status_check) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $request->order_id;
                $vendor_order_status->order_status_option_id = $request->status_option_id;
                $vendor_order_status->vendor_id = $request->vendor_id;
                $vendor_order_status->save();
                if ($request->status_option_id == 2) {
                    $order_dispatch = $this->checkIfanyProductLastMileon($request);
                }
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


    // check If any Product Last Mile on
    public function checkIfanyProductLastMileon($request)
    {
        $dispatch_domain = $this->getDispatchDomain();
        if ($dispatch_domain && $dispatch_domain != false) {
            $checkdeliveryFeeAdded = OrderVendor::where(['order_id' => $request->order_id,'vendor_id' => $request->vendor_id])->first();
            if($checkdeliveryFeeAdded && $checkdeliveryFeeAdded->delivery_fee > 0.00)
            $order_dispatch = $this->placeRequestToDispatch($request->order_id,$request->vendor_id,$dispatch_domain);
        }
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
                                     
                        }
                        
                        
            }    
            catch(\Exception $e)
            {
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
}
