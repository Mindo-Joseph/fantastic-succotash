<?php

namespace App\Http\Controllers\client;

use DB;
use Session;
use \DateTimeZone;
use App\Jobs\UpdateClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, SmsProvider, Currency, Language, Country, Order, User, Vendor, UserSubscriptions, VendorSubscriptions, UserSubscriptionFeatures, VendorSubscriptionFeatures, SubscriptionFeaturesList, SubscriptionValidities};
use Carbon\Carbon;

class SubscriptionController extends BaseController
{
    use ApiResponser;
    private $folderName = '/subscriptions/image';
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    /**
     * Get user subscriptions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userSubscriptions(Request $request, $domain = '')
    {
        $user_subs = UserSubscriptions::with(['features.feature', 'validity'])->get();
        $featuresList = SubscriptionFeaturesList::where('type', 'User')->where('status', 1)->get();
        $validities = SubscriptionValidities::where('status', 1)->get();
        $features = '';
        if($user_subs){
            foreach($user_subs as $sub){
                if($sub->features->isNotEmpty()){
                    $subFeaturesList = array();
                    foreach($sub->features as $feature){
                        $subFeaturesList[] = $feature->feature->title;
                    }
                    unset($sub->features);
                    $features = implode(', ', $subFeaturesList);
                }
                $sub->features = $features;
            }
        }
        return view('backend/subscriptions/userSubscriptions')->with(['validities'=>$validities, 'features'=>$featuresList, 'user_subscriptions'=>$user_subs]);
    }

    /**
     * save user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveUserSubscription(Request $request, $domain = '', $slug='')
    {
        $message = 'added';
        $rules = array(
            'title' => 'required|string|max:50|unique:user_subscriptions',
            'features' => 'required',
            'price' => 'required',
            'validity' => 'required'
        );
        if(!empty($slug)){
            $subscription = UserSubscriptions::where('slug', $slug)->firstOrFail();
            $rules['title'] = $rules['title'].',id,'.$subscription->id;
            $message = 'updated';
        }

        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        if(!empty($slug)){
            $subFeatures = UserSubscriptionFeatures::where('subscription_id', $subscription->id)->whereNotIn('feature_id', $request->features)->delete();
        }else{
            $subscription = new UserSubscriptions;
        }
        $subscription->title = $request->title;
        $subscription->slug = strtolower(str_replace(' ', '-', $request->title));
        $subscription->price = $request->price;
        $subscription->validity_id = $request->validity;
        $subscription->status = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $subscription->image = Storage::disk('s3')->put($this->folderName, $file,'public');
        }
        if( ($request->has('description')) && (!empty($request->description)) ){
            $subscription->description = $request->description;
        }
        $subscription->save();
        $subscriptionId = $subscription->id;
        if( ($request->has('features')) && (!empty($request->features)) ){
            foreach($request->features as $key => $val){
                if(!empty($slug)){
                    $subFeature = UserSubscriptionFeatures::where('subscription_id', $subscriptionId)->where('feature_id', $val)->first();
                    if($subFeature){
                        continue;
                    }
                }
                $feature = array(
                    'subscription_id' => $subscriptionId,
                    'feature_id' => $val,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                );
                UserSubscriptionFeatures::insert($feature);
            }
        }
        return redirect()->back()->with('success', 'Subscription has been '.$message.' successfully.');
    }

    /**
     * edit user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editUserSubscription(Request $request, $domain = '', $slug='')
    {
        $subscription = UserSubscriptions::where('slug', $slug)->firstOrFail();
        $subscriptionFeatures = UserSubscriptionFeatures::select('feature_id')->where('subscription_id', $subscription->id)->get();
        $featuresList = SubscriptionFeaturesList::where('type', 'User')->where('status', 1)->get();
        $validities = SubscriptionValidities::where('status', 1)->get();
        $subFeatures = array();
        foreach($subscriptionFeatures as $feature){
            $subFeatures[] = $feature->feature_id;
        }
        $returnHTML = view('backend.subscriptions.edit-userSubscription')->with(['validities'=>$validities, 'features'=>$featuresList, 'subscription' => $subscription, 'subFeatures'=>$subFeatures])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * update user subscription status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserSubscriptionStatus(Request $request, $domain = '', $slug='')
    {
        $subscription = UserSubscriptions::where('slug', $slug)->firstOrFail();
        $subscription->status = $request->status;
        $subscription->save();
        return response()->json(array('success' => true, 'message'=>'Subscription status has been updated.'));
    }

    /**
     * update user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteUserSubscription(Request $request, $domain = '', $slug='')
    {
        try {
            $subscription = UserSubscriptions::where('slug', $slug)->firstOrFail();
            $subscription->delete();
            return redirect()->back()->with('success', 'Subscription has been deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Subscription cannot be deleted.');
        }
    }

    /**
     * Get vendor subscriptions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vendorSubscriptions(Request $request, $domain = '')
    {
        $vendor_subs = VendorSubscriptions::with(['features.feature', 'validity'])->get();
        $featuresList = SubscriptionFeaturesList::where('type', 'Vendor')->where('status', 1)->get();
        $validities = SubscriptionValidities::where('status', 1)->get();
        $features = '';
        if($vendor_subs){
            foreach($vendor_subs as $sub){
                if($sub->features->isNotEmpty()){
                    $subFeaturesList = array();
                    foreach($sub->features as $feature){
                        $subFeaturesList[] = $feature->feature->title;
                    }
                    unset($sub->features);
                    $features = implode(', ', $subFeaturesList);
                }
                $sub->features = $features;
            }
        }
        return view('backend/subscriptions/vendorSubscriptions')->with(['validities'=>$validities, 'features'=>$featuresList, 'vendor_subscriptions'=>$vendor_subs]);
    }

    /**
     * save vendor subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveVendorSubscription(Request $request, $domain = '', $slug='')
    {
        $message = 'added';
        $rules = array(
            'title' => 'required|string|max:50|unique:vendor_subscriptions',
            'features' => 'required',
            'price' => 'required',
            'validity' => 'required'
        );
        if(!empty($slug)){
            $subscription = VendorSubscriptions::where('slug', $slug)->firstOrFail();
            $rules['title'] = $rules['title'].',id,'.$subscription->id;
            $message = 'updated';
        }

        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        if(!empty($slug)){
            $subFeatures = VendorSubscriptionFeatures::where('subscription_id', $subscription->id)->whereNotIn('feature_id', $request->features)->delete();
        }else{
            $subscription = new VendorSubscriptions;
        }
        $subscription->title = $request->title;
        $subscription->slug = strtolower(str_replace(' ', '-', $request->title));
        $subscription->price = $request->price;
        $subscription->validity_id = $request->validity;
        $subscription->status = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $subscription->on_request = ($request->has('on_request') && $request->on_request == 'on') ? 1 : 0;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $subscription->image = Storage::disk('s3')->put($this->folderName, $file,'public');
        }
        if( ($request->has('description')) && (!empty($request->description)) ){
            $subscription->description = $request->description;
        }
        $subscription->save();
        $subscriptionId = $subscription->id;
        if( ($request->has('features')) && (!empty($request->features)) ){
            foreach($request->features as $key => $val){
                if(!empty($slug)){
                    $subFeature = VendorSubscriptionFeatures::where('subscription_id', $subscriptionId)->where('feature_id', $val)->first();
                    if($subFeature){
                        continue;
                    }
                }
                $feature = array(
                    'subscription_id' => $subscriptionId,
                    'feature_id' => $val,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                );
                VendorSubscriptionFeatures::insert($feature);
            }
        }
        return redirect()->back()->with('success', 'Subscription has been '.$message.' successfully.');
    }

    /**
     * edit vendor subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editVendorSubscription(Request $request, $domain = '', $slug='')
    {
        $subscription = VendorSubscriptions::where('slug', $slug)->firstOrFail();
        $subscriptionFeatures = VendorSubscriptionFeatures::select('feature_id')->where('subscription_id', $subscription->id)->get();
        $featuresList = SubscriptionFeaturesList::where('type', 'Vendor')->where('status', 1)->get();
        $validities = SubscriptionValidities::where('status', 1)->get();
        $subFeatures = array();
        foreach($subscriptionFeatures as $feature){
            $subFeatures[] = $feature->feature_id;
        }
        $returnHTML = view('backend.subscriptions.edit-vendorSubscription')->with(['validities'=>$validities, 'features'=>$featuresList, 'subscription' => $subscription, 'subFeatures'=>$subFeatures])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * update vendor subscription status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateVendorSubscriptionStatus(Request $request, $domain = '', $slug='')
    {
        $subscription = VendorSubscriptions::where('slug', $slug)->firstOrFail();
        $subscription->status = $request->status;
        $subscription->save();
        return response()->json(array('success' => true, 'message'=>'Subscription status has been updated.'));
    }

    /**
     * update vendor subscription on request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateVendorSubscriptionOnRequest(Request $request, $domain = '', $slug='')
    {
        $subscription = VendorSubscriptions::where('slug', $slug)->firstOrFail();
        $subscription->on_request = $request->on_request;
        $subscription->save();
        return response()->json(array('success' => true, 'message'=>'Subscription on request status has been updated.'));
    }

    /**
     * update vendor subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteVendorSubscription(Request $request, $domain = '', $slug='')
    {
        try {
            $subscription = VendorSubscriptions::where('slug', $slug)->firstOrFail();
            $subscription->delete();
            return redirect()->back()->with('success', 'Subscription has been deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Subscription cannot be deleted.');
        }
    }
}
