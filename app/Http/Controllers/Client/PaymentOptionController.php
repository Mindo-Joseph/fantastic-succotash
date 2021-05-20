<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use App\Models\{Client, ClientPreference, PaymentOption};
use Illuminate\Support\Facades\Storage;

class PaymentOptionController extends BaseController
{
    private $folderName = 'payoption';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $code = array('COD', 'wallet', 'layalty-points', 'paypal', 'stripe');
        $payOption = PaymentOption::whereIn('code', $code)->get();
        return view('backend/payoption/index')->with(['payOption' => $payOption]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $status = 0;
        $msg = $request->method_name .' deactivated successfully!';
        if($request->has('active') && $request->active == 'on'){
            $status = 1;
            $msg = $request->method_name .' activated successfully!';
        }
        PaymentOption::where('id', $id)->update(['status' => $status]);

        return redirect()->back()->with('success', $msg);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        $brand = Brand::where('id', $id)->first();
        $brand->status = 2;
        $brand->save();
        return redirect()->back()->with('success', 'Brand deleted successfully!');
    }

    /**
     * save the order of variant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function updateOrders(Request $request)
    {
        $arr = explode(',', $request->orderData);
        foreach ($arr as $key => $value) {
            $brand = Brand::where('id', $value)->first();
            if($brand){
                $brand->position = $key + 1;
                $brand->save();
            }
        }
        return redirect('client/category')->with('success', 'Brand order updated successfully!');
    }
}
