<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{VendorSlotDate, Vendor, VendorSlot, VendorBlockDate, Category, ServiceArea};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Image;

class VendorController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::where('status', '!=', '2')->orderBy('id', 'desc')->get();
        //dd($vendors->toArray());
        return view('backend/vendor/index')->with(['vendors' => $vendors]);
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
        $rules = array(
            'name' => 'required|string|max:150|unique:vendors',
            'address' => 'required',
        );

        $validation  = Validator::make($request->all(), $rules)->validate();
        $vendor = new Vendor();
        $saveVendor = $this->save($request, $vendor, 'false');
        if($saveVendor > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Vendor created Successfully!',
                'data' => $saveVendor
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        $co_ordinates = $all_coordinates = array();
        $areas = ServiceArea::where('vendor_id', $id)->orderBy('created_at', 'DESC')->get();
        $zz = 1;
        foreach ($areas as $k => $v) {
            $all_coordinates[] = [
                'name' => $k.'-a',
                'coordinates' => $v->geo_coordinates
            ];
        }

        $center = [
            'lat' => 30.0612323,
            'lng' => 76.1239239
        ];

         if (!empty($all_coordinates)) {
            $center['lat'] = $all_coordinates[0]['coordinates'][0]['lat'];
            $center['lng'] = $all_coordinates[0]['coordinates'][0]['lng'];
        }

        $area1 = ServiceArea::where('vendor_id', $id)->orderBy('created_at', 'DESC')->first();

        if(isset($area1)){
            $co_ordinates = $area1->geo_coordinates[0];
         }else{
            $co_ordinates[] = [
                'lat' => 33.5362475,
                'lng' => -111.9267386
            ];
         }

        //dd($all_coordinates);
        
        return view('backend/vendor/show')->with(['vendor' => $vendor, 'center' => $center, 'tab' => 'configuration', 'co_ordinates' => $co_ordinates, 'all_coordinates' => $all_coordinates, 'areas' => $areas]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function vendorCategory($id)
    {
        $vendor = Vendor::findOrFail($id);
        $categories = Category::join('category_translations as ct', 'ct.category_id', 'categories.id')
                        ->select('ct.name', 'categories.id', 'ct.category_id', 'categories.icon', 'categories.slug', 'categories.type', 'categories.parent_id')

                        ->where(function($q) use($id){
                              $q->whereNull('categories.vendor_id')
                                ->orWhere('categories.vendor_id', $id);
                        })
                        ->where('categories.id', '>', '1')
                        ->where('ct.language_id', '=', '1')
                        ->where('categories.status', '!=', '2')
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();

        if($categories){
            $build = $this->buildTree($categories->toArray());
            $tree = $this->printTree($build, 'vendor');
        }
        return view('backend/vendor/vendorCategory')->with(['vendor' => $vendor, 'tab' => 'category', 'html' => $tree,]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function vendorCatalog($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('backend/vendor/vendorCatalog')->with(['vendor' => $vendor, 'tab' => 'catalog']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, Vendor $vendor, $update = 'false')
    {
        $checks = array();
        foreach ($request->only('name', 'address', 'latitude', 'longitude', 'desc') as $key => $value) {
            $vendor->{$key} = $value;
        }

        $vendor->dine_in = ($request->has('dine_in') && $request->dine_in == 'on') ? 1 : 0; 
        $vendor->takeaway = ($request->has('takeaway') && $request->takeaway == 'on') ? 1 : 0; 
        $vendor->delivery = ($request->has('delivery') && $request->delivery == 'on') ? 1 : 0;

        if($update == 'false'){
            $vendor->logo = 'default/default_logo.png';
            $vendor->banner = 'default/default_image.png';
        }

        if ($request->hasFile('logo')) {    /* upload logo file */
            $file = $request->file('logo');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            $vendor->logo = $request->file('logo')->storeAs('/vendor', $file_name, 'public');
        }

        if ($request->hasFile('banner')) {    /* upload logo file */
            $file = $request->file('banner');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            $vendor->banner = $request->file('banner')->storeAs('/vendor', $file_name, 'public');
        }

        $vendor->save();
        return $vendor->id;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendor = Vendor::where('id', $id)->first();
        $returnHTML = view('backend.vendor.form')->with(['vendor' => $vendor])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'name' => 'required|string|max:150|unique:vendors,name,'.$id,
            'address' => 'required',
        );
        //dd($request->all());
        $validation  = Validator::make($request->all(), $rules)->validate();
        $vendor = Vendor::where('id', $id)->first();
        $saveVendor = $this->save($request, $vendor, 'true');
        if($saveVendor > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Vendor updated Successfully!',
                'data' => $saveVendor
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = Vendor::where('id', $id)->first();
        $vendor->status = 2;
        $vendor->save();
        return redirect()->back()->with('success', 'Vendor deleted successfully!');
    }

    public function updateConfig(Request $request, $id)
    {
        $vendor = Vendor::where('id', $id)->first();
        foreach($request->only('order_min_amount', 'order_pre_time', 'auto_reject_time', 'commission_percent', 'commission_fixed_per_order', 'commission_monthly') as $key => $value){
            $vendor->{$key} = $value;
        }
        $vendor->save();
        return redirect()->back()->with('success', 'Configurations updated successfully!');
        
    }
}
