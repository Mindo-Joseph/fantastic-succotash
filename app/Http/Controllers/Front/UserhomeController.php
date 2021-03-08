<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\Currency;
use Illuminate\Http\Request;

class UserhomeController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $home = array();        
        return view('forntend/home')->with(['home' => $home]);
    }

}
