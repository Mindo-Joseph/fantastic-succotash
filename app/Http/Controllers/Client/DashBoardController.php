<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Client, ClientPreference, MapProvider, SmsProvider, Template, Currency, Language, Country};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Support\Str;
use App\Jobs\ProcessClientDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Session;
use Illuminate\Support\Facades\Storage;
use App\Jobs\UpdateClient;
use \DateTimeZone;

class DashBoardController extends BaseController
{
    private $folderName = 'Clientlogo';
    public function __construct(){
        
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('backend/dashboard');
    }

    public function profile()
    {
        $client = Client::where('code', Auth::user()->code)->first();
        $countries = Country::all();
        //dd($client->toArray());
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('backend/setting/profile')->with(['client' => $client, 'countries'=> $countries,'tzlist'=>$tzlist]);
    }

    public function changePassword(Request $request)
    {
        $client = Client::where('code', Auth::user()->code)->first();

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
            $this->dispatchNow(new UpdateClient($clientData, $client->password));
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
        $client = Client::where('code', Auth::user()->code)->firstOrFail();

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
         $data['logo'] = Auth::user()->logo;

        if ($request->hasFile('logo')) {    /* upload logo file */
            $file = $request->file('logo');
            //$file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            //$path = $request->file('logo')->storeAs('/Clientlogo', $file_name, 'public');

            $getFileName = Storage::disk('s3')->put($this->folderName, $file,'public');
            $data['logo'] = $getFileName;
        }

        $client = Client::where('code', Auth::user()->code)->update($data);
        
        $this->dispatchNow(new UpdateClient($data, $pass = ''));

        return redirect()->back()->with('success', 'Client Updated successfully!');
    }
}
