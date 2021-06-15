<?php

namespace Database\Seeders;
use App\Models\OrderStatusOption;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['Placed', 'Accepted', 'Processing', 'Out For Delivery', 'Delivered'];
        // OrderStatusOption::truncate();
        foreach ($statuses as $status) {
	        OrderStatusOption::create(['title' => $status, 'status' => 1, 'type' => 1]);
        }
    }
}
