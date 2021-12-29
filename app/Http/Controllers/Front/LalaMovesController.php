<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderVendor;
use App\Models\ShippingOption;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use App\Models\VendorOrderDispatcherStatus;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;

class LalaMovesController extends Controller
{
    use \App\Http\Traits\LalaMoves;
	use \App\Http\Traits\ApiResponser;

    private $base_price;
    private $lalamove_status;
    private $distance;
    private $amount_per_km;

    public function __construct()
    {
  
      $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'lalamove')->where('status', 1)->first();
      if($simp_creds && $simp_creds->credentials){
            $creds_arr = json_decode($simp_creds->credentials);
            $this->base_price = $creds_arr->base_price??'0';
            if($this->base_price>0)
            {
                $this->base_price = $creds_arr->base_price??'0';
                $this->distance = $creds_arr->distance??'0';
                $this->amount_per_km = $creds_arr->amount_per_km??'0';

            }
            $this->lalamove_status = $simp_creds->status??'';
        }
    }


    public function quotation(Request $request)
    {
    	$data = (object) array(
            "pick_lat"=> "3.115825684565",
            "pick_lng"=> "101.666775521484",
            "pick_address"=> "Malaysia",
            "vendor_name"=> "General Electric",
            "vendor_contact"=> "8965745236",
            "drop_lat"=> "3.229537972256",
            "drop_lng"=> "101.730552380616",
            "drop_address"=> "6PHJ+R6 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia",
            "user_name"=> "Xavier Ross",
            "user_phone"=> "+41767250736",
            "remarks"=> "Delivery vendor message remarks"
        );
        $quotation = $this->getQuotations($data);
        return $quotation;
    }


    public function getBaseprice($distance)
    {
        $distance = ($distance - $this->distance);
        if($distance < 1 && $this->base_price < 1)
        {
            return 0;    
        }

        $base_price = $this->base_price;
        $amount_per_km = $this->amount_per_km;
        $total = $base_price + ($distance * $amount_per_km);
        return  $total;
        
       // + ($paid_duration * $pricingRule->duration_price);

    }


    public function getDeliveryFeeLalamove($vendor_id)
    {
        try{    

                $customer = User::find(Auth::id());
                $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
                if ($cus_address && $this->lalamove_status==1){

                    $vendor_details = Vendor::find($vendor_id);
                    $data = (object) array(
                        'pick_lat' => $vendor_details->latitude,
                        'pick_lng' => $vendor_details->longitude,
                        'pick_address' => $vendor_details->address,
                        'vendor_name' => $vendor_details->name,
                        // 'vendor_contact' => $vendor_details->phone_no,
                        'vendor_contact' => '3768865552',
                        'drop_lat' => $cus_address->latitude,
                        'drop_lng' => $cus_address->longitude,
                        'drop_address' => $cus_address->address,
                        'user_name' => $customer->name,
                        'user_phone' => '3768865551',
                        //'user_phone' => $customer->phone_number,
                        'remarks' => 'Delivery vendor message remarks'
                    );
            
                    $quotation = $this->getQuotations($data);
                    $actualAmount=0;
                    if($quotation['code']!='409')
                    { 
                        $json = json_decode($quotation['response']);
                        $distance =  round($json->distance->value/1000);
                        if($this->base_price > 0)
                        {
                            $actualAmount = $this->getBaseprice($distance);
                         }else{
                            $actualAmount = $json->totalFee;
                        }
                    }
                    //dd($actualAmount);
                    return $actualAmount;
                }
            
        }catch(\Exception $e)
        {
            return 0;
        }

    }

    public function placeOrderToLalamove($vendor_id,$user_id,$order_id)
    {
        $order = Order::find($order_id);
        $customer = User::find($user_id);
        //$cus_address = UserAddress::where('user_id', $customer->id)->orderBy('is_primary', 'desc')->first();
        $cus_address = UserAddress::find($order->address_id);
                if ($cus_address && $this->lalamove_status==1){

                    $vendor_details = Vendor::find($vendor_id);
                    $data = (object) array(
                        'pick_lat' => $vendor_details->latitude,
                        'pick_lng' => $vendor_details->longitude,
                        'pick_address' => $vendor_details->address,
                        'vendor_name' => $vendor_details->name,
                        'vendor_contact' => $vendor_details->phone_no,
                        'drop_lat' => $cus_address->latitude,
                        'drop_lng' => $cus_address->longitude,
                        'drop_address' => $cus_address->address,
                        'user_name' => $customer->name,
                        'user_phone' => $customer->phone_number,
                        'remarks' => 'Delivery vendor message remarks'
                    );
                   
                $quotation = $this->getQuotations($data);
                $response = json_decode($quotation['response']);
                if($quotation['code']=='200'){
                        $response = $this->placeOrders($data,$response);
                        if($response['code']=='200'){
                            $response = json_decode($response['response']);
                        }else{
                            $response = 2;
                        }
                }else{
                    $response = 2;
                }
            }

        return $response;
    	
    }


    public function placeOrder($vendor_id)
    {
        $customer = User::find(Auth::id());
        $cus_address = UserAddress::find('150');
                if ($cus_address && $this->lalamove_status==1){
                    //dd($vendor_id);
                    $vendor_details = Vendor::find('16');
                    $data = (object) array(
                        'pick_lat' => $vendor_details->latitude,
                        'pick_lng' => $vendor_details->longitude,
                        'pick_address' => $vendor_details->address,
                        'vendor_name' => $vendor_details->name,
                        'vendor_contact' => $vendor_details->phone_no,
                        'drop_lat' => $cus_address->latitude,
                        'drop_lng' => $cus_address->longitude,
                        'drop_address' => $cus_address->address,
                        'user_name' => $customer->name,
                        'user_phone' => $customer->phone_number,
                        'remarks' => 'Delivery vendor message remarks'
                    );

                $quotation = $this->getQuotations($data);
                $response = json_decode($quotation['response']);
                if($quotation['code']=='200'){
                        $response = $this->placeOrders($data,$response);
                        if($quotation['code']=='200'){
                            $response = json_decode($quotation['response']);
                        }else{
                            $response = json_decode($quotation['response']);
                        }
                }else{
                    $response;
                }
            }

        return $response;
    	
    }

    public function placeOrder2($vendor_id)
    {
        $response = $this->testing();
        dd($response);
    }

    public function webhooks(Request $request)
    {
        // $request = '{
        //     "apiKey": "pk_test_11c917c792586a46bef122660d6e04b9",
        //     "timestamp": 1640697361,
        //     "signature": "92153b66e867160b139f1288c07170bf2e66851a7c77113f546cd3c143e5d349",
        //     "eventId": "52C93E91-FD46-F542-67B1-225F324AD809",
        //     "eventType": "ORDER_STATUS_CHANGED",
        //     "data": {
        //       "order": {
        //         "id": "195610807268",
        //         "city": "MY_KUL",
        //         "status": "ASSIGNING_DRIVER",
        //         "driverId": "",
        //         "shareLink": "https://share.sandbox.lalamove.com/?MY100211228211600794410010041218730&lang=en_MY&source=api_wrapper&sign=757a3f926c2ca25fc2e121c21eec4dd5",
        //         "previousStatus": "",
        //         "updatedAt": "2021-12-28T21:16.00Z"
        //       }
        //     }
        //   }';

        $json = json_decode($request);
        if(isset($json->eventType) && $json->eventType == 'ORDER_STATUS_CHANGED' && $json->data->order->status == 'ASSIGNING_DRIVER')
        {
            // ASSIGNING_DRIVER means Order is placed and assigning drivers
            OrderVendor::where('web_hook_code',$json->data->order->id)
            ->update(['lalamove_tracking_url'=>$json->data->order->shareLink]);
            $details = OrderVendor::where('web_hook_code',$json->data->order->id)->first();
            VendorOrderDispatcherStatus::UpdateOrCreate(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id],['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'1']);
        }elseif(isset($json->eventType) && $json->eventType == 'ORDER_STATUS_CHANGED' && $json->data->order->status == 'ON_GOING')
        {

            // ON_GOING means driver assigned and start drive
            OrderVendor::where('web_hook_code',$json->data->order->id)
            ->update(['lalamove_tracking_url'=>$json->data->order->shareLink]);
            $details = OrderVendor::where('web_hook_code',$json->data->order->id)->first();
            VendorOrderDispatcherStatus::UpdateOrCreate(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id],['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'2']);
            VendorOrderDispatcherStatus::UpdateOrCreate(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id],['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'3']);
        }elseif(isset($json->eventType) && $json->eventType == 'ORDER_STATUS_CHANGED' && $json->data->order->status == 'PICKED_UP')
        {
            // PICKED_UP means driver picked order and out for delivery
            OrderVendor::where('web_hook_code',$json->data->order->id)
            ->update(['lalamove_tracking_url'=>$json->data->order->shareLink]);
            $details = OrderVendor::where('web_hook_code',$json->data->order->id)->first();
            VendorOrderDispatcherStatus::UpdateOrCreate(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id],['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'4']);
        }elseif(isset($json->eventType) && $json->eventType == 'ORDER_STATUS_CHANGED' && $json->data->order->status == 'COMPLETED')
        {
            // COMPLETED means driver complete the delivery
            OrderVendor::where('web_hook_code',$json->data->order->id)
            ->update(['lalamove_tracking_url'=>$json->data->order->shareLink]);
            $details = OrderVendor::where('web_hook_code',$json->data->order->id)->first();
            VendorOrderDispatcherStatus::UpdateOrCreate(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id],['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'5']);
        }



        if($request && isset($json->data)){
         Webhook::create(['tracking_order_id'=>(($json->data->order->id)?$json->data->order->id:''),'response'=>$request]);
        }

        return json_encode(['status'=>'200']);


    }

    
}
