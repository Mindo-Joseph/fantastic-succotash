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
use App\Models\{User, UserAddress, ClientPreference, Client, SubscriptionPlansUser, SubscriptionFeaturesListUser, PaymentOption};

class UserSubscriptionController extends FrontController
{
    /**
     * get user subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptions(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $sub_plans = SubscriptionPlansUser::with('features.feature')->where('status', '1')->orderBy('sort_order', 'asc')->get();
        $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        if($sub_plans){
            foreach($sub_plans as $sub){
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
        return view('frontend.account.userSubscriptions')->with(['navCategories' => $navCategories, 'subscriptions'=> $sub_plans]);
    }
    
    /**
     * buy user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function buySubscription(Request $request, $domain = '', $slug = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $sub_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->firstOrFail();
        $payment_options = PaymentOption::select('id', 'code', 'title')->where('status', 1)->get();
        foreach ($payment_options as $payment_option) {
           $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
        }
        // return view('frontend.account.buysubscription')->with(['navCategories' => $navCategories, 'subscription'=> $subscription, 'payment_options'=>$payment_options]);
        return response()->json(["status"=>"Success", "sub_plan" => $sub_plan, "payment_options" => $payment_options]);
    }

    /**
     * buy user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseSubscription(Request $request, $domain = '', $slug = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $sub_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->firstOrFail();
        $payment_options = PaymentOption::select('id', 'code', 'title')->where('status', 1)->get();
        foreach ($payment_options as $payment_option) {
           $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
        }
        // return view('frontend.account.buysubscription')->with(['navCategories' => $navCategories, 'subscription'=> $subscription, 'payment_options'=>$payment_options]);
        return response()->json(["status"=>"Success", "sub_plan" => $sub_plan, "payment_options" => $payment_options]);
    }
}
