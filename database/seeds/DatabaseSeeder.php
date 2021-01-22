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
              TypeSeeder::class,
              RoleSeeder::class,
              //ClientTableSeeder::class,
              LanguageTableSeeder::class,
              NotificationSeeder::class,
              //AdminsTableDataSeeder::class,
              MapProviderSeeder::class,
              SmsProviderSeeder::class,
              TemplateSeeder::class,
              CategorySeeder::class,
              CategoryTranslationSeeder::class,

              /*AttributeFamilyTableSeeder::class,
              AttributeGroupTableSeeder::class,
              AttributeTableSeeder::class,
              AttributeOptionTableSeeder::class,*/


          ]);
    }
}
