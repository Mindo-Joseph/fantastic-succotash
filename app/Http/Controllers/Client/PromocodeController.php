<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{Promocode, Vendor, PromocodeUser, PromocodeProduct, PromocodeRestriction};
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
        $promocodes = Promocode::all();
        $promoTypes = \DB::table('promo_types')->where('status', 1)->get();
        return view('backend/promocode/index')->with(['promocodes' => $promocodes, 'promoTypes' => $promoTypes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $promoTypes = \DB::table('promo_types')->where('status', 1)->get();
        $promocode = new Promocode();
        $returnHTML = view('backend.promocode.form')->with(['promo' => $promocode,  'promoTypes' => $promoTypes])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'types' => 'required',
        //     'amount' => 'required',
        //     'expiry_date' => 'required',
        //     'free_delivery' => 'required',
        //     'first_order' => 'required',
        //     'minimum_amount' => 'required',
        //     'maximum_amount' => 'required',
        //     'limit_per_user' => 'required',
        //     'total_limit' => 'required',
        //     'paid_by' => 'required',
        //     'restriction_types' => 'required'
        // ]);
        $promocode_restriction = new PromoCodeRestriction();
        $promocode_restriction->restriction_type = $request->restriction_types;
        
        $promocode = new Promocode();
        $promocode->name = $request->name;
        $promocode->type = $request->types;
        $promocode->amount = $request->amount;
        $promocode->expiry_date = $request->expiry_date;
        $promocode->allow_free_delivery = ($request->has('free_delivery') && $request->free_delivery == 'on') ? 1 : 0;
        $promocode->first_order_only = ($request->has('first_order') && $request->first_order == 'on') ? 1 : 0;
        $promocode->minimum_spend = $request->minimum_amount;
        $promocode->maximum_spend = $request->maximum_amount;
        $promocode->limit_per_user = $request->limit_per_user;
        $promocode->limit_total = $request->total_limit;
        $promocode->Paid_by_vendor_admin = $request->radioInline;
        $promocode->save();

        $promocode->promocoderestriction()->save($promocode_restriction);
        return back()->with('Data_Inserted', 'Data has been Inserted successfully!');
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
    public function edit($id)
    {
        $promocode = Promocode::find($id);
        $promocode_restriction =PromocodeRestriction::where('promocode_id',$id)->first();
        // dd($promocode_restriction->restriction_type);
        return view('promocode.edit-promocode', ['promocode'=>$promocode,'restriction_type'=>$promocode_restriction->restriction_type]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
         // $request->validate([
        //     'name' => 'required',
        //     'types' => 'required',
        //     'amount' => 'required',
        //     'expiry_date' => 'required',
        //     'free_delivery' => 'required',
        //     'first_order' => 'required',
        //     'minimum_amount' => 'required',
        //     'maximum_amount' => 'required',
        //     'limit_per_user' => 'required',
        //     'total_limit' => 'required',
        //     'paid_by' => 'required',
        //     'restriction_types' => 'required'
        // ]);
        $promocode_restriction =  PromoCodeRestriction::where('promocode_id',$request->id)->first();

        // dd($promocode_restriction);
        $promocode_restriction->restriction_type = $request->restriction_types;

        $promocode = Promocode::find($request->id);
        $promocode->name = $request->name;
        // $promocode->type = $request->types;
        $promocode->amount = $request->amount;
        $promocode->expiry_date = $request->expiry_date;
        $promocode->allow_free_delivery = ($request->has('free_delivery') && $request->free_delivery == 'on') ? 1 : 0;
        $promocode->first_order_only = ($request->has('first_order') && $request->first_order == 'on') ? 1 : 0;
        $promocode->minimum_spend = $request->minimum_amount;
        $promocode->maximum_spend = $request->maximum_amount;
        $promocode->limit_per_user = $request->limit_per_user;
        $promocode->limit_total = $request->total_limit;
        $promocode->Paid_by_vendor_admin = $request->radioInline;
        $promocode->save();
        $promocode->promocoderestriction()->save($promocode_restriction);
        return back()->with('Data_Updated', 'Data has been Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promocode = Promocode::find($id);
        $promocode->delete();
        return redirect('/showall-promocode');
    }
}
