<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use App\Models\{AppStyling, AppStylingOption};

class AppStylingController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regular_font_options = [];
        $medium_font_options = [];
        $bold_font_options = [];
        $tab_style_options = [];
        $homepage_style_options = [];
        $primary_color_options = [];
        $secondary_color_options = [];
        $tertiary_color_options = [];
        $signup_tag_line_text = [];
        $regular_fonts = AppStyling::where('name', 'Regular Font')->first();
        if ($regular_fonts) {
            $regular_font_options = AppStylingOption::where('app_styling_id', $regular_fonts->id)->get();
        }
        $medium_fonts = AppStyling::where('name', 'Medium Font')->first();
        if ($medium_fonts) {
            $medium_font_options = AppStylingOption::where('app_styling_id', $medium_fonts->id)->get();
        }
        $bold_fonts = AppStyling::where('name', 'Bold Font')->first();
        if ($bold_fonts) {
            $bold_font_options = AppStylingOption::where('app_styling_id', $bold_fonts->id)->get();
        }
        $tab_style = AppStyling::where('name', 'Tab Bar Style')->first();
        if ($tab_style) {
            $tab_style_options = AppStylingOption::where('app_styling_id', $tab_style->id)->get();
        }
        $homepage_style = AppStyling::where('name', 'Home Page Style')->first();
        if ($homepage_style) {
            $homepage_style_options = AppStylingOption::where('app_styling_id', $homepage_style->id)->get();
        }
        $primary_color = AppStyling::where('name', 'Primary Color')->first();
        if ($primary_color) {
            $primary_color_options = AppStylingOption::where('app_styling_id', $primary_color->id)->first();
        }
        $secondary_color = AppStyling::where('name', 'Secondary Color')->first();
        if ($secondary_color) {
            $secondary_color_options = AppStylingOption::where('app_styling_id', $secondary_color->id)->first();
        }
        $tertiary_color = AppStyling::where('name', 'Tertiary Color')->first();
        if ($tertiary_color) {
            $tertiary_color_options = AppStylingOption::where('app_styling_id', $tertiary_color->id)->first();
        }
        $signup_tag_line = AppStyling::where('name', 'Home Tag Line')->first();
        if($signup_tag_line){
            $signup_tag_line_text = AppStylingOption::where('app_styling_id', $signup_tag_line->id)->first();
        }
        return view('backend/app_styling/index')->with(['tertiary_color_options' => $tertiary_color_options, 'secondary_color_options' => $secondary_color_options, 'primary_color_options' => $primary_color_options, 'medium_font_options' => $medium_font_options, 'bold_font_options' => $bold_font_options, 'regular_font_options' => $regular_font_options, 'tab_style_options' => $tab_style_options, 'homepage_style_options' => $homepage_style_options, 'signup_tag_line_text' => $signup_tag_line_text]);
    }
    /**
     * Store a regular font.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateFont(Request $request)
    {
        $font = AppStylingOption::where('id', $request->fonts)->first();
        $option_change = AppStylingOption::where('app_styling_id', '=', $font->app_styling_id)->update(array('is_selected' => 0));
        $font->is_selected = 1;
        $font->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully!'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateColor(Request $request)
    {
        $app_styling = AppStyling::where('name', $request->color_type.' Color')->first();
        $app_styling_option = AppStylingOption::where('app_styling_id', $app_styling->id)->first();
        if($request->color_type == "Primary"){
            $app_styling_option->name = $request->primary_color;
        }
        else if($request->color_type == "Secondary"){
            $app_styling_option->name = $request->secondary_color;
        }
        else if($request->color_type == "Tertiary"){
            $app_styling_option->name = $request->tertiary_color;
        }
        $app_styling_option->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully!'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTabBar(Request $request)
    {
        $font = AppStylingOption::where('id', $request->tab_bars)->first();
        $option_change = AppStylingOption::where('app_styling_id', '=', $font->app_styling_id)->update(array('is_selected' => 0));
        $font->is_selected = 1;
        $font->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully!'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateHomePage(Request $request)
    {
        $font = AppStylingOption::where('id', $request->home_styles)->first();
        $option_change = AppStylingOption::where('app_styling_id', '=', $font->app_styling_id)->update(array('is_selected' => 0));
        $font->is_selected = 1;
        $font->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully!'
        ]);
    }

    public function updateSignupTagLine(Request $request)
    {
        $signUpTag = AppStylingOption::find($request->id);
        $signUpTag->name = $request->updated_text;
        $signUpTag->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully!'
        ]);
    }
}
