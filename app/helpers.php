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
function http_check($url) {
    $return = $url;
    if ((!(substr($url, 0, 7) == 'http://')) || (!(substr($url, 0, 8) == 'https://'))) {
        $return = 'http://' . $url;
    }
    return $return;
} 
function getMonthNumber($month_name){
    if($month_name == 'January'){
        return 1;
    }else if($month_name == 'February'){
        return 2;
    }else if($month_name=='March'){
        return 3;
    }else if($month_name=='April'){
        return 4;
    }else if($month_name=='May'){
        return 5;
    }else if($month_name=='June'){
        return 6;
    }else if($month_name=='July'){
        return 7;
    }else if($month_name=='August'){
        return 8;
    }else if($month_name=='September'){
        return 9;
    }else if($month_name=='October'){
        return 10;
    }else if($month_name=='November'){
        return 11;
    }else if($month_name=='December'){
        return 12;
    }
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
function convertDateTimeInTimeZone($date, $timezone, $format = 'Y-m-d H:i:s'){
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    return $date->format($format);
}

