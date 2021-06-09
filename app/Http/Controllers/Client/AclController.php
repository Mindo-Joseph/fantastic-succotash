<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Permissions;
use App\Models\ClientPermissions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Crypt;
use Illuminate\Support\Facades\DB;

class AclController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subadmins = Client::where('is_superadmin', 0)->where('id', '!=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
        return view('backend.acl.index')->with(['subadmins' => $subadmins]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permissions::all();
       
        return view('backend.acl.form')->with(['permissions'=>$permissions]);
    }

    /**
     * Validation method for clients data
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'phone_number' => ['required'],
            'password' => ['required']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '')
    {
        $validator = $this->validator($request->all())->validate();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'confirm_password' => Crypt::encryptString($request->password),
            'phone_number' => $request->phone_number,
            'status' => $request->status,
            'is_superadmin' => 0,
        ];
       
        $superadmin_data = Client::select('country_id','timezone','custom_domain','sub_domain','is_deleted','is_blocked','database_path','database_name','database_username','database_password','company_name','company_address','logo','code','database_host','database_port')
        ->where('is_superadmin', 1)
        ->first()->toArray();
      
        $clientcode = $superadmin_data['code'];
        $superadmin_data['code'] = rand(11111,4444);
        $superadmin_data['logo'] = $superadmin_data['logo']['logo_db_value']??null;


        $finaldata = array_merge($data, $superadmin_data);
                     
        $subdmin = Client::create($finaldata);
        
        //update client code
        $codedata = [
            'code' => $subdmin->id.'_'.$clientcode,
            'is_superadmin' => 0
            
        ];
        
        $clientcodeupdate = Client::where('id', $subdmin->id)->update($codedata);

        if ($request->permissions) {
            $userpermissions = $request->permissions;
            $addpermission = [];
            $removepermissions = ClientPermissions::where('client_id', $subdmin->id)->delete();
            for ($i=0;$i<count($userpermissions);$i++) {
                $addpermission[] =  array('client_id' => $subdmin->id,'permission_id' => $userpermissions[$i]);
            }
            ClientPermissions::insert($addpermission);
        }

        
        return redirect()->route('acl.index')->with('success', 'Manager Added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain = '', $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $subadmin = Client::find($id);
        $permissions = Permissions::all();
        $user_permissions = ClientPermissions::where('client_id', $id)->get();
        
        return view('backend.acl.form')->with(['subadmin'=> $subadmin,'permissions'=>$permissions,'user_permissions'=>$user_permissions]);
    }

    protected function updateValidator(array $data, $id)
    {
        //print_r($data); die;
        return Validator::make($data, [

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',\Illuminate\Validation\Rule::unique('clients')->ignore($id)],
            'phone_number' => ['required'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $validator = $this->updateValidator($request->all(), $id)->validate();
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'status' => $request->status,
            'is_superadmin' => 0
            
        ];
        if ($request->password!="") {
            $data['password'] = Hash::make($request->password);
            $data['confirm_password'] = Crypt::encryptString($request->password);
        }
                
        $client = Client::where('id', $id)->update($data);

        //for updating permissions
        if ($request->permissions) {
            $userpermissions = $request->permissions;
            $addpermission = [];
            $removepermissions = ClientPermissions::where('client_id', $id)->delete();
            for ($i=0;$i<count($userpermissions);$i++) {
                $addpermission[] =  array('client_id' => $id,'permission_id' => $userpermissions[$i]);
            }
            ClientPermissions::insert($addpermission);
        }

        
        
        return redirect()->route('acl.index')->with('success', 'Manager Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        // $getSubadmin = Client::where('id', $id)->delete();
        // $removepermissions = SubAdminPermissions::where('sub_admin_id', $id)->delete();
        // $removeteampermissions = SubAdminTeamPermissions::where('sub_admin_id', $id)->delete();
        // return redirect()->back()->with('success', 'Subadmin deleted successfully!');
    }
}
