<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\DispatcherTemplateTypeOption;

class DispatcherTemplateTypeOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $array = ['Pickup & Delivery', 'Cab Booking'];
        DispatcherTemplateTypeOption::truncate();
        foreach ($array as $val) {
            DispatcherTemplateTypeOption::create(['title' => $val, 'status' => 1]);
        }
    }
}
