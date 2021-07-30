<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomePageLabel;
use DB;

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
        DB::table('home_page_labels')->truncate();    
        DB::table('home_page_label_transaltions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');   
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Featured Vendors',
        ]);
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Vendors',
        ]);
        $home_page = HomePageLabel::insertGetId([
            'title' => 'New Products',
        ]);
        $home_page = HomePageLabel::insertGetId([
            'title' => 'On Sale',
        ]);
        $home_page = HomePageLabel::insertGetId([
            'title' => 'Brands',
        ]);
    }
}
