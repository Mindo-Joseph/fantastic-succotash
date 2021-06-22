<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use DB;
use Auth;
use URL;
use Route;
use Config;
use App\Models\Client;
use App\Models\ClientPreference;
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
    public function boot(Request $request)
    {
        if (config('app.env') != 'local') {
            \URL::forceScheme('https');
        }
        $this->connectDynamicDb($request);
        Paginator::useBootstrap();

        $favicon_url = asset('assets/images/favicon.png');
        $client_preferences = ClientPreference::where(['id' => 1])->first('favicon');
        if ($client_preferences) {
            $favicon_url = $client_preferences->favicon['proxy_url'] . '600/400' . $client_preferences->favicon['image_path'];
        }
        $client = Client::where(['id' => 1])->first();
        view()->share('company_name', ucfirst($client->company_name) ?? 'Royo');
        view()->share('favicon', $favicon_url);
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
                            $database_username = !empty($redisData->database_username) ? $redisData->database_username : env('DB_USERNAME', 'royodelivery_db');
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
}
