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
        $refferandearn = ReferAndEarn::first();
        $reffer_by = " ";
        $reffer_to = " ";
        if($refferandearn == null){
            $reffer_by = 0;
            $reffer_to = 0;
        }
        else{
            $refferandearn = $refferandearn->toArray();
            if($refferandearn['reffered_by_amount'] != null){
                $reffer_by = $refferandearn['reffered_by_amount'];
            }else{
                $reffer_by = 0;
            }
            if($refferandearn['reffered_to_amount'] != null){
                $reffer_to = $refferandearn['reffered_to_amount'];
            }else{
                $reffer_to = 0;
            }
        }
        return view('backend/referandearn/index')->with(['reffer_by' => $reffer_by,'reffer_to' => $reffer_to]);
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
        if($rae){
            $rae->reffered_by_amount = $request->reffered_by_amount;
            $rae->save();
            return redirect()->back()->with('success', 'Updated successfully!');
        }
        else{
            $refandearn = new ReferAndEarn();
            $refandearn->reffered_by_amount	= $request->reffered_by_amount;
            $refandearn->save();
            return redirect()->back()->with('success', 'Updated successfully!');
        }
    }

    public function updateRefferto(Request $request)
    {
        $rae = ReferAndEarn::first();
        if($rae){
            $rae->reffered_to_amount = $request->reffered_to_amount;
            $rae->save();
            return redirect()->back()->with('success', 'Updated successfully!');
        }
        else{
            $refandearn = new ReferAndEarn();
            $refandearn->reffered_to_amount	= $request->reffered_to_amount;
            $refandearn->save();
            return redirect()->back()->with('success', 'Updated successfully!');
        }

    }

}
