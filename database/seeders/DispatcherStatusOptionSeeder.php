<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DispatcherStatusOption;

class DispatcherStatusOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['Created', 'Assigned', 'Started', 'Arrived', 'Completed'];
        // OrderStatusOption::truncate();
        foreach ($statuses as $status) {
	        DispatcherStatusOption::create(['title' => $status, 'status' => 1, 'type' => 1]);
        }
    }
}
