<?php
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Nomenclature;
use App\Models\UserRefferal;
use App\Models\ClientPreference;

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
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $return = 'http://' . $url;
    }
    return $return;
}
function getUserDetailViaApi($user){
    $user_refferal = UserRefferal::where('user_id', $user->id)->first();
    $client_preference = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format','time_format', 'map_key','sms_provider','verify_email','verify_phone', 'app_template_id', 'web_template_id')->first();
    $data['name'] = $user->name;
    $data['email'] = $user->email;
    $data['source'] = $user->image;
    $data['is_admin'] = $user->is_admin;
    $data['dial_code'] = $user->dial_code;
    $data['auth_token'] =  $user->auth_token;
    $data['phone_number'] = $user->phone_number;
    $data['client_preference'] = $client_preference;
    $data['cca2'] = $user->country ? $user->country->code : '';
    $data['callingCode'] = $user->country ? $user->country->phonecode : '';
    $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code: '';
    $data['verify_details'] = ['is_email_verified' => $user->is_email_verified, 'is_phone_verified' => $user->is_phone_verified];
    return $data;
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
function getNomenclatureName($searchTerm, $plural = true){
    $result = Nomenclature::with(['translations' => function($q) {
                $q->where('language_id', session()->get('customerLanguage'));
            }])->where('label', 'LIKE', "%{$searchTerm}%")->first();
    if($result){
        $searchTerm = $result->translations->count() != 0 ? $result->translations->first()->name : ucfirst($searchTerm);
    }
    return $plural ? $searchTerm : rtrim($searchTerm, 's');
}
function convertDateTimeInTimeZone($date, $timezone, $format = 'Y-m-d H:i:s'){
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    return $date->format($format);
}
function dateTimeInUserTimeZone($date, $timezone, $showTime=true, $showSeconds=false){
    $preferences = ClientPreference::select('date_format', 'time_format')->where('id', '>', 0)->first();
    $dateFormat = (!empty($preferences->date_format)) ? $preferences->date_format : 'YYYY-MM-DD';
    $timeFormat = (!empty($preferences->time_format)) ? $preferences->time_format : '24';
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    $secondsKey = '';
    $timeFormat = '';
    if($showTime){
        if($showSeconds){
            $secondsKey = ':ss';
        }
        if($timeFormat == '12'){
            $timeFormat = ' hh:mm'.$secondsKey.' A';
        }else{
            $timeFormat = ' HH:mm'.$secondsKey;
        }
    }
    
    $format = $dateFormat . $timeFormat;
    return $date->isoFormat($format);
}
