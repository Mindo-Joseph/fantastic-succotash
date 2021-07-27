<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\ClientPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebStylingController extends BaseController{
    //
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $client_preferences = ClientPreference::first();
        return view('backend/web_styling/index')->with(['client_preferences' => $client_preferences]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWebStyles(Request $request){
        $client_preferences = ClientPreference::first();
        if($client_preferences){
            if($request->has('favicon')){
                $client_preferences->favicon = Storage::disk('s3')->put('favicon', $request->favicon, 'public');
            }
            $client_preferences->web_color = $request->primary_color;
            $client_preferences->cart_enable = $request->cart_enable == 'on' ? 1 : 0;
            $client_preferences->age_restriction = $request->age_restriction == 'on' ? 1 : 0;
            $client_preferences->rating_check = $request->rating_enable == 'on' ? 1 : 0;
            $client_preferences->show_contact_us = $request->show_contact_us == 'on' ? 1 : 0;
            $client_preferences->age_restriction_title = $request->age_restriction_title;
            $client_preferences->save();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Web Styling Updated Successfully!'
        ]);
    }
}
