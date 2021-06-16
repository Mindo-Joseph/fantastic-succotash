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
        $font_options =[];
        $tab_style_options =[];
        $homepage_style_options =[];
        $fonts = AppStyling::where('name', 'Fonts')->first();
        if($fonts){
            $font_options = AppStylingOption::where('app_styling_id', $fonts->id)->get();
        }
        $tab_style = AppStyling::where('name', 'Tab Bar Style')->first();
        if($tab_style){
            $tab_style_options = AppStylingOption::where('app_styling_id', $tab_style->id)->get();
        }
        $homepage_style = AppStyling::where('name', 'Home Page Style')->first();
        if($homepage_style){
            $homepage_style_options = AppStylingOption::where('app_styling_id', $homepage_style->id)->get();
        }

        $color = AppStyling::where('name', 'Color')->first();
        if($color){
            $color_options = AppStylingOption::where('app_styling_id', $color->id)->first();
        }
        return view('backend/app_styling/index')->with(['color_options' => $color_options, 'font_options' => $font_options, 'tab_style_options' => $tab_style_options,'homepage_style_options' => $homepage_style_options]);
    }
    /**
     * Store a newly created resource in storage.
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
        $app_styling = AppStyling::where('name', 'Color')->first();
        $app_styling_option = AppStylingOption::where('app_styling_id', $app_styling->id)->first();
        $app_styling_option->name = $request->secondary_color;
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
}
