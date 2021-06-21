<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppStyling extends Model
{
    use HasFactory;

    public function getSelectedData()
    {
        $data = array();

        $tab_template = AppStyling::where('name', 'Tab Bar Style')->first();
        $tab_template_id = AppStylingOption::where('app_styling_id', $tab_template->id)->where('is_selected', 1)->first();
        $arr["tab_bar_template_id"] = $tab_template_id->template_id;

        $homepage_template = AppStyling::where('name', 'Home Page Style')->first();
        $homepage_template_id = AppStylingOption::where('app_styling_id', $homepage_template->id)->where('is_selected', 1)->first();
        $arr["home_page_template_id"] = $homepage_template_id->template_id;

        $regular_font = AppStyling::where('name', 'Regular Font')->first();
        $regular_font_name = AppStylingOption::where('app_styling_id', $regular_font->id)->where('is_selected', 1)->first();
        $fonts["regular_font_name"] = $regular_font_name->name;

        $medium_font = AppStyling::where('name', 'Medium Font')->first();
        $medium_font_name = AppStylingOption::where('app_styling_id', $medium_font->id)->where('is_selected', 1)->first();
        $fonts["medium_font_name"] = $medium_font_name->name;

        $bold_font = AppStyling::where('name', 'Bold Font')->first();
        $bold_font_name = AppStylingOption::where('app_styling_id', $bold_font->id)->where('is_selected', 1)->first();
        $fonts["bold_font_name"] = $bold_font_name->name;

        $primary_color = AppStyling::where('name', 'Primary Color')->first();
        $primary_color_code = AppStylingOption::where('app_styling_id', $primary_color->id)->where('is_selected', 1)->first();
        $colors["primary_color"] = $primary_color_code->name;

        $secondary_color = AppStyling::where('name', 'Secondary Color')->first();
        $secondary_color_code = AppStylingOption::where('app_styling_id', $secondary_color->id)->where('is_selected', 1)->first();
        $colors["secondary_color"] = $secondary_color_code->name;

        $tertiary_color = AppStyling::where('name', 'Tertiary Color')->first();
        $tertiary_color_code = AppStylingOption::where('app_styling_id', $tertiary_color->id)->where('is_selected', 1)->first();
        $colors["tertiary_color"] = $tertiary_color_code->name;

        array_push($arr, $fonts);
        array_push($arr, $colors);
        array_push($data, $arr);
        return $data;
    }
}
