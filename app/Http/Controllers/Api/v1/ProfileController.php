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

class ProfileController extends BaseController
{
    private $field_status = 2;
    private $curLang = 0;

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


    	if(!empty($wishList->product)){
    		foreach ($wishList->product as $key => $product) {
    			if(!empty($wishList->product)){
		    		foreach ($product->variant as $k => $vari) {
			            $vari[$k]->multiplier = $clientCurrency->doller_compare;
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
    public function addressBook($id = '')
    {
    	$user = UserAddress::where('user_id', Auth::user()->id);
    	if($id > 0){
    		$user = $user->where('id', $id);
    	}
    	$user = $user->first();

        if(!$user){
            return response()->json(['error' => 'No record found.'], 404);
        }

        return response()->json([
        	'data' => $user,
        ]);
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
        $obj_user->password = Hash::make($request_data['new_password']);
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
}