<?php
namespace Database\Seeders;
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
              RoleSeeder::class,
              TypeSeeder::class,
              PaymentOptionSeeder::class,
              //ClientTableSeeder::class,
              LanguageTableSeeder::class,
              NotificationSeeder::class,
              //AdminsTableDataSeeder::class,
              MapProviderSeeder::class,
              SmsProviderSeeder::class,
              TemplateSeeder::class,
              PromoTypeSeeder::class,
              //CategorySeeder::class,
              //CategoryTranslationSeeder::class,
              CommonDataSeeder::class,
              BannerDataSeeder::class,
              TimezoneSeeder::class,
              PermissionSeeder::class,
            //   AddonsetDataSeeder::class,
            //   VariantSeeder::class,
            //   CatalogSeeder::class,
            //   ProductSeeder::class,

          ]);
    }
}