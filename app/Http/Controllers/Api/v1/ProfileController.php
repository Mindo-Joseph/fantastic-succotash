<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Category, Brand, Client, ClientPreference, ClientLanguage, Product, Country, Currency, ServiceArea, ClientCurrency, UserWishlist, UserAddress};
use Validation;
use DB;
use Illuminate\Support\Facades\Storage;
use Config;
use ConvertCurrency;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ApiResponser;

class ProfileController extends BaseController
{
    private $field_status = 2;
    private $curLang = 0; use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wishlists(Request $request)
    {
        $langId = Auth::user()->language;
        $paginate = $request->has('limit') ? $request->limit : 12;
		$clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();

        $wishList = UserWishlist::with(['product.media.image', 'product.translation' => function($q) use($langId){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    },
                    'product.variant' => function($q) use($langId){
                        $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                        $q->groupBy('product_id');
                    },
                ])->select( "id", "user_id", "product_id")
        		->where('user_id', Auth::user()->id)->paginate($paginate);

    	if(!empty($wishList)){
    		foreach ($wishList as $key => $prod) {
    			if(!empty($prod->product->variant)){
		    		foreach ($prod->product->variant as $k => $vari) {
			            $vari->multiplier = $clientCurrency->doller_compare;
			        }
		    	}
	        }
    	}
    	return response()->json([
        	'data' => $wishList
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWishlist(Request $request, $pid = 0)
    {
        $product = Product::where('id', $pid)->first();
        if(!$product){
            return response()->json(['error' => 'No record found.'], 404);
        }

        $exist = UserWishlist::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();

        if($exist){
            $exist->delete();
            return response()->json([
            	'data' => $product->id,
	            'message' => 'Product has been removed from wishlist.',
	        ]);
        }
        $wishlist = new UserWishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $product->id;
        $wishlist->added_on = Carbon::now();
        $wishlist->save();

        return response()->json([
        	'data' => $product->id,
            'message' => 'Product has been added in wishlist.',
        ]);
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
    public function profile(Request $request)
    {
        $user = User::with('country', 'address')->select('name', 'email', 'phone_number', 'type', 'country_id')
                    ->where('id', Auth::user()->id)->first();

        if(!$user){
            return response()->json(['error' => 'No record found.'], 404);
        }

        return response()->json([
        	'data' => $user,
        ]);
        //return view('forntend/account/profile')->with(['user' => $user, 'navCategories' => $navCategories]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, $domain = '')
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|same:new_password',
        ]);
 
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = $error_value[0];
                return response()->json($errors, 422);
            }
        }

        $current_password = Auth::User()->password;           
        if(!Hash::check($request->current_password, $current_password))
        {
            return response()->json(['error' => 'Password did not matched.'], 404);
        }
        $user_id = Auth::User()->id;                       
        $obj_user = User::find(Auth::User()->id);
        $obj_user->password = Hash::make($request->new_password);
        $obj_user->save(); 
        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|string'
        ]); 
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = $error_value[0];
                return response()->json($errors, 422);
            }
        }

        $img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->avatar));

        $user = User::where('id', Auth::user()->id)->first();

        if(!empty($user->image)){
            Storage::disk('s3')->delete($user->image); 
        }

        $imgType = ($request->has('type')) ? $request->type : 'jpg';
        $imageName = 'profile/'.$user->id.substr(md5(microtime()), 0, 15).'.'.$imgType;

        $save = Storage::disk('s3')->put($imageName, $img, 'public');

        $user->image = $imageName;
        $user->save();
        return response()->json([
            'message' => 'Profile image updated successfully.',
            'data' => $user->image,
            'save' => $save
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $usr = Auth::user()->id; 
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:3|max:50',
            'email'         => 'required|email|max:50||unique:users,email,'.$usr,
            'phone_number'  => 'required|string|min:10|max:15|unique:users,phone_number,'.$usr,
        ]);

        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = $error_value[0];
                return response()->json($errors, 422);
            }
        }

        $prefer = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 
                        'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();

        $user = User::where('id', $usr)->first();

        $user->name = $request->name;
        $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();

        if($user->phone_number != trim($request->phone_number)){

            $phoneCode = mt_rand(100000, 999999);
            $user->phone_number = $request->phone_number;
            $user->is_phone_verified = 0;
            $user->phone_token = $phoneCode;
            $user->phone_token_valid_till = $sendTime;

            if(!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)){

                $provider = $prefer->sms_provider;
                $to = $request->phone_number;
                $body = "Dear ".ucwords($request->phone_number).", Please enter OTP ".$phoneCode." to verify your account.";
                $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                $response['send_otp'] = 1;
            }
        }

        if($user->email != trim($request->email)){

            $emailCode = mt_rand(100000, 999999);
            $user->email = $request->email;
            $user->is_email_verified = 0;
            $user->email_token = $emailCode;
            $user->email_token_valid_till = $sendTime;

            if(!empty($prefer->mail_driver) && !empty($prefer->mail_host) && !empty($prefer->mail_port) && !empty($prefer->mail_port) && !empty($prefer->mail_password) && !empty($prefer->mail_encryption)){

                $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();

                $confirured = $this->setMailDetail($prefer->mail_driver, $prefer->mail_host, $prefer->mail_port, $prefer->mail_username, $prefer->mail_password, $prefer->mail_encryption);

                $client_name = $client->name;
                $mail_from = $prefer->mail_from;
                $sendto = $request->email;
                try{
                    Mail::send('email.verify',[
                            'customer_name' => ucwords($request->name),
                            'code_text' => 'Enter below code to verify yoour account',
                            'code' => 'qweqwewqe',
                            'logo' => $client->logo['original'],
                            'link'=>"link"
                        ],
                        function ($message) use($sendto, $client_name, $mail_from) {
                        $message->from($mail_from, $client_name);
                        $message->to($sendto)->subject('OTP to verify account');
                    });
                    $response['send_email'] = 1;
                }
                catch(\Exception $e){
                    return response()->json(['data' => $response]);
                }
            }
        }
        
        $user->save();

        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['phone_number'] = $user->phone_number;
        $data['is_phone_verified'] = $user->is_phone_verified;
        $data['is_email_verified'] = $user->is_email_verified;

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => $data
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addressBook($id = '')
    {
        $address = UserAddress::where('user_id', Auth::user()->id);

        if($id > 0){
            $address = $address->where('id', $id);
        }

        $address = $address->orderBy('is_primary', 'desc')
                    ->orderBy('id', 'desc')->get();

        return response()->json([
            'data' => $address,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userAddress(Request $request, $addressId = 0)
    {
        try {
            $validator = Validator::make($request->all(), [
                'address' => 'required',
                'country' => 'required',
            ]);
            $user = Auth::user();

            if($validator->fails()){
                foreach($validator->errors()->toArray() as $error_key => $error_value){
                    $errors['error'] = $error_value[0];
                    return response()->json($errors, 422);
                }
            }

            if($request->has('is_primary') && $request->is_primary == 1){
                $add = UserAddress::where('user_id', $user->id)->update(['is_primary' => 0]);
            }

            $address = UserAddress::where('id', $addressId)->where('user_id', $user->id)->first();
            $message = "Address updated successfully.";
            if(!$address){
                $message = "Address added successfully.";
                $address = new UserAddress();
                $address->user_id = $user->id;
                $address->is_primary = $request->has('is_primary') ? 1 : 0;
            }
            foreach ($request->only('address', 'street', 'city', 'state', 'latitude', 'longitude', 'pincode', 'phonecode', 'country_code', 'country') as $key => $value) {
                $address[$key] = $value;
            }
            $request->type == ($request->has('address_type') && $request->address_type < 3) ? $request->address_type : 3;
            
            $address->save();

            return $this->successResponse($address, $message);
        }catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**     Update primary address         */
    public function primaryAddress($addressId = 0)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('id', $addressId)->where('user_id', $user->id)->first();

            if(!$address){
                return $this->errorResponse('Address not found.', 404);
            }
            $add = UserAddress::where('user_id', $user->id)->update(['is_primary' => 0]);
            $add = UserAddress::where('user_id', $user->id)->where('id', $addressId)->update(['is_primary' => 1]);

            return $this->successResponse('', 'Address is set as primary address successfully.');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAddress($addressId = 0)
    {
        try {
            $address = UserAddress::where('id', $addressId)->where('user_id', Auth::user()->id)->first();

            if(!$address){
                return $this->errorResponse('Address not found.', 404);
            }
            $address->delete();
            return $this->successResponse('', 'Address deleted successfully.');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}