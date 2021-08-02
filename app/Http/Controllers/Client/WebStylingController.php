<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{ClientPreference, HomePageLabel,ClientLanguage};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $home_page_labels = HomePageLabel::all();
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->where('client_languages.is_active', 1)
                    ->orderBy('client_languages.is_primary', 'desc')->get();

        return view('backend/web_styling/index')->with(['client_preferences' => $client_preferences,'home_page_labels' => $home_page_labels, 'langs' => $langs]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWebStyles(Request $request){

        $featured_vendor = HomePageLabel::where('slug', 'featured_vendors')->first();
        if($featured_vendor){
            $featured_vendor->is_active = $request->has('featured_vendors') && $request->featured_vendors == "on" ? 1 : 0;
            $featured_vendor->save(); 
        }
        $vendors = HomePageLabel::where('slug', 'vendors')->first();
        if($vendors){
            $vendors->is_active = $request->has('vendors') && $request->vendors == "on" ? 1 : 0;
            $vendors->save(); 
        }
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
            $client_preferences->show_icons = $request->show_icons == 'on' ? 1 : 0;
            $client_preferences->show_wishlist = $request->show_wishlist == 'on' ? 1 : 0;
            $client_preferences->age_restriction_title = $request->age_restriction_title;
            $client_preferences->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Web Styling Updated Successfully!'
        ]);
    }
}
