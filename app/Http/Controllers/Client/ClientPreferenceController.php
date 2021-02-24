<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{Client, ClientPreference, MapProvider, SmsProvider, Template, Currency, Language, ClientLanguage, ClientCurrency};

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
        $languages = Language::where('id', '>', '0')->get(); /*  cprimary - currency primary*/
        $preference = ClientPreference::with('language', 'domain', 'currency', 'primary.currency')->select('client_code', 'theme_admin', 'distance_unit', 'date_format', 'time_format', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'verify_email', 'verify_phone', 'web_template_id', 'app_template_id')
                        ->where('client_code', Auth::user()->code)->first();

        dd($preference->toArray());
        $client = Auth::user();
        $cli_langs = array();
        $cli_currencies = array();

        if(!$preference){
            $preference = new ClientPreference();
        }else{
            foreach ($preference->currency as $key => $value) {
                $cli_currencies[] = $value->currency_id;
            }
            foreach ($preference->language as $key => $value) {
                $cli_langs[] = $value->language_id;
            }
        }
        return view('backend/setting/customize')->with(['client' => $client, 'preference' => $preference, 'webTemplates' => $webTemplates, 'appTemplates' => $appTemplates, 'currencies' => $currencies, 'languages' => $languages, 'cli_langs' => $cli_langs, 'cli_currs' => $cli_currencies]);
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
        //dd($request->all());
        $cp = new ClientPreference();
        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();

        //$fillingData = $cp->filling();
        if(!$preference){
            $preference = new ClientPreference();
            $preference->client_code = $code;
        }
        
        $keyShouldNot = array('Default_location_name', 'Default_latitude', 'Default_longitude', 'is_hyperlocal', '_token', 'social_login', 'send_to', 'languages', 'hyperlocals', 'currency_data');

        foreach ($request->all() as $key => $value) {

            if(!in_array($key, $keyShouldNot)){
               $preference->{$key} = $value; 
            }
        }

        if($request->has('hyperlocals') && $request->hyperlocals == '1'){
            $preference->is_hyperlocal = ($request->has('is_hyperlocal') && $request->is_hyperlocal == 'on') ? 1 : 0;
            if($request->has('is_hyperlocal') && $request->is_hyperlocal == 'on'){
                $preference->Default_location_name = $request->Default_location_name;
                $preference->Default_latitude = $request->Default_latitude;
                $preference->Default_longitude = $request->Default_longitude;
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
            $preference->need_delivery_service = ($request->has('need_delivery_service') && $request->need_delivery_service == 'on') ? 1 : 0;
        }

        if($request->has('currency_data')){

            $exist = array();
            $exist[] = 147;
            foreach ($request->currency_data as $cur) {
                
                $currs = ClientCurrency::where('client_code', Auth::user()->code)->where('currency_id', $cur)->first();
                if(!$currs){
                    $currs = new ClientCurrency();
                    $currs->client_code = Auth::user()->code;
                    $currs->currency_id = $cur;
                    $currs->save();
                }
                $exist[] = $currs->currency_id;
            }

            $delCount = ClientCurrency::where('client_code', Auth::user()->code)->whereNotIn('currency_id', $exist)->count();
            if($delCount > 0){
                $delete = ClientCurrency::where('client_code', Auth::user()->code)->whereNotIn('currency_id', $exist)->delete();
            }
            
        }
        if($request->has('languages')){

            $exist_langs = array();

            $exist_langs[] = 1;

            foreach ($request->languages as $langs) {
                
                $clientLang = ClientLanguage::where('client_code', Auth::user()->code)->where('language_id', $langs)->first();
                if(!$clientLang){
                    $clientLang = new ClientLanguage();
                    $clientLang->client_code = Auth::user()->code;
                    $clientLang->language_id = $langs;
                    $clientLang->save();
                }
                $exist_langs[] = $clientLang->language_id;

            }

            $delCount = ClientLanguage::where('client_code', Auth::user()->code)->whereNotIn('language_id', $exist_langs)->count();
            if($delCount > 0){
                $delete = ClientLanguage::where('client_code', Auth::user()->code)->whereNotIn('language_id', $exist_langs)->delete();
            }
            
        }
        $preference->save();

        if($request->has('send_to') && $request->send_to == 'customize'){
            return redirect()->route('configure.customize')->with('success', 'Client customizations updated successfully!');
        }

        return redirect()->route('configure.index')->with('success', 'Client configurations updated successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClientPreference  $clientPreference
     * @return \Illuminate\Http\Response
     */
    public function updateDomain(Request $request, $code)
    {
        $rules = array(
            'custom_domain' => 'required|max:30',
        );

        $validation  = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $client = Client::where('code', Auth::user()->code)->first();
        //dd($client->toArray());
        $client->custom_domain = $request->custom_domain;
        $client->save();
        return redirect()->route('configure.customize')->with('success', 'Client customize data updated successfully!');
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
