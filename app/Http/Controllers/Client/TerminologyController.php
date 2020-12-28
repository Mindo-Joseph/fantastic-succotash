<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\Terminology;
use Illuminate\Http\Request;

class TerminologyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terminologies = array();
        return view('backend/terminology/index')->with(['terminologies' => $terminologies]);
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
     * @param  \App\Terminology  $terminology
     * @return \Illuminate\Http\Response
     */
    public function show(Terminology $terminology)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Terminology  $terminology
     * @return \Illuminate\Http\Response
     */
    public function edit(Terminology $terminology)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Terminology  $terminology
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Terminology $terminology)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Terminology  $terminology
     * @return \Illuminate\Http\Response
     */
    public function destroy(Terminology $terminology)
    {
        //
    }
}
