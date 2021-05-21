<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{VendorSlotDate, Vendor, VendorSlot, VendorBlockDate, Category, ServiceArea, ClientLanguage, AddonSet, Product, Type, VendorCategory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Phumbor;
use Image;
use Illuminate\Support\Facades\Storage;

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
            $vendor->logo = Storage::disk('s3')->put('/vendor', $file,'public');
        }

        if ($request->hasFile('banner')) {    /* upload logo file */
            $file = $request->file('banner');
            $vendor->banner = Storage::disk('s3')->put('/vendor', $file,'public');
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
    public function edit($domain = '', $id)
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
    public function update(Request $request, $domain = '', $id)
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
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show($domain = '', $id)
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

        $categorList = Category::select('id', 'slug', 'vendor_id')->where('parent_id', '1')
                    ->where(function($q) use($id){
                          $q->whereNull('vendor_id')
                            ->orWhere('vendor_id', $id);
                    })
                    ->where('status', '!=', '2')
                    ->orderBy('position', 'asc')->get();
        
        return view('backend/vendor/show')->with(['vendor' => $vendor, 'center' => $center, 'tab' => 'configuration', 'co_ordinates' => $co_ordinates, 'all_coordinates' => $all_coordinates, 'areas' => $areas, 'categorList' => $categorList]);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function vendorCategory($domain = '', $id)
    {
        $vendor = Vendor::findOrFail($id);

        $categories = Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
                        ->where('id', '>', '1')
                        ->where(function($q) use($id){
                              $q->whereNull('vendor_id')
                                ->orWhere('vendor_id', $id);
                        })
                        ->where('status', '!=', '2')
                        ->orderBy('parent_id', 'asc')
                        ->orderBy('position', 'asc')->get();

        /*$categories = Category::join('category_translations as ct', 'ct.category_id', 'categories.id')
                        ->select('ct.name', 'categories.id', 'ct.category_id', 'categories.icon', 'categories.slug', 'categories.type_id', 'categories.parent_id', 'categories.vendor_id', 'categories.is_core')

                        
                        ->where('categories.id', '>', '1')
                        ->where('ct.language_id', '=', '1')
                        ->where('categories.status', '!=', '2')
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();*/
        if($categories){
            $build = $this->buildTree($categories->toArray());
            $tree = $this->printTree($build, 'vendor');
        }

        $addons = AddonSet::with('option')->select('id', 'title', 'min_select', 'max_select', 'position')
                    ->where('status', '!=', 2)
                    ->where('vendor_id', $id)
                    ->orderBy('position', 'asc')->get();

        $langs = ClientLanguage::with('language')->select('language_id', 'is_primary', 'is_active')
                    ->where('is_active', 1)
                    ->orderBy('is_primary', 'desc')->get();

        $categorList = Category::select('id', 'slug', 'vendor_id')->where('parent_id', '1')
                    ->where(function($q) use($id){
                          $q->whereNull('vendor_id')
                            ->orWhere('vendor_id', $id);
                    })
                    ->where('status', '!=', '2')
                    ->orderBy('position', 'asc')->get();
        return view('backend/vendor/vendorCategory')->with(['vendor' => $vendor, 'tab' => 'category', 'html' => $tree, 'languages' => $langs, 'addon_sets' => $addons, 'categorList' => $categorList]);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function vendorCatalog($domain = '', $id)
    {
        $vendor = Vendor::findOrFail($id);

        //$ve = Vendor::with('products')->where('id', $id)->first();
        $type = Type::all();
        $categories = Category::with('primary')->select('id', 'slug')
                        ->where('id', '>', '1')->where('status', '!=', '2')
                        ->where('can_add_products', 1)->orderBy('parent_id', 'asc')
                        ->orderBy('position', 'asc')->get();

        $products = Product::with('variant', 'primary', 'category.cat', 'variantSet')->where('vendor_id', $id)->where('is_live', '!=', 2)->get();

        $categorList = Category::select('id', 'slug', 'vendor_id')->where('parent_id', '1')
                    ->where(function($q) use($id){
                          $q->whereNull('vendor_id')
                            ->orWhere('vendor_id', $id);
                    })
                    ->where('status', '!=', '2')
                    ->orderBy('position', 'asc')->get();
        return view('backend/vendor/vendorCatalog')->with(['vendor' => $vendor, 'tab' => 'catalog', 'products' => $products, 'typeArray' => $type, 'categories' => $categories, 'categorList' => $categorList]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        $vendor = Vendor::where('id', $id)->first();
        $vendor->status = 2;
        $vendor->save();
        return redirect()->back()->with('success', 'Vendor deleted successfully!');
    }

    public function updateConfig(Request $request, $domain = '',  $id)
    {
        $vendor = Vendor::where('id', $id)->first();
        $msg = 'Order configuration';

        if($request->has('order_pre_time')){
            $vendor->order_min_amount   = $request->order_min_amount;
            $vendor->order_pre_time     = $request->order_pre_time;
            $vendor->auto_reject_time   = $request->auto_reject_time;
        }

        if($request->has('commission_percent')){
            $vendor->commission_percent         = $request->commission_percent;
            $vendor->commission_fixed_per_order = $request->commission_fixed_per_order;
            $vendor->commission_monthly         = $request->commission_monthly;
            $vendor->add_category = ($request->has('add_category') && $request->add_category == 'on') ? 1 : 0;

            $msg = 'commission configuration';
        }
        $vendor->save();
        return redirect()->back()->with('success', $msg.' updated successfully!');
        
    }

    /** Activate Category for vendor     */
    public function activeCategory(Request $request, $domain = '', $id)
    {
        /* "vid" => "6"
  "cid" => "3"
  "category" => "on"
        $data = [

        ];

        dd($request->all());*/
        $vendor = Vendor::where('id', $id)->firstOrFail();
        $vc = VendorCategory::where('id', $id)->first();
        $vendor->status = 2;
        $vendor->save();
        return redirect()->back()->with('success', 'Vendor deleted successfully!');
    }

    
}