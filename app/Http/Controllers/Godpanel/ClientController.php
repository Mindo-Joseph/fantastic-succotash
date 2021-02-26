<?php

namespace App\Http\Controllers\Godpanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{ClientPreference, Currency, Client};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Jobs\{ProcessClientDatabase, EditClient};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Session;
use Illuminate\Support\Facades\Storage; 

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::where('is_deleted', 0)->orderBy('created_at', 'DESC')->paginate(10);
        return view('godpanel/client')->with(['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('godpanel/client-form');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);
        return view('godpanel/client-form')->with('client', $client);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = new Client();

        $validation  = Validator::make($request->all(), $client->rules());

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $data = $this->saveClient($request, $client, 'false');

        if(!$data){
            return redirect()->back()->withErrors(['error' => "Something went wrong."]);
        }
        $database_name = preg_replace('/\s+/', '', $request->database_name);
        Cache::set($database_name, $data);
        $this->dispatchNow(new ProcessClientDataBase($data->id));
        return redirect()->route('client.index')->with('success', 'Client Added successfully!');  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validation  = Validator::make($request->all(), $client->rules($client->id));

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $save = $this->saveClient($request, $client, 'true');

        if(!$save){
            return redirect()->back()->withErrors(['error' => "Something went wrong."]);
        }
        $this->dispatchNow(new EditClient($client->id));
        return redirect()->route('client.index')->with('success', 'Client Updated successfully!');
    }

    /* save and update client information */
    public function saveClient(Request $request, Client $client, $update = 'false')
    {
        foreach ($request->only('name', 'phone_number', 'company_name', 'company_address', 'custom_domain') as $key => $value) {
            $client->{$key} = $value;
        }
 
        if( $update == 'false'){
            $client->logo = 'default/default_logo.png';
            $client->database_path = '';
            $client->database_username = env('DB_USERNAME');
            $client->database_password = env('DB_PASSWORD');
           
            $client->email = $request->email;
            $client->database_name = $request->database_name;
            $client->password = Hash::make($request->encpass);
            $client->encpass = $request->encpass;
            $client->code = $this->randomString();
            $client->country_id = $request->country ? $request->country : NULL;
            $client->timezone = $request->timezone ? $request->timezone : NULL;
        }
        $isPasswordUpdate = 0;
        if($update == 'true'){
            if($request->has('encpass') && $request->has('encpass')){
                $client->password = Hash::make($request->encpass);
                $client->encpass = $request->encpass;
                $isPasswordUpdate = 1;
            }
        }
        if ($request->hasFile('logo')) {    /* upload logo file */
            $file = $request->file('logo');
            $client->logo = Storage::disk('s3')->put('Clientlogo', $file, 'public');
        }
        $client->save();
        return $client;
    }
    
    /* Create random and unique client code*/
    private function randomString(){
        $random_string = substr(md5(microtime()), 0, 6);
        // after creating, check if string is already used

        while(Client::where('code', $random_string )->exists()){
            $random_string = substr(md5(microtime()), 0, 6);
        }
        return $random_string;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        return redirect()->back()->with(['getClient' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $client = Client::where('id', $id)->first();
        $client->status = $request->action;
        $msg = '';
        if($request->action == 3){
            $client->is_deleted = 1;
            $msg = 'deleted';
        }else{
            $client->is_blocked = ($request->action == 2) ? 1 : 0;
            $msg = ($request->action == 2) ? 'blocked' : 'unblocked';
        }
        $client->save();
        return redirect()->back()->with('success', 'Client account ' . $msg . ' successfully!');
    }

    /**
     * Store/Update Client Preferences 
     */
    public function storePreference(Request $request, $id)
    {
        $client = Client::where('code', $id)->firstOrFail();
        //update the client custom_domain if value is set //
        if ($request->domain_name == 'custom_domain') {
            // check the availability of the domain //
            $exists = Client::where('code', '<>', $id)->where('custom_domain', $request->custom_domain_name)->count();
            if ($exists) {
                return redirect()->back()->withErrors(new \Illuminate\Support\MessageBag(['domain_name' => 'Domain name "' . $request->custom_domain_name . '" is not available. Please select a different domain']));
            }
            Client::where('id', $id)->update(['custom_domain' => $request->custom_domain_name]);
        }
        
        $updatePreference = ClientPreference::updateOrCreate([
            'client_id' => $id
        ], $request->all());
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Preference updated successfully!',
                'data' => $updatePreference
            ]);
        } else {
            return redirect()->back()->with('success', 'Preference updated successfully!');
        }
    }

    /**
     * Store/Update Client Preferences 
     */
    public function ShowPreference()
    {
        $preference = ClientPreference::where('client_id', Auth::user()->code)->first();
        $currencies = Currency::orderBy('iso_code')->get();
        return view('customize')->with(['preference' => $preference, 'currencies' => $currencies]);
    }


    /**
     * Show Configuration page 
     */
    public function ShowConfiguration()
    {
        $preference = ClientPreference::where('client_id',Auth::user()->code)->first();
        $client = Auth::user();
        return view('configure')->with(['preference' => $preference, 'client' => $client]);
    }

    /**
     * Show Options page 
     */
    public function ShowOptions()
    {
        $preference = ClientPreference::where('client_id',Auth::user()->id)->first();
        return view('options')->with(['preference' => $preference]);
    }
}
