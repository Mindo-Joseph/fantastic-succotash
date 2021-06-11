<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use DB;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
 
        $type = array(
            array(
                'id' => 1,
                'name' => 'DASHBOARD',
                'slug' => 'dashboard'
            ),
            array(
                'id' => 2,
                'name' => 'ORDERS',
                'slug' => 'order'
            ),
            array(
                'id' => 3,
                'name' => 'VENDORS',
                'slug' => 'vendor'
            ),
            array(
                'id' => 4,
                'name' => 'CUSTOMERS',
                'slug' => 'customer'
            ),
            array(
                'id' => 5,
                'name' => 'Profile',
                'slug' => 'profile'
            ),
            array(
                'id' => 6,
                'name' => 'CUSTOMIZE',
                'slug' => 'customize'
            ),
            array(
                'id' => 7,
                'name' => 'CONFIGURATIONS',
                'slug' => 'configure'
            ),
            array(
                'id' => 8,
                'name' => 'BANNER',
                'slug' => 'banner'
            ),
            array(
                'id' => 9,
                'name' => 'CATALOG',
                'slug' => 'category'
            ),
            array(
                'id' => 10,
                'name' => 'TAX',
                'slug' => 'tax'
            ),
            array(
                'id' => 11,
                'name' => 'PAYMENT',
                'slug' => 'payoption'
            ),
            array(
                'id' => 12,
                'name' => 'PROMOCODE',
                'slug' => 'promocode'
            ),
            array(
                'id' => 13,
                'name' => 'LOYALTY CARDS',
                'slug' => 'loyalty'
            ),
            array(
                'id' => 14,
                'name' => 'CELEBRITY',
                'slug' => 'celebrity'
            )
        );
        DB::table('permissions')->insert($type);
    }
}
