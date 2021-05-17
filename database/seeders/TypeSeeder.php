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
    {
        \DB::table('types')->delete();
 
        $maps = array(
            ['id' => 1,
                'title' => 'Product',
            ],
            ['id' => 2,
                'title' => 'Dispatcher',
            ],
            ['id' => 3,
                'title' => 'Vendor',
            ],
            ['id' => 4,
                'title' => 'Brand',
            ],
            ['id' => 5,
                'title' => 'Celebrity',
            ],
        ); 
        \DB::table('types')->insert($maps);
    }
}
