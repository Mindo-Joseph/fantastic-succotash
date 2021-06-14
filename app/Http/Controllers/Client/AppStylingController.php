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
        $fonts = AppStyling::where('name', 'Fonts')->first();
        $font_options = AppStylingOption::where('app_styling_id', $fonts->id)->get();

        $tab_style = AppStyling::where('name', 'Tab Bar Style')->first();
        $tab_style_options = AppStylingOption::where('app_styling_id', $tab_style->id)->get();

        $homepage_style = AppStyling::where('name', 'Home Page Style')->first();
        $homepage_style_options = AppStylingOption::where('app_styling_id', $homepage_style->id)->get();

        return view('backend/app_styling/index')->with(['font_options' => $font_options, 'tab_style_options' => $tab_style_options,'homepage_style_options' => $homepage_style_options]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
