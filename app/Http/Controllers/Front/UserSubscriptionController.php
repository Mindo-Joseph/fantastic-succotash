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
use App\Models\{User, UserAddress, ClientPreference, Client, SubscriptionPlansUser, SubscriptionFeaturesListUser, SubscriptionInvoicesUser, SubscriptionInvoiceFeaturesUser, Payment, PaymentOption};

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
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $sub_plans = SubscriptionPlansUser::with('features.feature')->where('status', '1')->orderBy('sort_order', 'asc')->get();
        $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        $active_subscriptions = SubscriptionInvoicesUser::with(['plan', 'features.feature'])
                            ->where('status_id', 2)
                            ->where('user_id', Auth::user()->id)->get();
        $active_subscription_plan_ids = array();
        foreach($active_subscriptions as $subscription){
            $active_subscription_plan_ids[] = $subscription->subscription_id;
        }

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
        return view('frontend.account.userSubscriptions')->with(['navCategories'=>$navCategories, 'subscriptions'=>$sub_plans, 'active_subscriptions'=>$active_subscriptions, 'active_subscription_plan_ids'=>$active_subscription_plan_ids]);
    }
    
    /**
     * select user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectSubscriptionPlan(Request $request, $domain = '', $slug = '')
    {
        $code = array('stripe');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $sub_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->firstOrFail();
        $payment_options = PaymentOption::select('id', 'code', 'title')->whereIn('code', $code)->where('status', 1)->get();
        foreach ($payment_options as $payment_option) {
           $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
        }
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
            $subscription_invoice->start_date = Carbon::now()->toDateString();
            $subscription_invoice->next_date = NULL;
            if($subscription_plan->frequency == 'weekly'){
                $subscription_invoice->next_date = Carbon::now()->addDays(6)->toDateString();
            }
            elseif($subscription_plan->frequency == 'monthly'){
                $subscription_invoice->next_date = Carbon::now()->addDays(29)->toDateString();
            }
            elseif($subscription_plan->frequency == 'yearly'){
                $subscription_invoice->next_date = Carbon::now()->addDays(364)->toDateString();
            }
            $subscription_invoice->end_date = $subscription_invoice->next_date;
            $subscription_invoice->subscription_amount = $request->amount;
            $subscription_invoice->save();
            $subscription_invoice_id = $subscription_invoice->id;
            if($subscription_invoice_id){
                $payment = new Payment;
                $payment->balance_transaction = $request->amount;
                $payment->transaction_id = $request->transaction_id;
                $payment->user_subscription_invoice_id = $subscription_invoice_id;
                $payment->date = Carbon::now()->format('Y-m-d');
                $payment->save();

                $subscription_invoice_features = array();
                foreach($subscription_plan->features as $feature){
                    $subscription_invoice_features[] = array(
                        'user_id' => $user->id,
                        'subscription_id' => $subscription_plan->id,
                        'subscription_invoice_id' => $subscription_invoice_id,
                        'feature_id' => $feature->feature_id,
                        'feature_title' => $feature->feature->title
                    );
                }
                if(!empty($subscription_invoice_features)){
                    SubscriptionInvoiceFeaturesUser::insert($subscription_invoice_features);
                }
                $message = 'Your subscription has been activated successfully.';
                Session::put('success', $message);
                return $this->successResponse('', $message);
            }
            else{
                return $this->errorResponse('Error in purchasing subscription.', 402);
            }
        }
        else{
            return $this->errorResponse('Invalid Data', 402);
        }
    }

    /**
     * cancel user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscriptionPlan(Request $request, $domain = '', $slug = '')
    {
        $active_subscription = SubscriptionInvoicesUser::with('plan')
                            ->where('slug', $slug)
                            ->where('user_id', Auth::user()->id)
                            ->where('status_id', 2)->first();
        if($active_subscription){
            $active_subscription->cancelled_at = $active_subscription->end_date;
            $active_subscription->updated_at = Carbon::now()->toDateTimeString();
            $active_subscription->save();
            return redirect()->back()->with('success', 'Your '.$active_subscription->plan->title.' subscription has been cancelled successfully');
        }
        else{
            return redirect()->back()->with('error', 'Unable to cancel subscription');
        }
    }
}
