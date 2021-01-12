<?php

use Illuminate\Database\Seeder;

class AttributeFamilyTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('attribute_families')->delete();

        DB::table('attribute_families')->insert([
            [
                'id'              => '1',
                'code'            => 'Default',
                'name'            => 'Default',
                'status'          => '1',
                'is_user_defined' => '0',
            ]
        ]);
    }
}