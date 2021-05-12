<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{Payment, User, Client, Country, Currency, Language, UserVerification, Role};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\Storage;

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
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:3|max:50',
            'email'         => 'required|email|max:50||unique:users',
            'password'      => 'required|string|min:6|max:50',
            'phone_number'  => 'required|string|min:10|max:15|unique:users',
        ]);

        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif';
        }

        $validation  = Validator::make($request->all(), $rules)->validate();
        $customer = new User();
        $savebanner = $this->save($request, $customer, 'false');
        if($savebanner > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Customer created Successfully!',
                'data' => $banner
            ]);
        }
    }

}
