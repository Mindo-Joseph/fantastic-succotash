<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\{Client, ClientPreference, MapProvider, SmsProvider, Template, Currency, Language};

class ClientPreferenceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mapTypes = MapProvider::where('status', '1')->get();
        $smsTypes = SmsProvider::where('status', '1')->get();
        $preference = ClientPreference::where('client_code',Auth::user()->code)->first();
        $client = Auth::user();
        if(!$preference){
            $preference = new ClientPreference();

        }
        return view('backend/setting/config')->with(['client' => $client, 'preference' => $preference, 'mapTypes'=> $mapTypes, 'smsTypes' => $smsTypes]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClientPreference  $clientPreference
     * @return \Illuminate\Http\Response
     */
    public function customize(ClientPreference $clientPreference)
    {
        $webTemplates = Template::where('for', '1')->get();
        $appTemplates = Template::where('for', '2')->get();
        $currencies = Currency::where('id', '>', '0')->get();
        $languages = Language::where('id', '>', '0')->get();
        $preference = ClientPreference::select('client_code', 'theme_admin', 'distance_unit', 'currency_id', 'language_id', 'date_format', 'time_format', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'verify_email', 'verify_phone', 'web_template_id', 'app_template_id')
                        ->where('client_code',Auth::user()->code)->first();
        $client = Auth::user();
        if(!$preference){
            $preference = new ClientPreference();
        }
        return view('backend/setting/customize')->with(['client' => $client, 'preference' => $preference, 'webTemplates' => $webTemplates, 'appTemplates' => $appTemplates, 'currencies' => $currencies, 'languages' => $languages]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClientPreference  $clientPreference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        $preference = ClientPreference::where('client_code', $code)->first();

        if(!$preference){
            $preference = new ClientPreference();
            $preference->client_code = $code;
        }

        foreach ($request->all() as $key => $value) {
            if($key != '_token' && $key != 'social_login' && $key != 'send_to'){
               $preference->{$key} = $value; 
            }
        }
        if($request->has('social_login') && $request->social_login == '1'){
            $preference->fb_login = ($request->has('fb_login') && $request->fb_login == 'on') ? 1 : 0; 
            $preference->twitter_login = ($request->has('twitter_login') && $request->twitter_login == 'on') ? 1 : 0; 
            $preference->google_login = ($request->has('google_login') && $request->google_login == 'on') ? 1 : 0; 
            $preference->apple_login = ($request->has('apple_login') && $request->apple_login == 'on') ? 1 : 0; 
        }

        if($request->has('verify_email')){
            $preference->verify_email = ($request->has('verify_email') && $request->verify_email == 'on') ? 1 : 0; 
            $preference->verify_phone = ($request->has('verify_phone') && $request->verify_phone == 'on') ? 1 : 0;
        }
        $preference->save();

        if($request->has('send_to') && $request->send_to == 'customize'){
            return redirect()->route('configure.customize')->with('success', 'Client customize data updated successfully!');
        }

        return redirect()->route('configure.index')->with('success', 'Client preference updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClientPreference  $clientPreference
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientPreference $clientPreference)
    {
        //
    }
}
