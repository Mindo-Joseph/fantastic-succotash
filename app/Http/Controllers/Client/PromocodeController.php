<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromocodeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promocodes = array();
        return view('backend/promocode/index')->with(['promocodes' => $promocodes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function show(Promocode $promocode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function edit(Promocode $promocode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promocode $promocode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promocode $promocode)
    {
        //
    }
}
