<?php
use Carbon\Carbon;
use GuzzleHttp\Client;
function changeDateFormate($date,$date_format){
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);    
}
   
function pr($var) {
  	echo '<pre>';
	print_r($var);
  	echo '</pre>';
}
function generateOrderNo($length = 8){
    $number = '';
    do {
        for ($i=$length; $i--; $i>0) {
            $number .= mt_rand(0,9);
        }
    } while (!empty(\DB::table('orders')->where('order_number', $number)->first(['order_number'])) );
    return $number;
}
function convertDateTimeInTimeZone($date, $timezone){
    return Carbon::createFromFormat('Y-m-d H:i:s', $date, 'UTC')->setTimezone($timezone);
}


function getDispatchClient($dispatch_domain){
   // try {
                       
                        $postdata =  ['shortCode' => $dispatch_domain->delivery_service_key_code];
                        $client = new Client(['headers' => ['content-type' => 'application/json']
                                                        ]);
                        $url = $dispatch_domain->delivery_service_key_url;                        
                        $res = $client->post($url.'/api/shortCode',
                            ['form_params' => ($postdata)]
                        );
                       
                        //$result = file_get_contents($res);
                        $response = json_decode($res->getBody(), true);
                        if($response){
                            return $response['data']['database_name'];
                        }
                        
        // }    
        // catch(\Exception $e)
        // {
        //                      dd($e->getMessage());
                                    
        // }                    
}
