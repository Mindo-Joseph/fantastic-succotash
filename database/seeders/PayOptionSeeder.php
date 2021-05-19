<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PayOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_options')->delete();
 
        $opt = array(
            array(
                'code' => 'COD',
                'title' => 'Cash On Delivery',
                'status' => '1'
            ),
            array(
                'code' => 'wallet',
                'title' => 'Wallet',
                'status' => '1'
            ),
            array(
                'code' => 'layalty-points',
                'title' => 'Layalty Points',
                'status' => '1'
            ),
        ); 
        \DB::table('payment_options')->insert($opt);
    }
}