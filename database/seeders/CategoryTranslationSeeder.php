<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class CategoryTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('category_translations')->delete();
 
        $maps = array(
            array(
                'id' => 1,
                'name' => 'root',
                'category_id' => 1,
                'language_id' => 1,
            )
        ); 
        \DB::table('category_translations')->insert($maps);
    }
}
