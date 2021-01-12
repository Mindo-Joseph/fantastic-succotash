<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{VendorSlotDate, Vendor, VendorSlot, SlotDay, ServiceArea};
use Illuminate\Http\Request;

class ServiceAreaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, $vendor_id)
    {
        //dd($request->all());
        $vendor = Vendor::where('id', $vendor_id)->firstOrFail();
        $area = new ServiceArea();
        if($request->has('area_id')){
            $area = ServiceArea::where('id', $request->area_id)->first();
        }
        $area->name             = $request->name;
        $area->description      = $request->description;
        $area->geo_array        = $request->latlongs;
        $area->zoom_level       = $request->zoom_level;
        $area->vendor_id        = $vendor->id;

        $area->save();

        return redirect()->back()->with('success', 'Service area saved successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceArea  $serviceArea
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $vendor_id)
    {
        $area = ServiceArea::where('id', $request->data)->where('vendor_id', $vendor_id)->first();
        $returnHTML = view('backend.vendor.editArea')->with(['area' => $area])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML, 'zoomLevel'  => $area->zoom_level, 'coordinate'  => $area->geo_array));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceArea  $serviceArea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $area = ServiceArea::where('id', $id)->where('vendor_id', $request->ven_id)->firstOrFail();
        $area->name           = $request->name;
        $area->description    = $request->description;
        $area->geo_array      = $request->latlongs_edit;
        $area->zoom_level     = $request->zoom_level_edit;
        $area->save();

        return redirect()->back()->with('success', 'Service area updated successfully!');
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceArea  $serviceArea
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceArea $serviceArea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceArea  $serviceArea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $vendor_id)
    {
        $vendor = Vendor::where('id', $vendor_id)->firstOrFail();
        $area = ServiceArea::where('id', $request->area_id)->delete();
        return redirect()->back()->with('success', 'Service area deleted successfully!');
    }
}
