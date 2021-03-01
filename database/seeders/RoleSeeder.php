<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();
 
        $maps = array(
            array(
                'id' => 1,
                'role' => 'vendor',
                'status' => '1'
            ),
            array(
                'id' => 2,
                'role' => 'customer',
                'status' => '1'
            ),
        ); 
        \DB::table('roles')->insert($maps);

        /* Work for product and category */
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
