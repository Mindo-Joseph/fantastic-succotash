<?php

namespace App\Http\Controllers\Client\CMS;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $pages = Page::orderBy('id', 'DESC')->get();
        return view('backend.cms.page.index', compact('pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = ''){
        $rules = array(
            'title' => 'required',
            'description' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();

        $page = new Page();
        $page->title = $request->title;
        $page->meta_title = $request->meta_title;
        $page->description = $request->description;
        $page->meta_keyword = $request->meta_keyword;
        $page->meta_description = $request->meta_description;
        $page->save();
        return $this->successResponse($page, 'Page Data Saved Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $domain = '', $id){
        $page = Page::findOrFail($id);
        return $this->successResponse($page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = ''){
        $rules = array(
            'edit_title' => 'required',
            'edit_description' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();

        $page = Page::findOrFail($request->page_id);
        $page->title = $request->edit_title;
        $page->meta_title = $request->edit_meta_title;
        $page->description = $request->edit_description;
        $page->meta_keyword = $request->edit_meta_keyword;
        $page->meta_description = $request->edit_meta_description;
        $page->save();
        return $this->successResponse($page, 'Page Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $domain = ''){
        Page::destroy($request->page_id);
        return $this->successResponse([], 'Page Deleted Successfully.');
    }
}
