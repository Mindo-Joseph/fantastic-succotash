<?php

namespace App\Http\Controllers\client;

use DB;
use Session;
use \DateTimeZone;
use App\Jobs\UpdateClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ProcessClientDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, SmsProvider, Currency, Language, Country, Order, User, Vendor, UserSubscriptions, UserSubscriptionFeatures, SubscriptionFeaturesList, SubscriptionValidities};
use Carbon\Carbon;

class SubscriptionController extends BaseController
{
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
        $user_subs = UserSubscriptions::get();
        $featuresList = SubscriptionFeaturesList::where('type', 'User')->where('status', 1)->get();
        $validities = SubscriptionValidities::where('status', 1)->get();
        return view('backend/subscriptions/userSubscriptions')->with(['validities'=>$validities, 'features'=>$featuresList, 'user_subscriptions'=>$user_subs]);
    }

    /**
     * save user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveUserSubscription(Request $request, $domain = '')
    {
        $rules = array(
            'title' => 'required|string|max:50|unique:user_subscriptions',
            'features' => 'required',
            'price' => 'required',
            'validity' => 'required'
        );
        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $subscription = new UserSubscriptions;
        $subscription->title = $request->title;
        $subscription->slug = strtolower(str_replace(' ', '-', $request->title));
        $subscription->price = $request->price;
        $subscription->validity_id = $request->validity;
        $subscription->status = ($request->has('status') && $request->status == 'on') ? 1 : 0;
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
                $feature = array(
                    'subscription_id' => $subscriptionId,
                    'feature_id' => $val
                );
                UserSubscriptionFeatures::insert($feature);
            }
        }
        return redirect()->back()->with('success', 'Subscription has been successfully added');
    }

    /**
     * edit user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editUserSubscription(Request $request, $domain = '', $slug='')
    {
        dd($request->all());
    }

    /**
     * update user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserSubscription(Request $request, $domain = '', $slug='')
    {
        dd($request->all());
    }

    /**
     * Get vendor subscriptions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vendorSubscriptions(Request $request, $domain = '')
    {
        return view('backend/subscriptions/vendorSubscriptions');
    }
}
