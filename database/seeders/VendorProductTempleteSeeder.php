<?php
namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;

class VendorProductTempleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vendor_templetes')->delete();
        $vendor_templete_array = array(
            ['id' => 1,
                'title' => 'Product',
                'type' => 'Grid',
                'status' =>'1'
            ],
            ['id' => 2,
                'title' => 'Category',
                'type' => 'Grid',
                'status' =>'1'
            ],
            ['id' => 3,
                'title' => 'Product',
                'type' => 'List',
                'status' =>'0'
            ],
            ['id' => 4,
                'title' => 'Category',
                'type' => 'List',
                'status' =>'0'
            ],
        ); 
        DB::table('vendor_templetes')->insert($vendor_templete_array);
    }
}
