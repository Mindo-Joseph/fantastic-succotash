<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;

class DashBoardController extends BaseController
{

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
        return view('backend/dashboard');
    }
}
