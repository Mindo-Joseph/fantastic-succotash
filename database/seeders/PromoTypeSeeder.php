<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PromoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('promo_types')->delete();
 
        $maps = array(
            array(
                'id' => 1,
                'title' => 'Voucher',
                'status' => '1'
            ),
            array(
                'id' => 2,
                'title' => 'Percentage Discount',
                'status' => '1'
            ),
            array(
                'id' => 3,
                'title' => 'Amount Discount',
                'status' => '1'
            ),
            array(
                'id' => 4,
                'title' => 'Cashback',
                'status' => '1'
            ),
            array(
                'id' => 5,
                'title' => 'Offer Free Product',
                'status' => '1'
            ),
        ); 
        \DB::table('promo_types')->insert($maps);
    }
}