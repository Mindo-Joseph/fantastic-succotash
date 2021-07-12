<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\User;
use DB;
use App;
use Config;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client as TwilioClient;
use App\Models\{Client, Category, Product, ClientPreference,UserDevice,UserLoyaltyPoint, Wallet};

class FrontController extends Controller
{
    private $field_status = 2;
    protected function sendSms($provider, $sms_key, $sms_secret, $sms_from, $to, $body){
        $to = "+918950473361";
        try{
            $client = new TwilioClient($sms_key, $sms_secret);
            $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
        }
        catch(\Exception $e){
            return '2';
        }
        return '1';
    }
    public function categoryNav($lang_id) {
        $preferences = Session::get('preferences');
        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                        ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name')->distinct('categories.id');
        $status = $this->field_status;
        if($preferences){
            if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
                $vendors = (Session::has('vendors')) ? Session::get('vendors') : array();
                $categories = $categories->leftJoin('vendor_categories as vct', 'categories.id', 'vct.category_id')
                            ->where(function($q1) use($vendors, $status, $lang_id) {
                                $q1->whereIn('vct.vendor_id', $vendors)
                                ->where('vct.status', 1)
                                ->where('categories.id', '>', '1')
                                ->where('categories.is_visible', 1)
                                ->where('categories.status', '!=', $status)
                                ->where('cts.language_id', $lang_id);
                            });
            }
        }        
        $categories = $categories->orWhere(function($q2) use($status, $lang_id) {
                        $q2->whereIn('categories.type_id', [4,5])
                        ->where('categories.id', '>', '1')
                        ->where('categories.is_visible', 1)
                        ->where('categories.status', '!=', $status)
                        ->where('cts.language_id', $lang_id);
                    })
                    ->orderBy('categories.position', 'asc')
                    ->orderBy('categories.id', 'asc')
                    ->orderBy('categories.parent_id', 'asc')->get();
        if($categories){
            $categories = $this->buildTree($categories->toArray());
        }
        return $categories;
    }

    public function buildTree($elements, $parentId = 1) {
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
        $products = Product::with(['media' => function($q){
                            $q->groupBy('product_id');
                        }, 'media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating');
        
                    if($where !== ''){
            $products = $products->where($where, 1);
        }
        // if(is_array($venderIds) && count($venderIds) > 0){
        if(is_array($venderIds)){
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        $products = $products->where('is_live', 1)->take(6)->get();

        if(!empty($products)){
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
            if($userFind){
                $cart = Cart::where('user_id', $userFind->id)->first();
                if($cart){
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
    public function userMetaData($userid, $device_type = 'web', $device_token = 'web'){
        $device = UserDevice::where('user_id', $userid)->first();
        if(!$device){
            $user_device[] = [
                'user_id' => $userid,
                'device_type' => $device_type,
                'device_token' => $device_token,
                'access_token' => ''
            ];
            UserDevice::insert($user_device);
        }
        $loyaltyPoints = UserLoyaltyPoint::where('user_id', $userid)->first();
        if(!$loyaltyPoints){
            $loyalty[] = [
                'user_id' => $userid,
                'points' => 0
            ];
            UserLoyaltyPoint::insert($loyalty);
        }
        $wallet = Wallet::where('user_id', $userid)->first();
        if(!$wallet){
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
    public function randomData($table, $digit, $where){
        $random_string = substr(md5(microtime()), 0, $digit);
        // after creating, check if string is already used

        while(\DB::table($table)->where($where, $random_string)->exists()){
            $random_string = substr(md5(microtime()), 0, $digit);
        }
        return $random_string;
    }

    public function randomBarcode($table){
        $barCode = substr(md5(microtime()), 0, 14);
        // $number = mt_rand(1000000000, 9999999999);

        while( \DB::table($table)->where('card_qr_code', $barCode)->exists()){
            $barCode = substr(md5(microtime()), 0, 14);
        }
        return $barCode;
    }
}