<?php

namespace Database\Seeders;
use App\Models\OrderStatus;
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
        OrderStatus::truncate();
        foreach ($statuses as $status) {
	        OrderStatus::create(['title' => $status, 'status' => 1, 'type' => 1]);
        }
    }
}
