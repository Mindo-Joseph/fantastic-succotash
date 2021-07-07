<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Timezonelist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Front\FrontController;
use App\Models\{User, UserAddress, ClientPreference, Client, UserSubscriptions, SubscriptionFeaturesList, SubscriptionValidities};

class SubscriptionController extends FrontController
{
    /**
     * get user subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscriptions(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $subscriptions = UserSubscriptions::with('features.feature', 'validity')->where('status', '1')->get();
        $featuresList = SubscriptionFeaturesList::where('type', 'User')->where('status', 1)->get();
        if($subscriptions){
            foreach($subscriptions as $sub){
                $subFeaturesList = array();
                if($sub->features->isNotEmpty()){
                    foreach($sub->features as $feature){
                        $subFeaturesList[] = $feature->feature->title;
                    }
                    unset($sub->features);
                }
                $sub->features = $subFeaturesList;
            }
        }
        return view('frontend.account.subscriptions')->with(['navCategories' => $navCategories, 'subscriptions'=> $subscriptions]);
    }
}
