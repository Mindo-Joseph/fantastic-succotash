<?php

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
        DB::table('types')->delete();
 
        $maps = array(
            ['id' => 1,
                'title' => 'product',
            ],
            ['id' => 2,
                'title' => 'service',
            ],
            ['id' => 3,
                'title' => 'rental',
            ],            
        ); 
        DB::table('types')->insert($maps);
    }
}


