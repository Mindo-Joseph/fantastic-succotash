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
use \Spatie\DbDumper\Databases\MySql;

class ClientDatabaseToDevMaster implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $client; 
    protected $dumpinto; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client,$dumpinto)
    {
        $this->client = $client;
        $this->dumpinto = $dumpinto;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::where('id', $this->client)->first(['name', 'email', 'password', 'phone_number', 'database_host','database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain', 'status', 'code', 'country_id', 'sub_domain'])->toarray();
        $clientData = array();

           
        try {
            $databaseNameSet = 'royo_'.$client['database_name'];
            $db_name_set = $databaseNameSet.'.sql';
            \Spatie\DbDumper\Databases\MySql::create()
                ->setDbName($databaseNameSet)
                ->setUserName($client['database_username'])
                ->setPassword($client['database_password'])
                ->setHost($client['database_host'])
                ->dumpToFile($db_name_set);

            dd($databaseNameSet) ;   
            
            $schemaName = 'ab_royo_' . $client['database_name'] ?: config("database.connections.mysql.database");
            $dumpinto = $this->dumpinto;

                $database_host_dev = env('DB_HOST_DEV', 'royoorders-2-db-development-cluster.cvgfslznkneq.us-west-2.rds.amazonaws.com');
                $database_port_dev = env('DB_PORT_DEV', '3306');
                $database_username_dev = env('DB_USERNAME_DEV', 'cbladmin');
                $database_password_dev =  env('DB_PASSWORD_DEV', 'aQ2hvKYLH4LKWmrA');

                $default = [
                'driver' => env('DB_CONNECTION_DEV', 'mysql'),
                'host' => $database_host_dev,
                'port' => $database_port_dev,
                'database' => $schemaName,
                'username' => $database_username_dev,
                'password' => $database_password_dev,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
                ];

                $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
                $db = DB::connection('dev')->select($query, [$schemaName]);
                if ($db) {
                    dd('exist');
                }else{
                    $query = "CREATE DATABASE $schemaName;";
                    DB::connection('dev')->statement($query);
                }

               
                
                dd($db_name_set) ;
                
           
                Config::set("database.connections.$schemaName", $default);
                config(["database.connections.mysql.database" => $schemaName]);
            
                DB::connection($schemaName)->beginTransaction();
                DB::connection($schemaName)->statement("SET foreign_key_checks=0");

           

           

            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
         
            DB::connection($schemaName)->table('clients')->insert($clientData);
          
            DB::disconnect($schemaName);
        } catch (Exception $ex) {
            print_r($ex->getMessage());die;
           return $ex->getMessage();

        }
    }
}
