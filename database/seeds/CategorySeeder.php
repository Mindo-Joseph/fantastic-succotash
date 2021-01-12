<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
 
        $maps = array(
            array(
                'slug' => 'root',
                'type' => 'product',
                'is_visible' => 0,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1
            ),
        ); 
        DB::table('categories')->insert($maps);
    }
}


