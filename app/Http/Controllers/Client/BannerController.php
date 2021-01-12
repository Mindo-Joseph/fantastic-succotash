<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Image;

class BannerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::orderBy('sorting', 'asc')->get();
        
        return view('backend/banner/index')->with(['banners' => $banners]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = array();
        $categories = array();
        $banner = new Banner();
        $returnHTML = view('backend.banner.form')->with(['banner' => $banner,  'vendors' => $vendors, 'categories' => $categories])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendors = array();
        $categories = array();
        $banner = Banner::where('id', $id)->first();
        $returnHTML = view('backend.banner.form')->with(['banner' => $banner,  'vendors' => $vendors, 'categories' => $categories])->render();
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
        $rules = array(
            'name' => 'required|string|max:150',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $banner = new Banner();
        $savebanner = $this->save($request, $banner, 'false');
        if($savebanner > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Banner created Successfully!',
                'data' => $banner
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'name' => 'required|string|max:150',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();

        $banner = Banner::find($id);
        $savebanner = $this->save($request, $banner, 'true');
        if($savebanner > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Banner updated Successfully!',
                'data' => $banner
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
    public function save(Request $request, Banner $banner, $update = 'false')
    {
        $banner->validity_on = ($request->has('validity_on') && $request->validity_on == 'on') ? 1 : 0; 
        $banner->name = $request->name;
        $banner->start_date_time = $request->start_date_time;
        $banner->end_date_time = $request->end_date_time;

        if( $update == 'false'){
            $bannerSort = Banner::select('id', 'sorting')->where('sorting', \DB::raw("(select max(`sorting`) from banners)"))->first();
            $banner->sorting = $bannerSort->sorting + 1;
        }
        //$banner->redirect_category_id = $request->redirect_category_id;
        //$banner->redirect_vendor_id = $request->redirect_vendor_id;

        if ($request->hasFile('image')) {    /* upload logo file */
            $file = $request->file('image');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            //$s3filePath = '/assets/Clientlogo/' . $file_name;
            //$path = Storage::disk('s3')->put($s3filePath, $file,'public');
            $banner->image = $request->file('image')->storeAs('/banner', $file_name, 'public');
        }
        $banner->save();
        return $banner->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        banner::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Banner deleted successfully!');
    }

    /**
     * save the order of banner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function saveOrder(Request $request)
    {
        foreach ($request->order as $key => $value) {
            $banner = Banner::where('id', $value)->first();
            $banner->sorting = $key + 1;
            $banner->save();
        }
        return response()->json([
            'status'=>'success',
            'message' => 'Banner order updated Successfully!',
        ]);

    }

    /**
     * update the validity of banner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function validity(Request $request)
    {
        $banner = Banner::where('id', $request->banId)->first();
        $banner->validity_on = ($request->value == 1) ? 1 : 0;
        $banner->save();
        return response()->json([
            'status'=>'success',
            'message' => 'Banner order updated Successfully!',
        ]);

    }

}
