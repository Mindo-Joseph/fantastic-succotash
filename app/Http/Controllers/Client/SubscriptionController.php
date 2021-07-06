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
use App\Models\{Client, ClientPreference, SmsProvider, Currency, Language, Country, Order, User, Vendor};
use Carbon\Carbon;

class SubscriptionController extends BaseController
{
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
        return view('backend/subscriptions/userSubscriptions');
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
