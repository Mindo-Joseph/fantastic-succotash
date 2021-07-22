<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\User;
use DB;
use App;
use Auth;
use Config;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client as TwilioClient;
use App\Models\{Client, Category, Product, ClientPreference, UserDevice, UserLoyaltyPoint, Wallet, UserSavedPaymentMethods, SubscriptionInvoicesUser};

class FrontController extends Controller
{
    private $field_status = 2;
    protected function sendSms($provider, $sms_key, $sms_secret, $sms_from, $to, $body)
    {
        try {

            $client = new TwilioClient($sms_key, '1c649b9207c16c58cd610654ac81025f');
            $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
        } catch (\Exception $e) {
            return '2';
        }
        return '1';
    }
    public function categoryNav($lang_id)
    {
        $preferences = Session::get('preferences');
        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
            ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name')->distinct('categories.id');
        $status = $this->field_status;
        if ($preferences) {
            if ((isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1)) {
                $vendors = (Session::has('vendors')) ? Session::get('vendors') : array();
                $categories = $categories->leftJoin('vendor_categories as vct', 'categories.id', 'vct.category_id')
                    ->where(function ($q1) use ($vendors, $status, $lang_id) {
                        $q1->whereIn('vct.vendor_id', $vendors)
                            ->where('vct.status', 1)
                            ->orWhere(function ($q2) {
                                $q2->whereIn('categories.type_id', [4, 5]);
                            });
                    });
            }
        }
        $categories = $categories->where('categories.id', '>', '1')
            ->where('categories.is_visible', 1)
            ->where('categories.status', '!=', $status)
            ->where('cts.language_id', $lang_id)
            ->orderBy('categories.position', 'asc')
            ->orderBy('categories.id', 'asc')
            ->orderBy('categories.parent_id', 'asc')->get();
        if ($categories) {
            $categories = $this->buildTree($categories->toArray());
        }
        return $categories;
    }

    public function buildTree($elements, $parentId = 1)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function productList($venderIds, $langId, $currency = 'USD', $where = '')
    {
        $products = Product::with([
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode')->orderBy('price');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');

        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        // if(is_array($venderIds) && count($venderIds) > 0){
            if (is_array($venderIds)) {
                $products = $products->whereIn('vendor_id', $venderIds);
            }
            $products = $products->where('is_live', 1)->take(6)->get();
        // pr($products->toArray());die;          
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                }
            }
        }
        return $products;
    }

    public function setMailDetail($mail_driver, $mail_host, $mail_port, $mail_username, $mail_password, $mail_encryption)
    {
        $config = array(
            'driver' => $mail_driver,
            'host' => $mail_host,
            'port' => $mail_port,
            'encryption' => $mail_encryption,
            'username' => $mail_username,
            'password' => $mail_password,
            'sendmail' => '/usr/sbin/sendmail -bs',
            'pretend' => false,
        );

        Config::set('mail', $config);
        $app = App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');
        return '1';

        // return '2';
    }

    /**     * check if cookie already exist     */
    public function checkCookies($userid)
    {
        if (isset($_COOKIE['uuid'])) {
            $userFind = User::where('system_id', Auth::user()->system_user)->first();
            if ($userFind) {
                $cart = Cart::where('user_id', $userFind->id)->first();
                if ($cart) {
                    $cart->user_id = $userid;
                    $cart->save();
                }
                $userFind->delete();
            }
            setcookie("uuid", "", time() - 3600);
            return redirect()->route('user.checkout');
        }
    }

    /**     * check if cookie already exist     */
    public function userMetaData($userid, $device_type = 'web', $device_token = 'web')
    {
        $device = UserDevice::where('user_id', $userid)->first();
        if (!$device) {
            $user_device[] = [
                'user_id' => $userid,
                'device_type' => $device_type,
                'device_token' => $device_token,
                'access_token' => ''
            ];
            UserDevice::insert($user_device);
        }
        $loyaltyPoints = UserLoyaltyPoint::where('user_id', $userid)->first();
        if (!$loyaltyPoints) {
            $loyalty[] = [
                'user_id' => $userid,
                'points' => 0
            ];
            UserLoyaltyPoint::insert($loyalty);
        }
        $wallet = Wallet::where('user_id', $userid)->first();
        if (!$wallet) {
            $walletData[] = [
                'user_id' => $userid,
                'type' => 1,
                'balance' => 0,
                'card_id' => $this->randomData('wallets', 6, 'card_id'),
                'card_qr_code' => $this->randomBarcode('wallets'),
                'meta_field' => '',
            ];

            Wallet::insert($walletData);
        }
        return 1;
    }

    /* Create random and unique client code*/
    public function randomData($table, $digit, $where)
    {
        $random_string = substr(md5(microtime()), 0, $digit);
        // after creating, check if string is already used

        while (\DB::table($table)->where($where, $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, $digit);
        }
        return $random_string;
    }

    public function randomBarcode($table)
    {
        $barCode = substr(md5(microtime()), 0, 14);
        // $number = mt_rand(1000000000, 9999999999);

        while (\DB::table($table)->where('card_qr_code', $barCode)->exists()) {
            $barCode = substr(md5(microtime()), 0, 14);
        }
        return $barCode;
    }

    /* Save user payment method */
    public function saveUserPaymentMethod($request)
    {
        $payment_method = new UserSavedPaymentMethods;
        $payment_method->user_id = Auth::user()->id;
        $payment_method->payment_option_id = $request->payment_option_id;
        $payment_method->card_last_four_digit = $request->card_last_four_digit;
        $payment_method->card_expiry_month = $request->card_expiry_month;
        $payment_method->card_expiry_year = $request->card_expiry_year;
        $payment_method->customerReference = ($request->has('customerReference')) ? $request->customerReference : NULL;
        $payment_method->cardReference = ($request->has('cardReference')) ? $request->cardReference : NULL;
        $payment_method->save();
    }

    /* Get Saved user payment method */
    public function getSavedUserPaymentMethod($request)
    {
        $saved_payment_method = UserSavedPaymentMethods::where('user_id', Auth::user()->id)
                        ->where('payment_option_id', $request->payment_option_id)->first();
        return $saved_payment_method;
    }

    public function sendMailToSubscribedUsers(){
        $after7days = Carbon::now()->addDays(7)->toDateString();
        $now = Carbon::now()->toDateString();
        $active_subscriptions = SubscriptionInvoicesUser::with(['plan', 'features.feature', 'user'])
                                ->whereBetween('end_date', [$now, $after7days])
                                ->whereNull('cancelled_at')->get();
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();

        foreach($active_subscriptions as $subscription){
            if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
                $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
                $client_name = $client->name;
                $mail_from = $data->mail_from;
                $sendto = $subscription->user->email;
                try{
                    $data = [
                        'customer_name' => $subscription->user->name,
                        'code_text' => '',
                        'logo' => $client->logo['original'],
                        'frequency' => $subscription->frequency,
                        'end_date' => $subscription->end_date,
                        'link'=> "http://local.myorder.com/user/subscription/select/".$subscription->plan->slug,
                    ];
                    Mail::send('email.notifyUserSubscriptionBilling', ['mailData'=>$data],
                    function ($message) use($sendto, $client_name, $mail_from) {
                        $message->from($mail_from, $client_name);
                        $message->to($sendto)->subject('Upcoming Subscription Billing');
                    });
                    $response['send_email'] = 1;
                }
                catch(\Exception $e){
                    return response()->json(['data' => $e->getMessage()]);
                }
            }
        }
    }
}
