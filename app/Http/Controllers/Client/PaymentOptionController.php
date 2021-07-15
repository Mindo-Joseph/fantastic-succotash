<?php

namespace App\Http\Controllers\Client;

use App\Models\Brand;
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

        $saved_creds = PaymentOption::select('credentials')->where('id', $id)->first();
        
        if( (isset($saved_creds)) && (!empty($saved_creds->credentials)) ){
            $json_creds = $saved_creds->credentials;
        }else{
            $json_creds = NULL;
        }

        if($request->has('active') && $request->active == 'on'){
            $status = 1;
            $msg = $request->method_name .' activated successfully!';
            
            if(strtolower($request->method_name) == 'paypal'){
                $json_creds = json_encode(array(
                    'username' => $request->paypal_username,
                    'password' => $request->paypal_password,
                    'signature' => $request->paypal_signature,
                ));
            }
            else if(strtolower($request->method_name) == 'stripe'){
                $json_creds = json_encode(array(
                    'api_key' => $request->stripe_api_key
                ));
            }
        }
        
        PaymentOption::where('id', $id)->update(['status' => $status, 'credentials' => $json_creds]);

        return redirect()->back()->with('success', $msg);

        
    }

    public function updateAll(Request $request, $domain = '')
    {
        $msg = 'Payment options have been saved successfully!';
        $method_id_arr = $request->input('method_id');
        $method_name_arr = $request->input('method_name');
        $active_arr = $request->input('active');

        foreach ($method_id_arr as $key => $id) {
            $saved_creds = PaymentOption::select('credentials')->where('id', $id)->first();
            if( (isset($saved_creds)) && (!empty($saved_creds->credentials)) ){
                $json_creds = $saved_creds->credentials;
            }else{
                $json_creds = NULL;
            }

            $status = 0;

            if( (isset($active_arr[$id])) && ($active_arr[$id] == 'on') ){
                $status = 1;
                if( (isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'paypal') ){
                    $json_creds = json_encode(array(
                        'username' => $request->paypal_username,
                        'password' => $request->paypal_password,
                        'signature' => $request->paypal_signature,
                    ));
                }
                else if( (isset($method_name_arr[$key])) && (strtolower($method_name_arr[$key]) == 'stripe') ){
                    $json_creds = json_encode(array(
                        'api_key' => $request->stripe_api_key,
                        'publishable_key' => $request->stripe_publishable_key
                    ));
                }
            }
            PaymentOption::where('id', $id)->update(['status' => $status, 'credentials' => $json_creds]);
        }
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
