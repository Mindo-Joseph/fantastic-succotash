<?php

namespace App\Http\Controllers\Api\v1;

use App;
use Mail;
use Config;
use Session;
use ConvertCurrency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client as TwilioClient;
use App\Models\{Client, Category, Product, ClientPreference, Wallet, UserLoyaltyPoint, LoyaltyCard, Order};

class BaseController extends Controller{
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

    public function categoryNav($lang_id) {
        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                        ->leftjoin('types', 'types.id', 'categories.type_id')
                        ->select('categories.id', 'categories.icon', 'categories.image', 'categories.slug', 'categories.parent_id', 'cts.name', 'categories.warning_page_id', 'categories.template_type_id', 'types.title as redirect_to')
                        ->where('categories.id', '>', '1')
                        ->where('categories.is_visible', 1)
                        ->where('categories.status', '!=', $this->field_status)
                        ->where('categories.is_core', 1)
                        ->where('cts.language_id', $lang_id)
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();
        if($categories){
            $categories = $this->buildTree($categories->toArray());
        }
        return $categories;
    }

    protected function in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y){
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
        ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) {
            $c = !$c;
        }
      }
      return $c;
    }

    protected function contains($point, $polygon){
        if($polygon[0] != $polygon[count($polygon)-1]){
            $polygon[count($polygon)] = $polygon[0];
            $j = 0;
            $oddNodes = false;
            $x = $point[1];
            $y = $point[0];
            $n = count($polygon);
            for ($i = 0; $i < $n; $i++){
                $j++;
                if ($j == $n){
                    $j = 0;
                }
                if ((($polygon[$i]['lat'] < $y) && ($polygon[$j]['lat'] >= $y)) || (($polygon[$j]['lat'] < $y) && ($polygon[$i]['lat'] >=
                    $y))){
                    if ($polygon[$i]['lng'] + ($y - $polygon[$i]['lat']) / ($polygon[$j]['lat'] - $polygon[$i]['lat']) * ($polygon[$j]['lng'] -
                        $polygon[$i]['lng']) < $x)
                    {
                        $oddNodes = !$oddNodes;
                    }
                }
            }
        }
        return $oddNodes;
    }

    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'XXXXXX';
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        dd($response);

    }

    protected function changeCurrency($curr, $price)
    {
        $currency = ConvertCurrency::convert('USD',[$curr], $price);
        return $currency[0]['convertedAmount'];
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
    }

    /**     * check if cookie already exist     */
    public function checkCookies($userid)
    {
        if (isset(Auth::user()->system_user) && !empty(Auth::user()->system_user)) {

            $userFind = User::where('system_id', Auth::user()->system_user)->first();
            if($userFind){
                $cart = Cart::where('user_id', $userFind->id)->first();
                if($cart){
                    $cart->user_id = $userid;
                    $cart->save();
                }
                $userFind->delete();
            }
        }
        return $userid;
    }

    /**     * check if cookie already exist     */
    public function getLoyaltyPoints($userid, $multiplier){
        $loyalty_earned_amount = 0;
        $order_loyalty_points_earned_detail = Order::where('user_id', $userid)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
        if ($order_loyalty_points_earned_detail) {
            $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
            if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
            }
        }
        return $loyalty_earned_amount;
    }

    /**     * check if cookie already exist     */
    public function getWallet($userid, $multiplier, $currency = 147)
    {
        $wallet = Wallet::where('user_id', $userid)->first();
        if(!$wallet){
            $wallet = new Wallet();
            $wallet->user_id = $userid;
            $wallet->type = 1;
            $wallet->balance = 0;
            $wallet->card_id = $this->randomData('wallets');
            $wallet->card_qr_code = $this->randomBarcode('wallets');
            $wallet->meta_field = '';
            $wallet->currency_id = $currency;
            $wallet->save();
        }
        $balance = $wallet->balance * $multiplier;
        return $balance;
    }

    /* Create random and unique client code*/
    public function randomData($table){
        $random_string = substr(md5(microtime()), 0, 6);
        // after creating, check if string is already used
        while(\DB::table($table)->where('refferal_code', $random_string)->exists()){
            $random_string = substr(md5(microtime()), 0, 6);
        }
        return $random_string;
    }

    public function randomBarcode($table){
        $barCode = substr(md5(microtime()), 0, 14);
        while( \DB::table($table)->where('card_qr_code', $barCode)->exists()){
            $barCode = substr(md5(microtime()), 0, 14);
        }
        return $barCode;
    }

    /**     * check if cookie already exist     */
    public function userMetaData($userid, $device_type = 'web', $device_token = 'web', $currency = 147){
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
                'card_id' => $this->randomData('wallets'),
                'card_qr_code' => $this->randomBarcode('wallets'),
                'meta_field' => '',
                'currency_id' => $currency,
            ];
            Wallet::insert($walletData);
        }
        return 1;
    }

}