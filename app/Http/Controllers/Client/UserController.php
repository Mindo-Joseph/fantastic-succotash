<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{Payment, User, Client, Country, Currency, Language, UserVerification, Role};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Password;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Mail;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $countries = Country::all();
        $users = User::with('role', 'country')->select('id', 'name', 'email', 'phone_number', 'status', 'role_id', 'system_id', 'email_token', 'phone_token', 'is_email_verified', 'is_phone_verified')->orderBy('id', 'desc')->paginate(20);
        //dd($users->toArray());
        return view('backend/users/index')->with(['users' => $users, 'roles' => $roles, 'countries' => $countries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($uid, $action)
    {
        $user = User::where('id', $uid)->firstOrFail();
        $user->status = $action;
        $user->save();
        $msg = 'activated';
        if($action == 2){
            $msg = 'blocked';
        }

        return redirect()->back()->with('success', 'Customer account ' . $msg . ' successfully!');
    }

    /**              Add customer             */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = new User();
        $validation  = Validator::make($request->all(), $customer->rules())->validate();
        
        $saveId = $this->save($request, $customer, 'false');
        if($saveId > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Customer created Successfully!',
                'data' => $banner
            ]);
        }
    }

    /**
     * save and update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, User $user, $update = 'false')
    {
        $user->name = $request->name; 
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;

        $user->is_email_verified = ($request->has('is_email_verified') && $request->is_email_verified == 'on') ? 1 : 0; 
        $user->is_phone_verified = ($request->has('is_phone_verified') && $request->is_phone_verified == 'on') ? 1 : 0; 

        if ($request->hasFile('image')) {    /* upload logo file */
            $file = $request->file('image');
            $user->image = Storage::disk('s3')->put('/profile', $file,'public');
        }
        $user->save();
        return $user->id;
    }

}
