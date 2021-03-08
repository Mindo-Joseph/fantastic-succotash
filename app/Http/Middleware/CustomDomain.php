<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Models\{Client, ClientPreference};
use Config;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    /*public function handle($request, Closure $next)
    {
        if (!$tenant) {
            $adminDomain = config('app.admin_domain');

            if ($domain != $adminDomain) {
                abort(404);
            }
        }

        // Append domain and tenant to the Request object
        // for easy retrieval in the application.
        $request->merge([
            'domain' => $domain,
            'tenant' => $tenant
        ]);

        if ($tenant) {
            View::share('tenantColor', $tenant->color);
            View::share('tenantName', $tenant->name);
        }

        return $next($request);
    }*/

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
        $subDomain = explode('.', $domain);
        print_r($subDomain);
        $client = Client::where('database_name', $subDomain[0])->first();
        dd($client->toArray());

            
           if($client){
              $database_name = 'royo_'.$client->database_name;
              $default = [
                  'driver' => env('DB_CONNECTION','mysql'),
                  'host' => env('DB_HOST'),
                  'port' => env('DB_PORT'),
                  'database' => $database_name,
                  'username' => env('DB_USERNAME'),
                  'password' => env('DB_PASSWORD'),
                  'charset' => 'utf8mb4',
                  'collation' => 'utf8mb4_unicode_ci',
                  'prefix' => '',
                  'prefix_indexes' => true,
                  'strict' => false,
                  'engine' => null
              ];
              Config::set("database.connections.$database_name", $default);
              Config::set("client_id", 1);
              Config::set("client_connected",true);
              Config::set("client_data",$client);
              DB::setDefaultConnection($database_name);
              DB::purge($database_name);

              $clientPreference = ClientPreference::where('client_code', $client->code)->first();

              Session::put('login_user_type', 'client');

              $preferData = array();


              if(isset($clientPreference)){
                $preferData = $clientPreference->toArray();
              }
              Session::put('preferences', $preferData);
             // dd($clientPreference->toArray());

              if($clientPreference){

                if(!empty($clientPreference->sms_provider_key_1) && !empty($clientPreference->sms_provider_key_2)){

                  $token = $clientPreference->sms_provider_key_1;
                  $sid = $clientPreference->sms_provider_key_2;
                  $twilio = new TwilioC($sid, $token);
                  try {
                    $account = $twilio->api->v2010->accounts($sid)->fetch();

                    Session::put('twilio_status', $account->status);

                  } catch (\Exception $e) {
                      Session::put('twilio_status', 'invalid_key');
                  }

                }else{
                  Session::put('twilio_status', 'null_key');
                }

              }

              //Session::put('testImage', url('profileImg'));
          }
      }
        return $next($request);
    }
}