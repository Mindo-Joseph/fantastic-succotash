<?php

namespace App\Http\Controllers\Front;

use App\Models\{UserWishlist, User, Product, UserAddress};
use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use Carbon\Carbon;
use Auth;
use Session;

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

        //dd($user->toArray());
        $navCategories = $this->categoryNav($langId);
        return view('forntend/account/profile')->with(['user' => $user, 'navCategories' => $navCategories]);
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
