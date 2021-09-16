<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomePageLabel;
use DB;
use Log;
class HomePageLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');  

        $already = HomePageLabel::where('slug', 'featured_products')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Featured Products',
            'slug' => 'featured_products',
            'order_by' => 1,
        ]);

        $already = HomePageLabel::where('slug', 'vendors')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Vendors',
            'slug' => 'vendors',
            'order_by' => 2,
        ]);

        $already = HomePageLabel::where('slug', 'new_products')->count();

        if($already == 0){
            Log::info($already);
            $home_page = HomePageLabel::insertGetId([
                'title' => 'New Products',
                'slug' => 'new_products',
                'order_by' => 3,
            ]);
        }
      

        $already = HomePageLabel::where('slug', 'on_sale')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'On Sale',
            'slug' => 'on_sale',
            'order_by' => 4,
        ]);

        $already = HomePageLabel::where('slug', 'brands')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Brands',
            'slug' => 'brands',
            'order_by' => 5,
        ]);

        $already = HomePageLabel::where('slug', 'best_sellers')->count();

        if($already == 0)
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Best Sellers',
            'slug' => 'best_sellers',
            'order_by' => 6,
        ]);

        $already = HomePageLabel::where('slug', 'cab_booking')->count();

        if($already == 0){
            $del = HomePageLabel::where('slug', 'pickup_delivery')->delete();
            $home_page = HomePageLabel::insertGetId([
                'id' => 8,
                'title' => 'Picup Delivery',
                'slug' => 'pickup_delivery',
                'order_by' => 7,
                'is_active' => 0
            ]);
         
        }
        else{
            $del = HomePageLabel::where('slug', 'cab_booking')->delete();
            $del = HomePageLabel::where('slug', 'pickup_delivery')->delete();
            $home_page = HomePageLabel::where('id', $already->id)->update([
                'id' => 8,
                 'title' => 'Picup Delivery',
                'slug' => 'pickup_delivery',
                'order_by' => 7,
                'is_active' => 0
            ]);
        }   
       
    }
}
