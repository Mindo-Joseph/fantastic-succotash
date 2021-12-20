<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
