<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{Payment, User, Client, Country, Currency, Language};
use Illuminate\Http\Request;
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
        $users = User::with('role', 'country')->paginate(20);
        //dd($users->toArray());
        return view('backend/users/index')->with(['users' => $users]);
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

}
