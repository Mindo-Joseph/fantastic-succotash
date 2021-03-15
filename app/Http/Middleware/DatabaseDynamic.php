<?php

namespace App\Http\Middleware;

use App\Models\{Client, ClientPreference};
use Closure;
use Config;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client as TwilioC;
use Illuminate\Support\Facades\View;

class DatabaseDynamic
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
        /*$domain = $request->getHost();
        $subDomain = explode('.', $domain);
        print_r($subDomain);
        $tenant = Client::where('database_name', $subDomain[0])->first();
        dd($tenant->toArray());*/

        if(Auth::check()){
            
          $client = Auth::user();
           if($client){
              $database_name = 'royo_'.$client->database_name;
              $default = [
                  'driver' => env('DB_CONNECTION','mysql'),
                  'host' => $client->database_host,
                  'port' => $client->database_port,
                  'database' => $database_name,
                  'username' => $client->database_username,
                  'password' => $client->database_password,
                  'charset' => 'utf8mb4',
                  'collation' => 'utf8mb4_unicode_ci',
                  'prefix' => '',
                  'prefix_indexes' => true,
                  'strict' => false,
                  'engine' => null
              ];
              Config::set("database.connections.$database_name", $default);
              Config::set("client_id",1);
              Config::set("client_connected",true);
              Config::set("client_data",$client);
              DB::setDefaultConnection($database_name);
              DB::purge($database_name);

              $clientPreference = ClientPreference::where('client_code',Auth::user()->code)->first();

              Session::put('login_user_type', 'client');

              if(isset($clientPreference)){
                $agentTitle = empty($clientPreference->agent_name) ? 'Agent' : $clientPreference->agent_name;
                Session::put('agent_name', $agentTitle);
                Session::put('preferences', $clientPreference->toArray());

              }else{
                Session::put('agent_name', 'Agent');
                Session::put('preferences', '');
              }
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
