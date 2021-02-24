<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { /* Work for product and category */
        \DB::table('types')->delete();
 
        $maps = array(
            ['id' => 1,
                'title' => 'Product',
            ],
            ['id' => 2,
                'title' => 'Dispatcher',
            ],
                       
        ); 
        \DB::table('types')->insert($maps);
    }
}


