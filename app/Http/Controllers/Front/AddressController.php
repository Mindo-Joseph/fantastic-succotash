<?php

namespace App\Http\Controllers\Front;

use App\Models\{Country, UserWishlist, User, Product, UserAddress};
use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use Carbon\Carbon;
use Auth;
use Session;

class AddressController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $domain = '')
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
    public function add(Request $request, $domain = '')
    {
        $address = new UserAddress();
        $langId = Session::get('customerLanguage');
        $countries = Country::all();
        $navCategories = $this->categoryNav($langId);
        return view('forntend/account/editAddress')->with(['navCategories' => $navCategories,'countries' => $countries, 'address' => $address]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '')
    {
        // dd($request->all());
        $address = new UserAddress;
        $address->user_id = Auth::user()->id;
        $address->address = $request->address;
        $address->street = $request->street;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->country_id = $request->country;
        $address->pincode = $request->pincode;
        $address->type = $request->type;
        $address->save();
        
        return redirect()->route('user.addressBook');
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update($domain = '', Request $request, $id)
    {
        $address = UserAddress::find($id);
        $address->address = $request->address;
        $address->street = $request->street;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->country_id = $request->country;
        $address->pincode = $request->pincode;
        $address->type = $request->type;
        $address->save();
        
        return redirect()->route('user.addressBook');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $address = UserAddress::findOrFail($id);
        $langId = Session::get('customerLanguage');
        $countries = Country::all();
        $navCategories = $this->categoryNav($langId);
        return view('forntend/account/editAddress')->with(['navCategories' => $navCategories,'countries' => $countries, 'address' => $address]);
    }

   
    /**
     * Set Primary Address for user
     *
     * @return \Illuminate\Http\Response
     */
    public function setPrimaryAddress($domain = '', $id)
    {
        $address = UserAddress::where('user_id', Auth::user()->id)->update(['is_primary' => 0]);

        $address = UserAddress::where('user_id', Auth::user()->id)->where('id', $id)->update(['is_primary' => 1]);

        return redirect()->route('user.addressBook');
    }

    /**
     * delete address of user
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($domain = '', $id)
    {
        $address = UserAddress::find($id)->delete();
        return redirect()->route('user.addressBook');
    }
   

}
