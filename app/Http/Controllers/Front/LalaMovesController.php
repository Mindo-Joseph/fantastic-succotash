<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ShippingOption;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
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
                        'vendor_contact' => $vendor_details->phone_no,
                        'drop_lat' => $cus_address->latitude,
                        'drop_lng' => $cus_address->longitude,
                        'drop_address' => $cus_address->address,
                        'user_name' => $customer->name,
                        'user_phone' => $customer->phone_number,
                        'remarks' => 'Delivery vendor message remarks'
                    );
            
                    $quotation = $this->getQuotations($data);
                   
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
            return $e->getMessage();
        }

    }


    public function placeOrder($vendor_id)
    {
        $customer = User::find(Auth::id());
        $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
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

    
}
