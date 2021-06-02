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
use App\Http\Traits\ToasterResponser;
use App\Http\Traits\ApiResponser;

class VendorController extends BaseController
{
  use ToasterResponser;
  use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$vendors = Vendor::where('status', '!=', '2')->orderBy('id', 'desc')->get();
        $vendors = Vendor::withCount(['products', 'orders', 'activeOrders'])
                  ->where('status', '!=', '2')->orderBy('id', 'desc')->get();
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

    /*  /**   show vendor page - config tab      */
    public function show($domain = '', $id)
    {
        $vendor = Vendor::findOrFail($id);
        $co_ordinates = $all_coordinates = array();
        $areas = ServiceArea::where('vendor_id', $id)->orderBy('created_at', 'DESC')->get();
        $VendorCategory = VendorCategory::where('vendor_id', $id)->where('status', 1)->pluck('category_id')->toArray();
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
         $categories = Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
                        ->where('id', '>', '1')
                        ->where(function($q) use($id){
                              $q->whereNull('vendor_id')->orWhere('vendor_id', $id);
                        })
                        ->orderBy('position', 'asc')
                        ->orderBy('id', 'asc')
                        ->orderBy('parent_id', 'asc')->get();
        $categoryToggle = array();
        $active = array();
        /* get active category list also with parent */
        foreach ($categories as $category) {
          if(in_array($category->id, $VendorCategory) && $category->parent_id == 1){
            $active[] = $category->id;
          }
          if(in_array($category->id, $VendorCategory) && in_array($category->parent_id, $VendorCategory)){
            $active[] = $category->id;
          }
        }

        if($categories){
            $build = $this->buildTree($categories->toArray());
            $categoryToggle = $this->printTreeToggle($build, $active);
        }

        $templetes = \DB::table('vendor_templetes')->where('status', 1)->get();

        return view('backend/vendor/show')->with(['vendor' => $vendor, 'center' => $center, 'tab' => 'configuration', 'co_ordinates' => $co_ordinates, 'all_coordinates' => $all_coordinates, 'areas' => $areas, 'categoryToggle' => $categoryToggle, 'VendorCategory' => $VendorCategory, 'templetes' => $templetes]);
    }

    /**   show vendor page - category tab      */
    public function vendorCategory($domain = '', $id)
    {
        $vendor = Vendor::findOrFail($id);
        $VendorCategory = VendorCategory::where('vendor_id', $id)->where('status', 1)->pluck('category_id')->toArray();

        $categories = Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
                        ->where('id', '>', '1')
                        ->where(function($q) use($id){
                              $q->whereNull('vendor_id')
                                ->orWhere('vendor_id', $id);
                        })
                        ->orderBy('position', 'asc')->orderBy('id', 'asc')
                        ->orderBy('parent_id', 'asc')->get();
        $categoryToggle = array();
        $active = array();
        /* get active category list also with parent */
        foreach ($categories as $category) {
          if(in_array($category->id, $VendorCategory) && $category->parent_id == 1){
            $active[] = $category->id;
          }
          if(in_array($category->id, $VendorCategory) && in_array($category->parent_id, $VendorCategory)){
            $active[] = $category->id;
          }
        }

        if($categories){
            $build = $this->buildTree($categories->toArray());
            $tree = $this->printTree($build, 'vendor');
            $categoryToggle = $this->printTreeToggle($build, $active);
        }

        $addons = AddonSet::with('option')->select('id', 'title', 'min_select', 'max_select', 'position')
                    ->where('status', '!=', 2)
                    ->where('vendor_id', $id)
                    ->orderBy('position', 'asc')->get();

        $langs = ClientLanguage::with('language')->select('language_id', 'is_primary', 'is_active')
                    ->where('is_active', 1)
                    ->orderBy('is_primary', 'desc')->get();
        $templetes = \DB::table('vendor_templetes')->where('status', 1)->get();
        
        return view('backend/vendor/vendorCategory')->with(['vendor' => $vendor, 'tab' => 'category', 'html' => $tree, 'languages' => $langs, 'addon_sets' => $addons, 'VendorCategory' => $VendorCategory, 'categoryToggle' => $categoryToggle, 'templetes' => $templetes]);
    }

