<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Models\{Client, ClientPreference, ClientLanguage, ClientCurrency};
use Config;
use Cache;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CustomDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $domain = $request->getHost();
      $domain = str_replace(array('http://', '.test.com/login'), '', $domain);
      $subDomain = explode('.', $domain);
      $existRedis = Redis::get($domain);

      //$callback = http://local.myorder.com/auth/facebook/callback
      

      if(!$existRedis){
        $client = Client::select('name', 'email', 'phone_number', 'is_deleted', 'is_blocked', 'logo', 'company_name', 'company_address', 'status', 'code', 'database_name', 'database_host', 'database_port', 'database_username', 'database_password', 'custom_domain', 'sub_domain')
                    ->where(function($q) use($domain, $subDomain){
                              $q->where('custom_domain', $domain)
                                ->orWhere('sub_domain', $subDomain[0]);
                                //->orWhere('database_name', $subDomain[0]);
                    })
                    ->firstOrFail();

        Redis::set($domain, json_encode($client->toArray()), 'EX', 36000);

        $existRedis = Redis::get($domain);
      }
      $callback = '';

      $redisData = json_decode($existRedis);
      //echo '<pre>';print_r($redisData);

      if($redisData){
          $database_name = 'royo_'.$redisData->database_name;
          $database_host = !empty($redisData->database_host) ? $redisData->database_host : '127.0.0.1';
          $database_port = !empty($redisData->database_port) ? $redisData->database_port : '3306';
          $default = [
              'driver' => env('DB_CONNECTION','mysql'),
              'host' => $redisData->database_host,
              'port' => $redisData->database_port,
              'database' => $database_name,
              'username' => $redisData->database_username,
              'password' => $redisData->database_password,
              'charset' => 'utf8mb4',
              'collation' => 'utf8mb4_unicode_ci',
              'prefix' => '',
              'prefix_indexes' => true,
              'strict' => false,
              'engine' => null
          ];
          Config::set("database.connections.$database_name", $default);
          Config::set("client_id", 1);
          Config::set("client_connected", true);
          Config::set("client_data", $redisData);
          DB::setDefaultConnection($database_name);
          DB::purge($database_name);

          if(!empty($redisData->custom_domain)){
            $domain = rtrim($redisData->custom_domain, "/");
            $domain = ltrim($domain, "https://");
            $callback = "https://".$domain.'/auth/facebook/callback';
          }else{
            $sub_domain = rtrim($redisData->sub_domain, "/");
            $sub_domain = ltrim($sub_domain, "https://");
            $callback = "https://".$sub_domain.".royoorders.com/auth/facebook/callback";
          }

          /*session()->forget('session_lang_id');
          session()->forget('session_cur_id');
          session()->flush();*/

          $clientPreference = ClientPreference::select('theme_admin', 'distance_unit', 'currency_id', 'date_format', 'time_format', 'fb_login', 'fb_client_id', 'fb_client_secret', 'fb_client_url', 'twitter_login', 'twitter_client_id', 'twitter_client_secret', 'twitter_client_url', 'google_login', 'google_client_id', 'google_client_secret', 'google_client_url', 'apple_login', 'apple_client_id', 'apple_client_secret', 'apple_client_url', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'map_provider', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'web_template_id', 'is_hyperlocal', 'need_delivery_service', 'need_dispacher_ride', 'delivery_service_key', 'dispatcher_key', 'primary_color', 'secondary_color')->where('client_code', $redisData->code)->first();

          Config::set('FACEBOOK_CLIENT_ID', $clientPreference->fb_client_id);
          Config::set('FACEBOOK_CLIENT_SECRET', $clientPreference->fb_client_secret);
          Config::set('FACEBOOK_CALLBACK_URL', $callback);


          Session::put('client_config', $redisData);
          Session::put('login_user_type', 'client');

          if (!session()->has('customerLanguage') || empty(session()->get('customerLanguage'))){

              $primeLang = ClientLanguage::select('language_id', 'is_primary')->where('is_primary', 1)->first();
              Session::put('customerLanguage', $primeLang->language_id);
          }
          if (!session()->has('customerCurrency') || empty(session()->get('customerCurrency'))){

              $primeCurcy = ClientCurrency::join('currencies as cu', 'cu.id', 'client_currencies.currency_id')
                    ->where('client_currencies.is_primary', 1)->first();

              Session::put('customerCurrency', $primeCurcy->currency_id);
              Session::put('currencySymbol', $primeCurcy->symbol);
              Session::put('currencyMultiplier', $primeCurcy->doller_compare);
          }

          $preferData = array();

          if(isset($clientPreference)){
            $preferData = $clientPreference;
          }

          Session::put('preferences', $preferData);

      }else{
        return redirect()->route('error_404');
      }
      
      return $next($request);
    }
}