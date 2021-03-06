<?php

namespace App\Providers;

use DB;
use Auth;
use URL;
use Route;
use Config,Schema;
use App\Models\Page;
use App\Models\Client;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Models\{ClientPreference, PaymentOption};
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request){
        if (config('app.env') != 'local') {
            \URL::forceScheme('https');
        }
       $this->connectDynamicDb($request);
        Paginator::useBootstrap();
        $social_media_details = '';
        if(Schema::hasTable('social_media'))
        $social_media_details = SocialMedia::get();
        $favicon_url = asset('assets/images/favicon.png');
        $client_preference_detail = ClientPreference::where(['id' => 1])->first();
        if ($client_preference_detail) {
            $favicon_url = $client_preference_detail->favicon['proxy_url'] . '600/400' . $client_preference_detail->favicon['image_path'];
        }
        $client_head = Client::where(['id' => 1])->first();

        $payment_codes = ['stripe', 'yoco', 'checkout'];
        $stripe_publishable_key = $yoco_public_key = $checkout_public_key = '';
        $payment_options = PaymentOption::select('code','credentials')->whereIn('code', $payment_codes)->where('status', 1)->get();
        if($payment_options){
            foreach($payment_options as $option){
                $creds = json_decode($option->credentials);
                if($option->code == 'stripe'){
                    $stripe_publishable_key = (isset($creds->publishable_key) && (!empty($creds->publishable_key))) ? $creds->publishable_key : '';
                }
                if($option->code == 'yoco'){
                    $yoco_public_key = (isset($creds->public_key) && (!empty($creds->public_key))) ? $creds->public_key : '';
                }
                if($option->code == 'checkout'){
                    $checkout_public_key = (isset($creds->public_key) && (!empty($creds->public_key))) ? $creds->public_key : '';
                }
            }
        }

        $count = 0;
        if($client_preference_detail){
            if($client_preference_detail->dinein_check == 1){$count++;}
            if($client_preference_detail->takeaway_check == 1){$count++;}
            if($client_preference_detail->delivery_check == 1){$count++;}
        }
        
        $last_mile_common_set = $this->checkIfLastMileDeliveryOn();
        $client_payment_options = PaymentOption::where('status', 1)->pluck('code')->toArray();


        view()->share('last_mile_common_set', $last_mile_common_set);
       
        view()->share('favicon', $favicon_url);
        view()->share('client_head', $client_head);
        view()->share('mod_count', $count);
        view()->share('social_media_details', $social_media_details);
        view()->share('stripe_publishable_key', $stripe_publishable_key);
        view()->share('yoco_public_key', $yoco_public_key);
        view()->share('checkout_public_key', $checkout_public_key);
        view()->share('client_preference_detail', $client_preference_detail);
        view()->share('client_payment_options', $client_payment_options);
       
       
    }

    public function connectDynamicDb($request)
    {
        if (strpos(URL::current(), '/api/') !== false) {
        } else {
            $domain = $request->getHost();
            $domain = str_replace(array('http://', '.test.com/login'), '', $domain);
            $subDomain = explode('.', $domain);
            $existRedis = Redis::get($domain);
            
            if ($domain != env('Main_Domain')) {
                
                if (!$existRedis) {
                    $client = Client::select('name', 'email', 'phone_number', 'is_deleted', 'is_blocked', 'logo', 'company_name', 'company_address', 'status', 'code', 'database_name', 'database_host', 'database_port', 'database_username', 'database_password', 'custom_domain', 'sub_domain')
                        ->where(function ($q) use ($domain, $subDomain) {
                            $q->where('custom_domain', $domain)
                                ->orWhere('sub_domain', $subDomain[0]);
                        })
                        ->first();


                    if ($client) {
                        Redis::set($domain, json_encode($client->toArray()), 'EX', 36000);
                        $existRedis = Redis::get($domain);
                    }
                }

                $callback = '';
                $dbname = DB::connection()->getDatabaseName();
                $redisData = json_decode($existRedis);

                if ($redisData) {
                    if ($domain != env('Main_Domain')) {
                        if ($redisData && $dbname != 'royo_' . $redisData->database_name) {
                            $database_name = 'royo_' . $redisData->database_name;
                            $database_host = !empty($redisData->database_host) ? $redisData->database_host : env('DB_HOST', '127.0.0.1');
                            $database_port = !empty($redisData->database_port) ? $redisData->database_port : env('DB_PORT', '3306');
                            $database_username = !empty($redisData->database_username) ? $redisData->database_username : env('DB_USERNAME', 'royoorders');
                            $database_password = !empty($redisData->database_password) ? $redisData->database_password : env('DB_PASSWORD', '');
                            $default = [
                                'driver' => env('DB_CONNECTION', 'mysql'),
                                'host' => $database_host,
                                'port' => $database_port,
                                'database' => $database_name,
                                'username' => $database_username,
                                'password' => $database_password,
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
                            DB::setDefaultConnection($database_name);
                            DB::purge($database_name);
                            $dbname = DB::connection()->getDatabaseName();
                        }
                    }
                }

               
            }   
        }
    }

    public function checkIfLastMileDeliveryOn()
    {
        $preference = ClientPreference::first();
        if (isset($preference) && Schema::hasColumn('client_preferences', 'need_delivery_service') && Schema::hasColumn('client_preferences', 'delivery_service_key_url')  && Schema::hasColumn('client_preferences', 'delivery_service_key_code')  ) {
            if($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
            else
            return false;
        }
        return false;
       
    }
}
