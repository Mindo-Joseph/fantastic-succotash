<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
              CurrencyTableSeeder::class,
              CountryTableSeeder::class,
              //ClientTableSeeder::class,
              LanguageTableSeeder::class,
              NotificationSeeder::class,
              //AdminsTableDataSeeder::class,
              MapProviderSeeder::class,
              SmsProviderSeeder::class,
              TemplateSeeder::class,

          ]);
    }
}
