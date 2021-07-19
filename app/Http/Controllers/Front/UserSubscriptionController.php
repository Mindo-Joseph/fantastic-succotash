<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Timezonelist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Front\FrontController;
use App\Models\{User, UserAddress, ClientPreference, Client, SubscriptionPlansUser, SubscriptionFeaturesListUser, SubscriptionInvoicesUser, SubscriptionInvoiceFeaturesUser, PaymentOption};

class UserSubscriptionController extends FrontController
{
    use ApiResponser;
    /**
     * get user subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans(Request $request, $domain = '')
    {
        $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', '60f165e1ae656')->where('status', '1')->first();
        // dd($subscription_plan->toArray());
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $sub_plans = SubscriptionPlansUser::with('features.feature')->where('status', '1')->orderBy('sort_order', 'asc')->get();
        $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        $active_subscriptions = SubscriptionInvoicesUser::with(['plan', 'features.feature'])->where('user_id', Auth::user()->id)->get();
        // dd($active_subscriptions->toArray());
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
        return view('frontend.account.userSubscriptions')->with(['navCategories' => $navCategories, 'subscriptions'=> $sub_plans, 'active_subscriptions' => $active_subscriptions]);
    }
    
    /**
     * buy user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectSubscriptionPlan(Request $request, $domain = '', $slug = '')
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
    public function purchaseSubscriptionPlan(Request $request, $domain = '', $slug = '')
    {
        $user = Auth::user();
        $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
        if( ($user) && ($subscription_plan) ){
            $subscription_invoice = new SubscriptionInvoicesUser;
            $subscription_invoice->user_id = $user->id;
            $subscription_invoice->subscription_id = $subscription_plan->id;
            $subscription_invoice->slug = strtotime(Carbon::now()).'_'.$slug;
            $subscription_invoice->payment_option_id = $request->payment_option_id;
            $subscription_invoice->status_id = 2;
            $subscription_invoice->frequency = $subscription_plan->frequency;
            $subscription_invoice->payment_option_id = $request->payment_option_id;
            $subscription_invoice->transaction_reference = $request->transaction_id;
            $subscription_invoice->start_date = Carbon::now()->toDateTimeString();
            if($subscription_plan->frequency == 'weekly'){
                $subscription_invoice->next_date = Carbon::now()->addDays(7)->toDateTimeString();
                $subscription_invoice->end_date = Carbon::now()->addDays(6)->toDateTimeString();
            }
            elseif($subscription_plan->frequency == 'monthly'){
                $subscription_invoice->next_date = Carbon::now()->addDays(30)->toDateTimeString();
                $subscription_invoice->end_date = Carbon::now()->addDays(29)->toDateTimeString();
            }
            elseif($subscription_plan->frequency == 'yearly'){
                $subscription_invoice->next_date = Carbon::now()->addDays(365)->toDateTimeString();
                $subscription_invoice->end_date = Carbon::now()->addDays(364)->toDateTimeString();
            }
            $subscription_invoice->subscription_amount = $request->amount;
            $subscription_invoice->save();
            $subscription_invoice_id = $subscription_invoice->id;
            if($subscription_invoice_id){
                $subscription_invoice_features = new SubscriptionInvoiceFeaturesUser;
                foreach($subscription_plan->features as $feature){
                    $subscription_invoice_features->user_id = $user->id;
                    $subscription_invoice_features->subscription_id = $subscription_plan->id;
                    $subscription_invoice_features->subscription_invoice_id = $subscription_invoice_id;
                    $subscription_invoice_features->feature_id = $feature->feature_id;
                    $subscription_invoice_features->feature_title = $feature->feature->title;
                    $subscription_invoice_features->save();
                }
                return $this->successResponse('', 'Your subscription has been activated successfully.');
            }
            else{
                return $this->errorResponse('Error in purchasing subscription.', 402);
            }
        }
        else{
            return $this->errorResponse('Invalid Data', 402);
        }
    }
}
