<?php

namespace App\Http\Controllers\Front;

use Session;
use Carbon\Carbon;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Contracts\Session\Session as SessionSession;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, ClientPreference, DriverRegistrationDocument, HomePageLabel, Page, VendorRegistrationDocument, Language,OnboardSetting,CabBookingLayout,WebStylingOption};
use Illuminate\Contracts\View\View;
use Illuminate\View\View as ViewView;
use Redirect;

class UserhomeController extends FrontController
{
    use ApiResponser;
    private $field_status = 2;

    public function setTheme(Request $request)
    {
        if ($request->theme_color == "dark") {
            Session::put('config_theme', $request->theme_color);
        } else {
            Session::forget('config_theme');
        }
    }
    public function getConfig()
    {
        $client_preferences = ClientPreference::first();
        return response()->json(['success' => true, 'client_preferences' => $client_preferences]);
        dd("neskjbf");
    }

    public function getLastMileTeams()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $unique = Auth::user()->code;
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->delivery_service_key,
                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->get($url . '/api/get-all-teams');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['teams'];
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function getAgentTags()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $unique = Auth::user()->code;
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->delivery_service_key,
                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->get($url . '/api/get-all-teams');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['teams'];
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function driverSignup()
    {
        $user = Auth::user();
        $language_id = Session::get('customerLanguage');
        $client_preferences = ClientPreference::first();
        $navCategories = $this->categoryNav($language_id);
        $client = Auth::user();
        $ClientPreference = ClientPreference::where('client_code', $client->code)->first();
        $preference = $ClientPreference ? $ClientPreference : new ClientPreference();
        $page_detail = Page::with(['translations' => function ($q) {
            $q->where('language_id', session()->get('customerLanguage'));
        }])->where('slug', 'driver-registration')->firstOrFail();
        $last_mile_teams = [];
        // $tags   =  $this->getAgentTags();
        $tag = [];
        // foreach ($tags as $key => $value) {
        //     array_push($tag, $value['name']);
        // }
        if (isset($preference) && $preference->need_delivery_service == '1') {
            $last_mile_teams = $this->getLastMileTeams();
        }
        $showTag = implode(',', $tag);
        $driver_registration_documents = DriverRegistrationDocument::get();
        return view('frontend.driver-registration', compact('page_detail','navCategories', 'client_preferences', 'user', 'showTag', 'last_mile_teams', 'driver_registration_documents'));
    }

    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getExtraPage(Request $request)
    {
        $user = Auth::user();
        $language_id = Session::get('customerLanguage');
        $client_preferences = ClientPreference::first();
        $navCategories = $this->categoryNav($language_id);
        $page_detail = Page::with(['translations' => function ($q) {
            $q->where('language_id', session()->get('customerLanguage'));
        }])->where('slug', $request->slug)->firstOrFail();
        $vendor_registration_documents = VendorRegistrationDocument::get();
        return view('frontend.extrapage', compact('page_detail', 'navCategories', 'client_preferences', 'user', 'vendor_registration_documents'));
    }
    public function index(Request $request)
    {
        try {
            $home = array();
            $vendor_ids = array();
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
            $latitude = Session::get('latitude');
            $longitude = Session::get('longitude');
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $count = 0;
            if ($clientPreferences) {
                if ($clientPreferences->dinein_check == 1) {
                    $count++;
                }
                if ($clientPreferences->takeaway_check == 1) {
                    $count++;
                }
                if ($clientPreferences->delivery_check == 1) {
                    $count++;
                }
            }
            if ($preferences) {
                if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
                    $selectedAddress = $preferences->Default_location_name;
                    $latitude = $preferences->Default_latitude;
                    $longitude = $preferences->Default_longitude;
                    Session::put('latitude', $latitude);
                    Session::put('longitude', $longitude);
                    Session::put('selectedAddress', $selectedAddress);
                }
            }
            $banners = Banner::where('status', 1)->where('validity_on', 1)
                ->where(function ($q) {
                    $q->whereNull('start_date_time')->orWhere(function ($q2) {
                        $q2->whereDate('start_date_time', '<=', Carbon::now())
                            ->whereDate('end_date_time', '>=', Carbon::now());
                    });
                })->orderBy('sorting', 'asc')->with('category')->with('vendor')->get();
            
            
            $home_page_labels = CabBookingLayout::where('is_active', 1)->orderBy('order_by');
            
            if(isset($langId) && !empty($langId))
            $home_page_labels = $home_page_labels->with(['translations' => function ($q)use($langId){
                $q->where('language_id',$langId);
            }]);

            $home_page_labels = $home_page_labels->get();  

            if(count($home_page_labels) == 0)
            $home_page_labels = HomePageLabel::with('translations')->where('is_active', 1)->orderBy('order_by')->get();
            

            $only_cab_booking = OnboardSetting::where('key_value','home_page_cab_booking')->count();
            if($only_cab_booking == 1)
            return Redirect::route('categoryDetail','cabservice');   

            $home_page_pickup_labels = CabBookingLayout::with('translations')->where('is_active', 1)->orderBy('order_by')->get();
           
            $set_template = WebStylingOption::where('web_styling_id',1)->where('is_selected',1)->first();

            if(isset($set_template)  && $set_template->template_id == 1)
            return view('frontend.home-template-one')->with(['home' => $home, 'count' => $count, 'homePagePickupLabels' => $home_page_pickup_labels,'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
            if(isset($set_template)  && $set_template->template_id == 2)
            return view('frontend.home')->with(['home' => $home, 'count' => $count, 'homePagePickupLabels' => $home_page_pickup_labels,'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
            else
            return view('frontend.home')->with(['home' => $home, 'count' => $count, 'homePagePickupLabels' => $home_page_pickup_labels,'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
           

        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    }
    public function postHomePageData(Request $request)
    {
        $vendor_ids = [];
        $new_products = [];
        $feature_products = [];
        $on_sale_products = [];
        if ($request->has('latitude')) {
            $latitude = $request->latitude;
            Session::put('latitude', $latitude);
        } else {
            $latitude = Session::get('latitude');
        }
        if ($request->has('longitude')) {
            $longitude = $request->longitude;
            Session::put('longitude', $longitude);
        } else {
            $longitude = Session::get('longitude');
        }
        $selectedAddress = ($request->has('selectedAddress')) ? Session::put('selectedAddress', $request->selectedAddress) : Session::get('selectedAddress');
        $selectedPlaceId = ($request->has('selectedPlaceId')) ? Session::put('selectedPlaceId', $request->selectedPlaceId) : Session::get('selectedPlaceId');
        $preferences = Session::get('preferences');
        $currency_id = Session::get('customerCurrency');
        $language_id = Session::get('customerLanguage');
        $brands = Brand::select('id', 'image','title')->with(['translation' => function($q)use($language_id){$q->where('language_id',$language_id);}])->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->get();
        foreach ($brands as $brand) {
            $brand->redirect_url = route('brandDetail', $brand->id);
            $brand->translation_title = $brand->translation->first() ? $brand->translation->first()->title : $brand->title;
        }
        Session::forget('vendorType');
        Session::put('vendorType', $request->type);
        $vendors = Vendor::with('products')->select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount', 'logo', 'slug', 'latitude', 'longitude')->where($request->type, 1);
        if ($preferences) {
            if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
                $selectedAddress = $preferences->Default_location_name;
                $latitude = $preferences->Default_latitude;
                $longitude = $preferences->Default_longitude;
                Session::put('latitude', $latitude);
                Session::put('longitude', $longitude);
                Session::put('selectedAddress', $selectedAddress);
            } else {
                if (($latitude == $preferences->Default_latitude) && ($longitude == $preferences->Default_longitude)) {
                    Session::put('selectedAddress', $preferences->Default_location_name);
                }
            }
            if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {
                $vendors = $vendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                    $query->select('vendor_id')
                        ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                });
            }
        }
        $vendors = $vendors->where('status', 1)->inRandomOrder()->get();
        foreach ($vendors as $key => $value) {
            $vendor_ids[] = $value->id;
            $value->vendorRating = $this->vendorRating($value->products);
            $value->name = Str::limit($value->name, 15, '..');
            if (($preferences) && ($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {
                $lat1   = $latitude;
                $long1  = $longitude;
                $lat2   = $value->latitude;
                $long2  = $value->longitude;
                $distance_unit = (!empty($preferences->distance_unit_for_time)) ? $preferences->distance_unit_for_time : 'kilometer';
                $distance_to_time_multiplier = (!empty($preferences->distance_to_time_multiplier)) ? $preferences->distance_to_time_multiplier : 2;
                $distance = $this->calulateDistanceLineOfSight($lat1,$long1,$lat2, $long2, $distance_unit);
                $value->lineOfSightDistance = number_format($distance, 1, '.', '');
                $value->timeofLineOfSightDistance = number_format(floatval($value->order_pre_time), 0, '.', '') + number_format(($distance * $distance_to_time_multiplier), 0, '.', ''); // distance is multiplied by multiplier to calculate travel time
            }
        }
        if (($latitude) && ($longitude)) {
            Session::put('vendors', $vendor_ids);
        }
        $navCategories = $this->categoryNav($language_id);
        Session::put('navCategories', $navCategories);
        $on_sale_product_details = $this->vendorProducts($vendor_ids, $language_id, 'USD', '', $request->type);
        $new_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_new', $request->type);
        $feature_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_featured', $request->type);
        foreach ($new_product_details as  $new_product_detail) {
            $multiply = $new_product_detail->variant->first() ? $new_product_detail->variant->first()->multiplier : 1;
            $title = $new_product_detail->translation->first() ? $new_product_detail->translation->first()->title : $new_product_detail->sku;
            $image_url = $new_product_detail->media->first() ? $new_product_detail->media->first()->image->path['image_fit'] . '600/600' . $new_product_detail->media->first()->image->path['image_path'] : '';
            $new_products[] = array(
                'image_url' => $image_url,
                'sku' => $new_product_detail->sku,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $new_product_detail->url_slug,
                'averageRating' => number_format($new_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $new_product_detail->inquiry_only,
                'vendor_name' => $new_product_detail->vendor ? $new_product_detail->vendor->name : '',
                'price' => Session::get('currencySymbol') . ' ' . (number_format($new_product_detail->variant->first()->price * $multiply, 2)),
            );
        }
        foreach ($feature_product_details as  $feature_product_detail) {
            $multiply = $feature_product_detail->variant->first() ? $feature_product_detail->variant->first()->multiplier : 1;
            $title = $feature_product_detail->translation->first() ? $feature_product_detail->translation->first()->title : $feature_product_detail->sku;
            $image_url = $feature_product_detail->media->first() ? $feature_product_detail->media->first()->image->path['image_fit'] . '600/600' . $feature_product_detail->media->first()->image->path['image_path'] : '';
            $feature_products[] = array(
                'image_url' => $image_url,
                'sku' => $feature_product_detail->sku,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $feature_product_detail->url_slug,
                'averageRating' => number_format($feature_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $feature_product_detail->inquiry_only,
                'vendor_name' => $feature_product_detail->vendor ? $feature_product_detail->vendor->name : '',
                'price' => Session::get('currencySymbol') . ' ' . (number_format($feature_product_detail->variant->first()->price * $multiply, 2)),
            );
        }
        foreach ($on_sale_product_details as  $on_sale_product_detail) {
            $multiply = $on_sale_product_detail->variant->first() ? $on_sale_product_detail->variant->first()->multiplier : 1;
            $title = $on_sale_product_detail->translation->first() ? $on_sale_product_detail->translation->first()->title : $on_sale_product_detail->sku;
            $image_url = $on_sale_product_detail->media->first() ? $on_sale_product_detail->media->first()->image->path['image_fit'] . '600/600' . $on_sale_product_detail->media->first()->image->path['image_path'] : '';
            $on_sale_products[] = array(
                'image_url' => $image_url,
                'sku' => $on_sale_product_detail->sku,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $on_sale_product_detail->url_slug,
                'averageRating' => number_format($on_sale_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $on_sale_product_detail->inquiry_only,
                'vendor_name' => $on_sale_product_detail->vendor ? $on_sale_product_detail->vendor->name : '',
                'price' => Session::get('currencySymbol') . ' ' . (number_format($on_sale_product_detail->variant->first()->price * $multiply, 2)),
            );
        }
        $home_page_labels = HomePageLabel::with('translations')->get();
        $data = [
            'brands' => $brands,
            'vendors' => $vendors,
            'new_products' => $new_products,
            'navCategories' => $navCategories,
            'homePageLabels' => $home_page_labels,
            'feature_products' => $feature_products,
            'on_sale_products' => $on_sale_products,
        ];
        return $this->successResponse($data);
    }

    public function vendorProducts($venderIds, $langId, $currency = 'USD', $where = '', $type)
    {
        $products = Product::with([
            'vendor' => function ($q) use ($type) {
                $q->where($type, 1);
            },
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        $pndCategories = Category::where('type_id', 7)->pluck('id');
        if (is_array($venderIds)) {
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        if ($pndCategories) {
            $products = $products->whereNotIn('category_id', $pndCategories);
        }
        $products = $products->where('is_live', 1)->take(10)->inRandomOrder()->get();
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                }
            }
        }
        return $products;
    }

    public function changePrimaryData(Request $request)
    {
        if ($request->has('type') && $request->type == 'language') {
            $clientLanguage = ClientLanguage::where('language_id', $request->value1)->first();
            if ($clientLanguage) {
                $lang_detail = Language::where('id', $request->value1)->first();
                App::setLocale($lang_detail->sort_code);
                session()->put('locale', $lang_detail->sort_code);
                Session::put('customerLanguage', $request->value1);
            }
        }
        if ($request->has('type') && $request->type == 'currency') {
            $clientCurrency = ClientCurrency::where('currency_id', $request->value1)->first();
            if ($clientCurrency) {
                $currency_detail = Currency::where('id', $request->value1)->first();
                Session::put('currencySymbol', $request->value2);
                Session::put('customerCurrency', $request->value1);
                Session::put('iso_code', $currency_detail->iso_code);
                Session::put('currencyMultiplier', $clientCurrency->doller_compare);
            }
        }
        $data['customerLanguage'] = Session::get('customerLanguage');
        $data['customerCurrency'] = Session::get('customerCurrency');
        $data['currencySymbol'] = Session::get('currencySymbol');
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully!', 'data' => $data]);
    }

    public function changePaginate(Request $request)
    {
        $perPage = 12;
        if ($request->has('itemPerPage')) {
            $perPage = $request->itemPerPage;
        }
        Session::put('cus_paginate', $perPage);
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully!', 'data' => $perPage]);
    }

    public function getClientPreferences(Request $request)
    {
        $clientPreferences = ClientPreference::first();
        if ($clientPreferences) {
            $dinein_check = $clientPreferences->dinein_check;
            $delivery_check = $clientPreferences->delivery_check;
            $takeaway_check = $clientPreferences->takeaway_check;
            $age_restriction = $clientPreferences->age_restriction;
            return response()->json(["age_restriction" => $age_restriction, "dinein_check" => $dinein_check, "delivery_check" => $delivery_check, "takeaway_check" => $takeaway_check]);
        }
    }


    /////    new home page 
    public function indexTemplateOne(Request $request)
    {
        try {
            $home = array();
            $vendor_ids = array();
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
            $latitude = Session::get('latitude');
            $longitude = Session::get('longitude');
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $count = 0;
            if ($clientPreferences) {
                if ($clientPreferences->dinein_check == 1) {
                    $count++;
                }
                if ($clientPreferences->takeaway_check == 1) {
                    $count++;
                }
                if ($clientPreferences->delivery_check == 1) {
                    $count++;
                }
            }
            if ($preferences) {
                if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
                    $selectedAddress = $preferences->Default_location_name;
                    $latitude = $preferences->Default_latitude;
                    $longitude = $preferences->Default_longitude;
                    Session::put('latitude', $latitude);
                    Session::put('longitude', $longitude);
                    Session::put('selectedAddress', $selectedAddress);
                }
            }
            $banners = Banner::where('status', 1)->where('validity_on', 1)
                ->where(function ($q) {
                    $q->whereNull('start_date_time')->orWhere(function ($q2) {
                        $q2->whereDate('start_date_time', '<=', Carbon::now())
                            ->whereDate('end_date_time', '>=', Carbon::now());
                    });
                })->orderBy('sorting', 'asc')->with('category')->with('vendor')->get();
            $home_page_labels = HomePageLabel::with('translations')->where('is_active', 1)->orderBy('order_by')->get();

            $only_cab_booking = OnboardSetting::where('key_value','home_page_cab_booking')->count();
            if($only_cab_booking == 1)
            return Redirect::route('categoryDetail','cabservice');    
            $home_page_pickup_labels = CabBookingLayout::with(['translations' => function($q)use($langId){
                $q->where('language_id',$langId);
            }])->where('is_active', 1)->orderBy('order_by')->get();
           
            return view('frontend.home-template-one')->with(['home' => $home, 'count' => $count, 'homePagePickupLabels' => $home_page_pickup_labels,'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    }
}
