<?php

namespace Database\Seeders;

use App\Models\{AppStyling,AppStylingOption};
use Illuminate\Database\Seeder;

class AppStylingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $app_styling = AppStyling::insertGetId([
            'name' => 'Fonts',
            'type' => '2'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Roboto',
            'is_selected' => '1'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Futura',
            'is_selected' => '0'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Helvetica',
            'is_selected' => '0'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Raleway',
            'is_selected' => '0'
        ]);

        $app_styling = AppStyling::insertGetId([
            'name' => 'Color',
            'type' => '4'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => '#fff',
            'is_selected' => '1'
        ]);

        $app_styling = AppStyling::insertGetId([
            'name' => 'Tab Bar Style',
            'type' => '3'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Tab 1',
            'image' => 'https://cdn.dribbble.com/users/1229051/screenshots/15168619/media/df50d958c7e13b9f8d5bacc9bf43a05e.gif',
            'is_selected' => '1'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Tab 2',
            'image' => 'https://cdn.dribbble.com/users/1229051/screenshots/9325107/media/7a9f86f2d92541ecf49ec81ff9d53fa0.gif',
            'is_selected' => '0'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Tab 3',
            'image' => 'https://cdn.dribbble.com/users/1229051/screenshots/9713873/media/60009de2d6179cee8835f34de04c2e54.gif',
            'is_selected' => '0'
        ]);

        $app_styling = AppStyling::insertGetId([
            'name' => 'Home Page Style',
            'type' => '3'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Home Page 1',
            'image' => 'https://cdn.dribbble.com/users/2878111/screenshots/15265330/media/94ed25cc0e51db948afbd8319cd8d655.jpg?compress=1&resize=1200x900',
            'is_selected' => '1'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Home Page 2',
            'image' => 'https://cdn.dribbble.com/users/472201/screenshots/15710485/media/8fb413b8ed159986278b341cbbeb87d4.jpg?compress=1&resize=1200x900',
            'is_selected' => '0'
        ]);

        $app_styling_option = AppStylingOption::insert([
            'app_styling_id' => $app_styling,
            'name' => 'Home Page 3',
            'image' => 'https://cdn.dribbble.com/users/472201/screenshots/14127485/media/ac90950e126cdd3128936d65f1e1a9a4.jpg?compress=1&resize=1200x900',
            'is_selected' => '0'
        ]);
    }
}
