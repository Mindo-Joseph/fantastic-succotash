<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{Promocode, Product, Vendor, PromoType, Category, PromocodeUser, PromocodeProduct, PromocodeRestriction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PromocodeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promocodes = Promocode::with('type', 'restriction')->get();

        return view('backend/promocode/index')->with(['promocodes' => $promocodes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $promoTypes = PromoType::where('status', 1)->get();
        $promocode = new Promocode();
        $products = Product::select('id', 'sku')->where('is_live', 1)->get();
        $vendors = Vendor::select('id', 'name')->where('status', 1)->get();
        $categories = Category::select('id', 'slug')->get();

        $returnHTML = view('backend.promocode.form')->with(['promo' => $promocode,  'promoTypes' => $promoTypes, 'categories' => $categories, 'vendors' => $vendors, 'products' => $products, 'restrictionType' => '', 'include' => '0', 'exclude' => '0'])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string|max:150||unique:promocodes',
            'amount' => 'required|numeric',
            'promo_type_id' => 'required',
            'expiry_date' => 'required',
            'minimum_spend' => 'required|numeric',
            'maximum_spend' => 'required|numeric',
            'limit_per_user' => 'required|numeric',
            'limit_total' => 'required|numeric',
        );

        $validation  = Validator::make($request->all(), $rules)->validate();
        $promo_types = new PromoType();
        $promo_types->status = $request->types;


        $promocode = Promocode::create($request->all());
        $promocode->first_order_only = ($request->has('first_order_only') && $request->first_order_only == 'on') ? 1 : 0;
        $promocode->allow_free_delivery = ($request->has('allow_free_delivery') && $request->allow_free_delivery == 'on') ? 1 : 0;
        $promocode->Paid_by_vendor_admin = $request->radioInline;
        $inlineRadioOptions = $request->inlineRadioOptions;
        $is_include = ($request->applied_type == 'include') ? 1 : 0;
        $is_exclude = ($request->applied_type == 'exclude') ? 1 : 0;
        $data = $excludeData = array();
        if ($inlineRadioOptions == 0) {
            $excludeData = $request->input('productList');
        }
        if ($inlineRadioOptions == 1) {
            $excludeData = $request->input('vendorList');
        }
        if ($inlineRadioOptions == 2) {
            $excludeData = $request->input('categoryList');
        }

        if (!empty($excludeData)) {
            foreach ($excludeData as $res) {
                $data[] = [
                    'promocode_id' => $promocode->id,
                    'restriction_type' => $inlineRadioOptions,
                    'data_id' => $res,
                    'is_excluded' => $is_exclude,
                    'is_included' => $is_include,
                ];
            }
        }
        PromocodeRestriction::insert($data);

        return response()->json(array('status' => 'success', 'Data_Inserted' => 'Data has been Inserted successfully!'));
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
    public function edit($domain = '', $id)
    {
        $promoTypes = PromoType::where('status', 1)->get();
        $promocode = Promocode::with('restriction')->where('id', $id)->first();
        $products = Product::select('id', 'sku')->where('is_live', 1)->get();
        $vendors = Vendor::select('id', 'name')->where('status', 1)->get();
        $categories = Category::select('id', 'slug')->get();
        $dataIds = array();
        $restrictionType = '';
        $include = $exclude = 0;
        foreach ($promocode->restriction as $key => $value) {
            $dataIds[] = $value->data_id;
            if ($value->is_included == 1) {
                $include = 1;
            }
            if ($value->is_excluded == 1) {
                $exclude = 1;
            }
            $restrictionType = $value->restriction_type;
        }
        dd($dataIds);
        $returnHTML = view('backend.promocode.form')->with(['promo' => $promocode, 'promoTypes' => $promoTypes, 'dataIds' => $dataIds, 'restrictionType' => $restrictionType, 'categories' => $categories, 'vendors' => $vendors, 'products' => $products, 'include' => $include, 'exclude' => $exclude])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id = 0)
    {
        $rules = array(
            'name' => 'required|string|max:150',
            'amount' => 'required|numeric',
            'promo_type_id' => 'required',
            'expiry_date' => 'required',
            'minimum_spend' => 'required|numeric',
            'maximum_spend' => 'required|numeric',
            'limit_per_user' => 'required|numeric',
            'limit_total' => 'required|numeric',
        );
        //$promocode_restriction =  PromoCodeRestriction::where('promocode_id', $request->id)->first();

        // dd($promocode_restriction);
        //$promocode_restriction->restriction_type = $request->restriction_types;

        $promocode = Promocode::find($id);
        $promocode->name = $request->name;
        // $promocode->type = $request->types;
        $promocode->amount = $request->amount;
        $promocode->expiry_date = $request->expiry_date;
        $promocode->allow_free_delivery = ($request->has('first_order_only') && $request->first_order_only == 'on') ? 1 : 0;
        $promocode->first_order_only =  ($request->has('allow_free_delivery') && $request->allow_free_delivery == 'on') ? 1 : 0;
        $promocode->minimum_spend = $request->minimum_spend;
        $promocode->maximum_spend = $request->maximum_spend;
        $promocode->limit_per_user = $request->limit_per_user;
        $promocode->limit_total = $request->limit_total;
        $promocode->Paid_by_vendor_admin = $request->radioInline;
        $promocode->save();
        // $promocode->promocoderestriction()->save($promocode_restriction);
        // return back()->with('', 'Data has been Updated successfully!');
        return response()->json(array('status' => 'success', 'Data_Updated' => 'Data has been Updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        Promocode::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Promocode deleted successfully!');
    }
}
