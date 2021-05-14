<?php

namespace App\Http\Controllers\Front;

use App\Models\{UserWishlist, User, Product, UserAddress, UserRefferal, ClientPreference, Client};
use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use Carbon\Carbon;
use Auth;
use Session;
use Illuminate\Support\Facades\Mail; 

class ProfileController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wishlists()
    {
        //
    }

    /**
     * Display send refferal page
     *
     * @return \Illuminate\Http\Response
     */
    public function showRefferal()
    {
        
        $langId = Session::get('customerLanguage');
       
        $navCategories = $this->categoryNav($langId);

        return view('forntend/account/sendRefferal')->with(['navCategories' => $navCategories]);
    }

     /**
     * Send Refferal Code
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendRefferalCode(Request $request)
    {
        // dd($request->all());

        $rae = UserRefferal::where('user_id', Auth::user()->id)->first()->toArray();
        // dd($rae['refferal_code']);
        $otp = $rae['refferal_code'];

        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();

        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {

            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);

            $client_name = $client->name;
            $mail_from = $data->mail_from;
            $sendto = $request->email;
            try {
                Mail::send(
                    'email.verify',
                    [
                        'customer_name' => "Link from ".Auth::user()->name,
                        'code_text' => 'Register yourself using this refferal code below to get bonus offer',
                        'code' => $otp,
                        'logo' => $client->logo['original'],
                        'link' => "http://local.myorder.com/user/register"
                    ],
                    function ($message) use ($sendto, $client_name, $mail_from) {
                        $message->from($mail_from, $client_name);
                        $message->to($sendto)->subject('OTP to verify account');
                    }
                );
                $notified = 1;
            } catch (\Exception $e) {
                // $user->save();
            }
        }
        return response()->json(array('success' => true, 'message' => 'Send Successfully'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWishlist(Request $request)
    {
        $product = Product::where('sku', $request->sku)->firstOrFail();

        $exist = UserWishlist::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();

        if($exist){
            $exist->delete();
            return response()->json(array('error' => true, 'message'=> 'Product has been removed from wishlist.'));
        }
        $wishlist = new UserWishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $product->id;
        $wishlist->added_on = Carbon::now();
        $wishlist->save();

        return response()->json(array('success' => true, 'message' => 'Product has been added in wishlist.'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addresBook(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $useraddress = UserAddress::where('user_id', Auth::user()->id)->with('country')->get();
        // dd($useraddress[0]->country->toArray());
        $navCategories = $this->categoryNav($langId);
        return view('forntend/account/addressbook')->with(['useraddress' => $useraddress, 'navCategories' => $navCategories]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request, $domain = '')
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newsLetter(Request $request, $domain = '')
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $user = User::with('country', 'address')->select('name', 'email', 'phone_number', 'type', 'country_id')
                    ->where('id', Auth::user()->id)->first();

        $refferal_code = UserRefferal::where('user_id', Auth::user()->id)->first();
        $navCategories = $this->categoryNav($langId);
        return view('forntend/account/profile')->with(['user' => $user, 'navCategories' => $navCategories, 'userRefferal' => $refferal_code]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, $domain = '')
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request, $domain = '')
    {
    }

}
