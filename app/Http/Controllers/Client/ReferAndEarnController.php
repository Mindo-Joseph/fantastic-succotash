<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use App\Models\{ReferAndEarn, Celebrity, Product};
use Illuminate\Support\Facades\Auth;

class ReferAndEarnController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $refferandearn = ReferAndEarn::first()->toArray();
        return view('backend/referandearn/index')->with(['refferandearn' => $refferandearn]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
    public function updateRefferby(Request $request)
    {
        // dd($request->all());
        $rae = ReferAndEarn::first();
        $rae->reffered_by_amount = $request->reffered_by_amount;
        $rae->save();
        dd("done");
    }

    public function updateRefferto(Request $request)
    {
        // dd($request->all());
        $rae = ReferAndEarn::first();
        $rae->reffered_to_amount = $request->reffered_to_amount;
        $rae->save();
        return redirect()->back()->with('success', 'Updated successfully!');

    }

}
