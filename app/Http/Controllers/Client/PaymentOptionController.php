<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use App\Models\{Client, ClientPreference, PaymentOption};
use Illuminate\Support\Facades\Storage;

class PaymentOptionController extends BaseController
{
    private $folderName = 'payoption';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $code = array('COD', 'paypal', 'stripe');
        $payOption = PaymentOption::whereIn('code', $code)->get();
        return view('backend/payoption/index')->with(['payOption' => $payOption]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::with('english')
                        ->select('id', 'slug')
                        ->where('id', '>', '1')
                        ->where('status', '!=', '2')
                        ->orderBy('parent_id', 'asc')
                        ->orderBy('position', 'asc')->get();

        $langs = ClientLanguage::with('language')->select('language_id', 'is_primary', 'is_active')
                    ->where('is_active', 1)
                    ->orderBy('is_primary', 'desc')->get();


        $returnHTML = view('backend.catalog.add-brand')->with(['categories' => $categories,  'languages' => $langs])->render();
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
        $data = $data_cate = array();
        if($request->has('title')){
            $brand = new Brand();
            $brand_pos = Brand::select('id','position')->where('position', \DB::raw("(select max(`position`) from brands)"))->first();

            $brand->title = $request->title[0];
            $brand->position = 1;

            if($brand_pos){
                $brand->position = $brand_pos->position + 1;
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                //$file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                //$brand->image = $request->file('image')->storeAs('/brand', $file_name, 'public');
                $brand->image = Storage::disk('s3')->put($this->folderName, $file,'public');
            }else{
                $brand->image = 'default/default_image.png';
            }

            $brand->save();

            if($brand->id > 0){

                $data_cate['brand_id'] = $brand->id;
                $data_cate['category_id'] = $request->cate_id;

                BrandCategory::insert($data_cate);

                foreach ($request->title as $key => $value) {
                    $data[] = [
                        'title' => $value,
                        'brand_id' => $brand->id,
                        'language_id' => $request->language_id[$key]
                    ];
                }

                BrandTranslation::insert($data);
            }
            return redirect()->back()->with('success', 'Brand added successfully!');
        }else{
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $brand = Brand::with('translation', 'bc')->where('id', $id)->firstOrFail();
        $categories = Category::with('english')
                        ->select('id', 'slug')
                        ->where('id', '>', '1')
                        ->where('status', '!=', '2')
                        ->orderBy('parent_id', 'asc')
                        ->orderBy('position', 'asc')->get();

        $langs = ClientLanguage::with(['language', 'brand_trans' => function($query) use ($id) {
                        $query->where('brand_id', $id);
                    }])
                    ->select('language_id', 'is_primary', 'is_active')
                    ->where('is_active', 1)
                    ->orderBy('is_primary', 'desc')->get();
        $submitUrl = route('brand.update', $id);

        $returnHTML = view('backend.catalog.edit-brand')->with(['categories' => $categories,  'languages' => $langs, 'brand' => $brand])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML, 'submitUrl' => $submitUrl));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $brand = Brand::where('id', $id)->firstOrFail();
        $brand->title = $request->title[0];
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            //$file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            //$brand->image = $request->file('image')->storeAs('/brand', $file_name, 'public');
            $brand->image = Storage::disk('s3')->put($this->folderName, $file,'public');
        }
        $brand->save();

        $affected = BrandCategory::where('brand_id', $brand->id)->update(['category_id' => $request->cate_id]);

        foreach ($request->title as $key => $value) {

            $bt = BrandTranslation::where('brand_id', $brand->id)->where('language_id', $request->language_id[$key])->first();
            if(!$bt){
                $bt = new BrandTranslation();
                $bt->brand_id = $brand->id;
                $bt->language_id = $request->language_id[$key];
            }
            $bt->title = $value;
            $bt->save();
        }
        return redirect()->back()->with('success', 'Brand updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        $brand = Brand::where('id', $id)->first();
        $brand->status = 2;
        $brand->save();
        return redirect()->back()->with('success', 'Brand deleted successfully!');
    }

    /**
     * save the order of variant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function updateOrders(Request $request)
    {
        $arr = explode(',', $request->orderData);
        foreach ($arr as $key => $value) {
            $brand = Brand::where('id', $value)->first();
            if($brand){
                $brand->position = $key + 1;
                $brand->save();
            }
        }
        return redirect('client/category')->with('success', 'Brand order updated successfully!');
    }
}
