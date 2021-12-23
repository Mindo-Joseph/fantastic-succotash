<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
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


    public function quotation(Request $request)
    {
    	$data = (object) array(
            'pick_lat' => '3.048593',
            'pick_lng' => '101.671568',
            'drop_lat' => '2.754873',
            'drop_lng' => '101.703744',
            'remarks' => 'Delivery Order Message remarks',
            'pick_address' => 'Pick Address Details in region lang languages',
            'drop_address' => 'Drop Address Details in region lang languages',
            'vendor_name' => 'Testing Vendor',
            'vendor_contact' => '0376886555',
            'user_name' => 'Testing User',
            'user_phone' => '0376886555',
        );
        $quotation = $this->getQuotations($data);
        return $quotation;
    }


    public function getDeliveryFeeLalamove($vendor_id)
    {
        try{
                $customer = User::find(Auth::id());
                $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
                if ($cus_address) {

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
                    //dd($data);
                    $quotation = $this->getQuotations($data);
                    return $quotation;
                    
                }
            
        }catch(\Exception $e)
        {
            return 'Wrong';
        }

    }


    public function placeOrder(Request $request)
    {
        $data = (object) array(
            'pick_lat' => '3.048593',
            'pick_lng' => '101.671568',
            'drop_lat' => '2.754873',
            'drop_lng' => '101.703744',
            'remarks' => 'Delivery Order Message remarks',
            'pick_address' => 'Pick Address Details in region lang languages',
            'drop_address' => 'Drop Address Details in region lang languages',
            'vendor_name' => 'Testing Vendor',
            'vendor_contact' => '3768865552',
            'user_name' => 'Testing User',
            'user_phone' => '3768865551',
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
            return $response;
        }

        return $response;
    	
    }

    
}
