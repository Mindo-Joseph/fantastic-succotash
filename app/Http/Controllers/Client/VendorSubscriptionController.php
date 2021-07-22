<?php

namespace App\Http\Controllers\Client;

use Auth;
use Session;
use Redirect;
use Timezonelist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\ToasterResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{User, UserVendor, Vendor, UserAddress, ClientPreference, Client, SubscriptionPlansVendor, SubscriptionFeaturesListVendor, SubscriptionInvoicesVendor, SubscriptionInvoiceFeaturesVendor, Payment, PaymentOption};

class VendorSubscriptionController extends BaseController
{
    use ToasterResponser;
    use ApiResponser;
    /**
     * get Vendor subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans($domain = '', $id)
    {
        $sub_plans = SubscriptionPlansVendor::with('features.feature')->where('status', '1')->orderBy('sort_order', 'asc')->get();
        $featuresList = SubscriptionFeaturesListVendor::where('status', 1)->get();
        $active_subscription = SubscriptionInvoicesVendor::with(['plan', 'features.feature'])
                            ->where('vendor_id', $id)
                            ->orderBy('end_date', 'desc')->first();
        // $active_subscription_plan_ids = array();
        // foreach($active_subscription as $subscription){
        //     $active_subscription_plan_ids[] = $active_subscription->subscription_id;
        // }

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
        return view('backend.vendor.vendorSubscriptions')->with(['subscription_plans'=>$sub_plans, 'subscription'=>$active_subscription]);
    }
    
    /**
     * select vendor subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectSubscriptionPlan($domain = '', $slug = '')
    {
        $code = array('stripe');
        $sub_plan = SubscriptionPlansVendor::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
        if($sub_plan){
            $subFeaturesList = '<ul class="pl-1" style="list-style:none">';
            if($sub_plan->features->isNotEmpty()){
                foreach($sub_plan->features as $feature){
                    $subFeaturesList = $subFeaturesList.'<li><i class="fa fa-check"> '.$feature->feature->title.'</li>';
                }
                unset($sub_plan->features);
            }
            $subFeaturesList = $subFeaturesList.'<ul>';
            $sub_plan->features = $subFeaturesList;
        }
        else{
            return response()->json(["status"=>"Error", "message" => "Subscription plan not active"]);
        }
        $payment_options = PaymentOption::select('id', 'code', 'title')->whereIn('code', $code)->where('status', 1)->get();
        foreach ($payment_options as $payment_option) {
           $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
        }
        return response()->json(["status"=>"Success", "sub_plan" => $sub_plan, "payment_options" => $payment_options]);
    }

    /**
     * check if vendor has any active subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkActiveSubscription($domain = '', $id, $slug = '')
    {
        $vendorActiveSubscription = SubscriptionInvoicesVendor::with(['plan'])
                                ->whereNull('cancelled_at')
                                ->where('vendor_id', $id)
                                ->orderBy('end_date', 'desc')->first();
        if( ($vendorActiveSubscription) && ($vendorActiveSubscription->plan->slug != $slug) ){
            return $this->errorResponse('You cannot buy two subscriptions at the same time', 402);
        }
        return $this->successResponse('', 'Processing...');
    }

    /**
     * buy vendor subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseSubscriptionPlan(Request $request, $domain = '', $id, $slug = '')
    {
        $vendor = Vendor::where('id', $id)->first();
        $subscription_plan = SubscriptionPlansVendor::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
        $last_subscription = SubscriptionInvoicesVendor::with(['plan', 'features.feature'])
            ->where('vendor_id', $id)
            ->where('subscription_id', $subscription_plan->id)
            ->orderBy('end_date', 'desc')->first();
        if( ($vendor) && ($subscription_plan) ){
            $subscription_invoice = new SubscriptionInvoicesVendor;
            $subscription_invoice->vendor_id = $vendor->id;
            $subscription_invoice->subscription_id = $subscription_plan->id;
            $subscription_invoice->slug = strtotime(Carbon::now()).'_'.$slug;
            $subscription_invoice->payment_option_id = $request->payment_option_id;
            // $subscription_invoice->status_id = 2;
            $subscription_invoice->frequency = $subscription_plan->frequency;
            $subscription_invoice->payment_option_id = $request->payment_option_id;
            $subscription_invoice->transaction_reference = $request->transaction_id;
            $now = Carbon::now();
            $current_date = $now->toDateString();
            $start_date = $current_date;
            $next_date = NULL;
            $end_date = NULL;

            if($last_subscription){
                if($last_subscription->end_date >= $current_date){
                    $start_date = Carbon::parse($last_subscription->end_date)->addDays(1)->toDateString();
                }
            }
            if($subscription_plan->frequency == 'weekly'){
                $end_date = Carbon::parse($start_date)->addDays(6)->toDateString();
            }elseif($subscription_plan->frequency == 'monthly'){
                $end_date = Carbon::parse($start_date)->addMonths(1)->subDays(1)->toDateString();
            }elseif($subscription_plan->frequency == 'yearly'){
                $end_date = Carbon::parse($start_date)->addYears(1)->subDays(1)->toDateString();
            }
            $next_date = Carbon::parse($end_date)->addDays(1)->toDateString();
            $subscription_invoice->start_date = $start_date;
            $subscription_invoice->next_date = $next_date;
            $subscription_invoice->end_date = $end_date;
            $subscription_invoice->subscription_amount = $request->amount;
            $subscription_invoice->save();
            $subscription_invoice_id = $subscription_invoice->id;
            if($subscription_invoice_id){
                $payment = new Payment;
                $payment->balance_transaction = $request->amount;
                $payment->transaction_id = $request->transaction_id;
                $payment->vendor_subscription_invoice_id = $subscription_invoice_id;
                $payment->date = Carbon::now()->format('Y-m-d');
                $payment->save();

                $subscription_invoice_features = array();
                foreach($subscription_plan->features as $feature){
                    $subscription_invoice_features[] = array(
                        'vendor_id' => $vendor->id,
                        'subscription_id' => $subscription_plan->id,
                        'subscription_invoice_id' => $subscription_invoice_id,
                        'feature_id' => $feature->feature_id,
                        'feature_title' => $feature->feature->title
                    );
                }
                if(!empty($subscription_invoice_features)){
                    SubscriptionInvoiceFeaturesVendor::insert($subscription_invoice_features);
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
     * cancel vendor subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscriptionPlan($domain = '', $id, $slug = '')
    {
        $active_subscription = SubscriptionInvoicesVendor::with('plan')
                            ->where('slug', $slug)
                            ->where('vendor_id', $id)
                            ->orderBy('end_date', 'desc')->first();
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
