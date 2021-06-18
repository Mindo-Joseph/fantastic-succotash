<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use App\Models\{UserWishlist, Product};
use Carbon\Carbon;
use Auth;
use Session;

class WishlistController extends FrontController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wishlists(){
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

}
