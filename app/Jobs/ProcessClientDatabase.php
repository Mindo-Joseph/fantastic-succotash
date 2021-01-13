<?php

namespace App\Jobs;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Config;
use Exception;
use Illuminate\Support\Facades\Artisan;

class ProcessClientDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $client_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::where('id', $this->client_id)->first(['name', 'email', 'password', 'phone_number', 'password', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain', 'status', 'code', 'country_id', 'timezone'])->toarray();
        try {
           
            $schemaName = 'royo_' . $client['database_name'] ?: config("database.connections.mysql.database");
            $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => $schemaName,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];

            $settings = [
                'client_code'           => $client['code'],
                'theme_admin'           => 'light',
                'distance_unit'         => 'metric',
                'currency_id'           => 147,
                'language_id'           => 1,
                'date_format'           => 'YYYY-MM-DD',
                'time_format'           => '24',
                'fb_login'              => 0,
                'twitter_login'         => 0,
                'google_login'          => 0,
                'apple_login'           => 0,
                'is_hyperlocal'         => 1,
                'Default_location_name' => 'Chandigarh, Punjab, India',
                'Default_latitude'      =>'30.53899440',
                'Default_longitude'     =>'75.95503290',
                'map_provider'          => 1,
                'sms_provider'          => 1,
                'verify_email'          => 0,
                'verify_phone'          => 0,
                'web_template_id'       => 1,
                'app_template_id'       => 2,
                'need_delivery_service' => 0
            ];

            $cl = [
                'client_code'           => $client['code'],
                'language_id'           => '1',

            $query = "CREATE DATABASE $schemaName;";

            DB::statement($query);

            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            Artisan::call('migrate', ['--database' => $schemaName]);
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--database' => $schemaName]);
            DB::connection($schemaName)->table('clients')->insert($client);
            DB::connection($schemaName)->table('client_preferences')->insert($settings);
            DB::connection($schemaName)->table('client_languages')->insert($cl);

            DB::disconnect($schemaName);
        } catch (Exception $ex) {
           return $ex->getMessage();
        }
    }
}
