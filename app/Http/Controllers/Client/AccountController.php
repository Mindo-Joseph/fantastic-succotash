<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account = array();
        return view('backend/account/index')->with(['account' => $account]);
    }
}
