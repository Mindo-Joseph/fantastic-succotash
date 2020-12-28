<?php

use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('templates')->delete();
 
        $maps = array(
            array(
                'id' => 1,
                'name' => 'default',
                'image' => 'default',
                'for' => '1'
            ),
            array(
                'id' => 2,
                'name' => 'default',
                'image' => 'default',
                'for' => '2'
            ),
        ); 
        DB::table('templates')->insert($maps);
    }
}
