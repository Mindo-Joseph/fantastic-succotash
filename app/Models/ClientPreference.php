<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPreference extends Model
{
    protected $fillable = ['client_code', 'theme_admin', 'distance_unit', 'currency_id', 'language_id', 'date_format', 'time_format', 'fb_client_id', 'fb_client_secret', 'fb_client_url', 'twitter_client_id', 'twitter_client_secret', 'twitter_client_url', 'google_client_id', 'google_client_secret', 'google_client_url', 'apple_client_id', 'apple_client_secret', 'apple_client_url', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'map_provider', 'map_key', 'map_secret', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'verify_email', 'verify_phone', 'web_template_id', 'app_template_id', 'is_hyperlocal', 'need_delivery_service', 'dispatcher_key_1', 'dispatcher_key_2'];

    public function filling(){
    	$filling = ['theme_admin', 'distance_unit', 'currency_id', 'date_format', 'time_format', 'fb_client_id', 'fb_client_secret', 'fb_client_url', 'twitter_client_id', 'twitter_client_secret', 'twitter_client_url', 'google_client_id', 'google_client_secret', 'google_client_url', 'apple_client_id', 'apple_client_secret', 'apple_client_url', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'map_provider', 'map_key', 'map_secret', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'verify_email', 'verify_phone', 'web_template_id', 'app_template_id'];

    	return $filling;
    }

    public function language()
    {
      return $this->hasMany('App\Models\ClientLanguage','client_code','client_code')->select( 'client_code', 'language_id');
    }

    public function domain()
    {
      return $this->belongsTo('App\Models\Client','client_code','code')->select('id', 'code', 'custom_domain');
    }
 
}
/*
'', 'distance_unit', 'currency_id', 'date_format', 'time_format', 'fb_client_id', 'fb_client_secret', 'fb_client_url', 'twitter_client_id', 'twitter_client_secret', 'twitter_client_url', 'google_client_id', 'google_client_secret', 'google_client_url', 'apple_client_id', 'apple_client_secret', 'apple_client_url', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'map_provider', 'map_key', 'map_secret', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'verify_email', 'verify_phone', '', ''
*/