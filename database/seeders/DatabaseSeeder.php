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
              LanguageTableSeeder::class,
              NotificationSeeder::class,
              MapProviderSeeder::class,
              SmsProviderSeeder::class,
              TemplateSeeder::class,
              PromoTypeSeeder::class,
              CommonDataSeeder::class,
              BannerDataSeeder::class,
              TimezoneSeeder::class,
              AppStylingSeeder::class,
              PermissionSeeder::class,
              ReturnReasonSeeder::class,
              LuxuryOptionsSeeder::class,
          ]);
        $this->call(UsersTableSeeder::class);
        $this->call(AppStylingOptionsTableSeeder::class);
    }
}