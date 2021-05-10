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
        $dataIds = array();

        $returnHTML = view('backend.promocode.form')->with(['promo' => $promocode,  'promoTypes' => $promoTypes, 'categories' => $categories, 'vendors' => $vendors, 'products' => $products, 'restrictionType' => '', 'include' => '0', 'exclude' => '0', 'dataIds' => $dataIds])->render();
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
            'name' => 'required|string|max:150||unique:promocodes,name,'.$id,
            'amount' => 'required|numeric',
            'promo_type_id' => 'required',
            'expiry_date' => 'required',
            'minimum_spend' => 'required|numeric',
            'maximum_spend' => 'required|numeric',
            'limit_per_user' => 'required|numeric',
            'limit_total' => 'required|numeric',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();

        $promocode = Promocode::findOrFail($id);
        $promoId = $this->save($request, $promocode, 'false');
        if($promoId > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Banner updated Successfully!',
                'data' => $promoId
            ]);
        } 
    }

    /**
     * save and update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, Promocode $promocode, $update = 'false')
    {
        foreach ($request->only('name', 'amount', 'expiry_date', 'promo_type_id', 'minimum_spend', 'maximum_spend', 'limit_per_user', 'limit_total', 'paid_by_vendor_admin') as $key => $value) {
            $promocode->{$key} = $value;
        }
        $promocode->first_order_only = ($request->has('first_order_only') && $request->first_order_only == 'on') ? 1 : 0;
        $promocode->allow_free_delivery = ($request->has('allow_free_delivery') && $request->allow_free_delivery == 'on') ? 1 : 0;
        $promocode->Paid_by_vendor_admin = $request->radioInline;
        $promocode->save();

        if($promocode->id > 0){

            $promoRestrict = PromocodeRestriction::where('promocode_id', $promocode->id)->delete();

            $inlineRadioOptions = $request->inlineRadioOptions;
            $is_include = ($request->applied_type == 'include') ? 1 : 0;
            $is_exclude = ($request->applied_type == 'exclude') ? 1 : 0;
            $data = $excludeData = array();
            $fieldKey = '';
            if ($inlineRadioOptions == 0) {
                $excludeData = $request->input('productList');
                $fieldKey = 'product_id';
            }
            if ($inlineRadioOptions == 1) {
                $excludeData = $request->input('vendorList');
                $fieldKey = 'vendor_id';
            }
            if ($inlineRadioOptions == 2) {
                $excludeData = $request->input('categoryList');
                $fieldKey = 'data_id';
            }

            if (!empty($excludeData)) {
                foreach ($excludeData as $res) {
                    $data[] = [
                        'promocode_id' => $promocode->id,
                        'restriction_type' => $inlineRadioOptions,
                        $fieldKey => $res,
                        'is_excluded' => $is_exclude,
                        'is_included' => $is_include,
                    ];
                }
            }
            PromocodeRestriction::insert($data);
        }
        return $promocode->id;
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

        $promocode = new Promocode();
        $promoId = $this->save($request, $promocode, 'false');
        if($promoId > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Banner created Successfully!',
                'data' => $promoId
            ]);
        } 
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
        $returnHTML = view('backend.promocode.form')->with(['promo' => $promocode, 'promoTypes' => $promoTypes, 'dataIds' => $dataIds, 'restrictionType' => $restrictionType, 'categories' => $categories, 'vendors' => $vendors, 'products' => $products, 'include' => $include, 'exclude' => $exclude])->render();

        return response()->json(array('success' => true, 'html' => $returnHTML));
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
