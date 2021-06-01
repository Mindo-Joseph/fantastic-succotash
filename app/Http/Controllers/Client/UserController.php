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
use App\Http\Traits\ToasterResponser;

class UserController extends BaseController
{
    use ToasterResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $countries = Country::all();
        $users = User::withCount(['orders', 'activeOrders'])->where('status', '!=', 3)->orderBy('id', 'desc')->paginate(20);
        //dd($users->toArray());
        return view('backend/users/index')->with(['users' => $users, 'roles' => $roles, 'countries' => $countries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomer($domain = '', $uid, $action)
    {
        $user = User::where('id', $uid)->firstOrFail();
        //$user->status = $action;
        $user->save();
        $msg = 'activated';
        if($action == 2){
            $msg = 'blocked';
        }
        if($action == 3){
            $msg = 'deleted';
        }
        return redirect()->back()->with('success', 'Customer account ' . $msg . ' successfully!');
    }

    /*      block - activate customer account*/
    public function changeStatus(Request $request, $domain = '')
    {
        $user = User::where('id', $request->userId)->firstOrFail();
        $user->status = ($request->value == 1) ? 1 : 2; // 1 for active 2 for block
        $user->save();
        $msg = 'activated';
        if($request->value == 0){
            $msg = 'blocked';
        }
        return response()->json([
            'status'=>'success',
            'message' => 'Customer account ' . $msg . ' successfully!',
        ]);
        // $toaster = $this->successToaster('Success', 'Customer account ' . $msg . ' successfully!');
        // return redirect()->back()->with('toaster', $toaster);
    }

    /**              Add customer             */
    public function show($domain = '', $uid)
    {
        $user = User::where('id', $uid)->firstOrFail();
        return redirect()->back();
        //dd($user->toArray());
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
                'data' => $saveId,
                'aaa' => $request->all()
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
        $request->contact;
        $request->phone_number;
        $phone = ($request->has('contact') && !empty($request->contact)) ? $request->contact : '+1'.$request->phone_number;

        $user->name = $request->name; 
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $phone;

        $user->is_email_verified = ($request->has('is_email_verified') && $request->is_email_verified == 'on') ? 1 : 0; 
        $user->is_phone_verified = ($request->has('is_phone_verified') && $request->is_phone_verified == 'on') ? 1 : 0; 

        if ($request->hasFile('image')) {    /* upload logo file */
            $file = $request->file('image');
            $user->image = Storage::disk('s3')->put('/profile', $file,'public');
        }
        $user->save();
        $userCustomData = $this->userMetaData($user->id, 'web', 'web');
        return $user->id;
    }


    public function edit($domain = '', $id)
    {
        $user = User::where('id', $id)->first();

        return response()->json(array('success' => true, 'user'=> $user->toArray() ));
        

        //$returnHTML = view('backend.banner.form')->with(['banner' => $banner,  'vendors' => $vendors, 'categories' => $categories])->render();
        //return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

}