    /**   show vendor page - catalog tab      */
    public function vendorCatalog($domain = '', $id){
        $type = Type::all();
        $vendor = Vendor::findOrFail($id);
        $VendorCategory = VendorCategory::where('vendor_id', $id)->where('status', 1)->pluck('category_id')->toArray();

        $categories = Category::with('primary')->select('id', 'slug')
                        ->where('id', '>', '1')->where('status', '!=', '2')->where('type_id', '1')
                        ->where('can_add_products', 1)->orderBy('parent_id', 'asc')->orderBy('position', 'asc')->get();
        $products = Product::with(['media.image', 'primary', 'category.cat', 'brand','variant' => function($v){
                            $v->select('id','product_id', 'quantity', 'price')->groupBy('product_id');
                    }])->select('id', 'sku','vendor_id', 'is_live', 'is_new', 'is_featured', 'has_inventory', 'has_variant', 'sell_when_out_of_stock', 'Requires_last_mile', 'averageRating', 'brand_id')
                    ->where('vendor_id', $id)->get();

        $categories = Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
                        ->where('id', '>', '1')
                        ->where(function($q) use($id){
                              $q->whereNull('vendor_id')
                                ->orWhere('vendor_id', $id);
                        })
                        ->orderBy('position', 'asc')
                        ->orderBy('id', 'asc')
                        ->orderBy('parent_id', 'asc')->get();
        $categoryToggle = array();
        $active = array();
        /*    get active category list also with parent     */
        foreach ($categories as $category) {
          if(in_array($category->id, $VendorCategory) && $category->parent_id == 1){
            $active[] = $category->id;
          }
          if(in_array($category->id, $VendorCategory) && in_array($category->parent_id, $VendorCategory)){
            $active[] = $category->id;
          }
        }
        if($categories){
            $build = $this->buildTree($categories->toArray());
            $categoryToggle = $this->printTreeToggle($build, $active);
        }
        $templetes = \DB::table('vendor_templetes')->where('status', 1)->get();
        return view('backend/vendor/vendorCatalog')->with(['vendor' => $vendor, 'VendorCategory' => $VendorCategory, 'products' => $products, 'tab' => 'catalog', 'typeArray' => $type, 'categories' => $categories, 'categoryToggle' => $categoryToggle, 'templetes' => $templetes]);
    }

    /**       delete vendor       */
    public function destroy($domain = '', $id)
    {
        $vendor = Vendor::where('id', $id)->first();
        $vendor->status = 2;
        $vendor->save();
        return redirect()->back()->with('success', 'Vendor deleted successfully!');
    }

    /**     update vendor configuration data     */
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
            //$vendor->add_category = ($request->has('add_category') && $request->add_category == 'on') ? 1 : 0;
            $vendor->show_slot         = ($request->has('show_slot') && $request->show_slot == 'on') ? 1 : 0;
            $msg = 'commission configuration';
        }
        $vendor->save();
        return redirect()->back()->with('success', $msg.' updated successfully!');
    }

    /**     Activate Category for vendor     */
    public function activeCategory(Request $request, $domain = '', $id)
    {
        $vendor = Vendor::where('id', $id)->firstOrFail();
        $vendor->vendor_templete_id = $request->assignTo;
        $vendor->add_category = ($request->has('add_category') && $request->add_category == 'on') ? 1 : 0;
        $vendor->save();
        $activeCategory = array();
        $data = VendorCategory::where('vendor_id', $id)->delete();
        $enableCategory = array();
        if($request->has('category')){
          foreach($request->category as $key => $value){
            $enableCategory[] = [
              'vendor_id' => $id,
              'category_id' => $key,
              'status' => 1
            ];
          }
        }
        VendorCategory::insert($enableCategory);

        
       /* $vc = VendorCategory::where('vendor_id', $id)->where('category_id', $request->category_id)->first();
        $msg = 'deactivated';
        if(!$vc){
            $vc = new VendorCategory();
            $vc->vendor_id = $id;
            $vc->category_id = $request->category_id;
        }
        $vc->status = $request->status;
        $msg = ($request->status == 1) ? 'activated' : 'deactivated';
        $vc->save();*/
        $toaster = $this->successToaster('Success', 'Category setting saved successfully.');
        return redirect()->back()->with('toaster', $toaster);
    }

    /**     Check parent category enable status - true if all parent, false if any parent disable     */
    public function checkParentStatus(Request $request, $domain = '', $id)
    {
        $blockedCategory = VendorCategory::where('vendor_id', $id)->where('status', 0)->pluck('category_id')->toArray();
        //dd($request->all());
        $is_parent_disabled = $exit = 0;
        $category = Category::where('id', $request->category_id)->select('id', 'parent_id')->first();
        $parent_id = $category->parent_id;

        while($exit == 0){
          if($parent_id == 1){
            $exit = 1;
            break;
          }elseif(in_array($parent_id, $blockedCategory)){
            $is_parent_disabled = 1;
            $exit = 1;
          }else{
            $category = Category::where('id', $parent_id)->select('id', 'parent_id')->first();
            $parent_id = $category->parent_id;
          }
        }

        if($is_parent_disabled == 1){
          return $this->errorResponse('Parent category is disabled. First enable parent category to enable this category.', 422);
        }else{
          return $this->successResponse(null, 'Parent is enabled.');
        }

        // $toaster = $this->successToaster('Success', 'Category '.$msg.' successfully.');
        // return redirect()->back()->with('toaster', $toaster);
          //}
    }
}