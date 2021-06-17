<?php

namespace App\Http\Controllers\Front;

use App\Models\{UserWishlist, User, Product, UserAddress, UserRefferal, ClientPreference, Client, Order};
use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use Carbon\Carbon;
use Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Hash;

class ProfileController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wishlists()
    {
        
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);

        $wishList = UserWishlist::with(['product.media.image', 'product.translation' => function($q) use($langId){
            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'product.variant' => function($q) use($langId){
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select( "id", "user_id", "product_id")
        ->where('user_id', Auth::user()->id)->get();
        
        if(!empty($wishList)){
            $wishList = $wishList->toArray();
        }
       return view('frontend/account/wishlist')->with(['navCategories' => $navCategories, 'wishList' => $wishList]);
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

        return view('frontend/account/sendRefferal')->with(['navCategories' => $navCategories]);
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
                        'link' => "http://local.myorder.com/user/register?refferal_code=".$otp,
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeWishlist($domain = '', Request $request, $sku)
    {
        $product = Product::where('sku', $sku)->firstOrFail();

        $exist = UserWishlist::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();

        if($exist){
            $exist->delete();
            return redirect()->route('user.wishlists');
        }
        $wishlist = new UserWishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $product->id;
        $wishlist->added_on = Carbon::now();
        $wishlist->save();

        return redirect()->route('user.wishlists');   
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
        return view('frontend/account/addressbook')->with(['useraddress' => $useraddress, 'navCategories' => $navCategories]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
       return view('frontend/account/orders')->with(['navCategories' => $navCategories]);
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
        return view('frontend/account/profile')->with(['user' => $user, 'navCategories' => $navCategories, 'userRefferal' => $refferal_code]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
       return view('frontend/account/changePassword')->with(['navCategories' => $navCategories]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function submitChangePassword(Request $request, $domain = '')
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                $errors['error'] = $error_value[0];
                return redirect()->back()->with($errors);
            }
        }
        
        $user = User::where('id', Auth::user()->id)->first();
        if ($user) {
        $user->password = Hash::make($request['new_password']);
        $user->save();
        }
        return redirect()->route('user.profile');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request, $domain = '')
    {
    }

    /** User account information        */
    public function accountInformation(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $user = User::with('country')->find(Auth::user()->id);
        // dd($useraddress[0]->country->toArray());
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/accountInformation')->with(['user' => $user, 'navCategories' => $navCategories]);
    }

    

}
