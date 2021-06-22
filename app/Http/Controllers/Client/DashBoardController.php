<?php

namespace App\Http\Controllers\Client;

use DB;
use Session;
use \DateTimeZone;
use App\Jobs\UpdateClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ProcessClientDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, MapProvider, SmsProvider, Template, Currency, Language, Country, Order, User};

class DashBoardController extends BaseController{

    private $folderName = 'Clientlogo';
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){   
        $total_revenue = Order::sum('payable_amount');
        return view('backend/dashboard')->with(['total_revenue' => $total_revenue]);
    }

    public function profile(){
        $countries = Country::all();
        $client = Client::where('code', Auth::user()->code)->first();
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('backend/setting/profile')->with(['client' => $client, 'countries'=> $countries,'tzlist'=>$tzlist]);
    }

    public function changePassword(Request $request){
        $client = User::where('id', Auth::id())->first();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        if (Hash::check($request->old_password, $client->password)) {
            $client->password = Hash::make($request->password);
            $client->save();
            $clientData = 'empty';
            return redirect()->back()->with('success', 'Password Changed successfully!');
        } else {
            $request->session()->flash('error', 'Wrong Old Password');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request, $domain = '', $id)
    {
        $user = Auth::user();
        $client = Client::where('code', $user->code)->firstOrFail();
        $rules = array(
            'name' => 'required|string|max:50',
            'phone_number' => 'required|digits:10',
            'company_name' => 'required',
            'company_address' => 'required',
            'country_id' => 'required',
            'timezone' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $data = array();
        foreach ($request->only('name', 'phone_number', 'company_name', 'company_address', 'country_id', 'timezone') as $key => $value) {
            $data[$key] = $value;
        }
        $client = Client::where('code', Auth::user()->code)->first();
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $file_name = 'Clientlogo/'.uniqid() .'.'.  $file->getClientOriginalExtension();
            $path = Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
            $data['logo'] = $file_name;
        }else{
             $data['logo'] = $client->getRawOriginal('logo');
        }
        $client = Client::where('code', Auth::user()->code)->update($data);
        $userdata = array();
        foreach ($request->only('name','phone_number','timezone') as $key => $value) {
            $userdata[$key] = $value;
        }
        $user = User::where('id', Auth::id())->update($userdata);
        return redirect()->back()->with('success', 'Client Updated successfully!');
    }
}
