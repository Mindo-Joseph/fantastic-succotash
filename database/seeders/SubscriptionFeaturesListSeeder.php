<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubscriptionFeaturesListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('subscription_features_list')->delete();
 
        $features = array(
            array(
                'id' => 1,
                'title' => 'Free Delivery',
                'Description' => '',
                'type' => 'User',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 2,
                'title' => 'Trending',
                'Description' => '',
                'type' => 'Vendor',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            )
        ); 
        \DB::table('subscription_features_list')->insert($features);
    }
}
