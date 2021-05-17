<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{Client, ClientPreference, MapProvider, SmsProvider, Template, Currency, Language, ClientLanguage, ClientCurrency};
use Illuminate\Support\Facades\Storage;

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
        
        $curArray = array();
        $primaryCurrency = ClientCurrency::where('is_primary', 1)->first();

        $currencies = Currency::where('id', '>', '0')->get();

        $curtableData = array_chunk($currencies->toArray(), 2);
        //dd($currencies);

        $languages = Language::where('id', '>', '0')->get(); /*  cprimary - currency primary*/
        $preference = ClientPreference::with('language', 'primarylang', 'domain', 'currency.currency', 'primary.currency')->select('client_code', 'theme_admin', 'distance_unit', 'date_format', 'time_format', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'verify_email', 'verify_phone', 'web_template_id', 'app_template_id', 'primary_color', 'secondary_color')
                        ->where('client_code', Auth::user()->code)->first();

        //dd($preference->toArray());
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
        return view('backend/setting/customize')->with(['client' => $client, 'preference' => $preference, 'webTemplates' => $webTemplates, 'appTemplates' => $appTemplates, 'currencies' => $currencies, 'languages' => $languages, 'cli_langs' => $cli_langs, 'cli_currs' => $cli_currencies, 'primaryCurrency' => $primaryCurrency, 'curtableData' => $curtableData]);
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
        $cp = new ClientPreference();
        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();

        //dd($request->all());

        //$fillingData = $cp->filling();
        if(!$preference){
            $preference = new ClientPreference();
            $preference->client_code = $code;
        }
        
        $keyShouldNot = array('Default_location_name', 'Default_latitude', 'Default_longitude', 'is_hyperlocal', '_token', 'social_login', 'send_to', 'languages', 'hyperlocals', 'currency_data', 'multiply_by', 'cuid', 'primary_language', 'primary_currency', 'currency_data');

        foreach ($request->all() as $key => $value) {
            if(!in_array($key, $keyShouldNot)){
               $preference->{$key} = $value; 
            }
        }

        if($request->has('hyperlocals') && $request->hyperlocals == '1'){
            $preference->is_hyperlocal = ($request->has('is_hyperlocal') && $request->is_hyperlocal == 'on') ? 1 : 0;

            $preference->need_delivery_service = ($request->has('need_delivery_service') && $request->need_delivery_service == 'on') ? 1 : 0;

            $preference->need_dispacher_ride = ($request->has('need_dispacher_ride') && $request->need_dispacher_ride == 'on') ? 1 : 0;

            if($request->has('is_hyperlocal') && $request->is_hyperlocal == 'on'){
                $preference->Default_location_name = $request->Default_location_name;
                $preference->Default_latitude = $request->Default_latitude;
                $preference->Default_longitude = $request->Default_longitude;
            }

            if($request->has('need_delivery_service') && $request->need_delivery_service == 'on'){
                $preference->delivery_service_key = $request->delivery_service_key;
            }

            if($request->has('need_dispacher_ride') && $request->need_dispacher_ride == 'on'){
                $preference->dispatcher_key = $request->dispatcher_key;
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
            $preference->celebrity_check = ($request->has('celebrity_check') && $request->celebrity_check == 'on') ? 1 : 0;
            
            
        }

        if($request->has('languages')){

            $existLanguage = array();

            foreach ($request->languages as $lan) {
                $lang = ClientLanguage::where('client_code',Auth::user()->code)->where('language_id', $lan)->first();
                if(!$lang){
                    $lang = new ClientLanguage();
                    $lang->client_code = Auth::user()->code;
                }
                $lang->is_primary = 0;
                $lang->language_id = $lan;
                $lang->is_active = 1;
                $lang->save();
                $existLanguage[] = $lan;
            }

            $deactivateLanguages = ClientLanguage::where('client_code',Auth::user()->code)->whereNotIn('language_id', $existLanguage)->where('is_primary', 0)->update(['is_active' => 0]);
        }

        if($request->has('primary_language')){

            $deactivateLanguages = ClientLanguage::where('client_code',Auth::user()->code)->where('is_primary', 1)->update(['is_active' => 0, 'is_primary' => 0]);

            $primary_change = ClientLanguage::where('client_code', Auth::user()->code)->where('language_id', $request->primary_language)->update(['is_active' => 1, 'is_primary' => 1]);

            if(!$primary_change){
                $primary_lang[] = [
                    'client_code'=> Auth::user()->code,
                    'language_id'=> $request->primary_language,
                    'is_primary'=> 1,
                    'is_active'=> 1
                ];

                ClientLanguage::insert($primary_lang);
            }
        }

        if($request->has('primary_currency')){

            $oldAdditional = ClientCurrency::where('currency_id', $request->primary_currency)
                        ->where('is_primary', 0)->delete();

            $primaryCur = ClientCurrency::where('is_primary', 1)->update(['currency_id' => $request->primary_currency, 'doller_compare' => 1]); 
        }

        if($request->has('primary_currency') && !$request->has('currency_data')){

            $delete = ClientCurrency::where('client_code',Auth::user()->code)->where('is_primary', 0)->delete();
        }

        if($request->has('currency_data') && $request->has('multiply_by')){
            $cur_multi = $exist_cid = array(); 
            foreach ($request->currency_data as $key => $value) {

                $exist_cid[] = $value;

                $curr = ClientCurrency::where('currency_id', $value)->where('client_code',Auth::user()->code)->first();
                $multiplier = array_key_exists($key, $request->multiply_by) ? $request->multiply_by[$key] : 1;
                if(!$curr){

                    $cur_multi[] = [
                        'currency_id'=> $value,
                        'client_code'=> Auth::user()->code,
                        'is_primary'=> 0,
                        'doller_compare'=> $multiplier
                    ];
                }else{
                    ClientCurrency::where('currency_id', $value)->where('client_code',Auth::user()->code)
                                ->update(['doller_compare' => $multiplier]);                    
                }
            }
            ClientCurrency::insert($cur_multi);
            $delete = ClientCurrency::where('client_code',Auth::user()->code)->where('is_primary', 0)
                            ->whereNotIn('currency_id',$exist_cid)->delete();
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
