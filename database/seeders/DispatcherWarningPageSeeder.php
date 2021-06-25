<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\DispatcherWarningPage;

class DispatcherWarningPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = ['Design 1', 'Design 2'];
        DispatcherWarningPage::truncate();
        foreach ($array as $val) {
            DispatcherWarningPage::create(['title' => $val, 'status' => 1]);
        }
    }
}
