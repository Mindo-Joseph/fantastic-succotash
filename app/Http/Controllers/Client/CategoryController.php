<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use App\Models\{Client, ClientPreference, MapProvider, Category, Category_translation, ClientLanguage, Variant, Brand, CategoryHistory};

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$categories = Category::with('childs')->select('id', 'slug', 'parent_id')->wherenull('parent_id')->get();

        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                        ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.type', 'categories.is_visible', 'categories.status', 'categories.is_core', 'categories.can_add_products', 'categories.parent_id', 'categories.vendor_id', 'cts.name')
                        ->where('categories.id', '>', '1')
                        ->where('categories.status', '!=', '2')
                        ->where('cts.language_id', 1)
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();

        $variants = Variant::with('option', 'varcategory.cate.english')
                        ->where('status', '!=', 2)->orderBy('position', 'asc')->get();
        $brands = Brand::with( 'bc.cate.english')
                        ->where('status', '!=', 2)->orderBy('position', 'asc')->get();

        //dd($variants->toArray());
        if($categories){
            $build = $this->buildTree($categories->toArray());
            //dd($build);
            $tree = $this->printTree($build);
        }
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code')
                    ->where('client_languages.client_code', Auth::user()->code)->get();
        
        //$langs = ClientLanguage::with('language')->where('client_code', Auth::user()->code)->get();

        return view('backend/catalog/index')->with(['categories' => $categories, 'html' => $tree,  'languages' => $langs, 'variants' => $variants, 'brands' => $brands]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = array();
        $category = new Category();
        $parCategory = Category::join('category_translations', 'categories.id', 'category_translations.category_id')
                        ->select('categories.id', 'categories.slug', 'category_translations.name')->get();

        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code')
                    ->where('client_languages.client_code', Auth::user()->code)->get();

        $returnHTML = view('backend.catalog.add-category')->with(['category' => $category,  'languages' => $langs, 'parCategory' => $parCategory])->render();
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
            'slug' => 'required|string|max:30|unique:categories',
            'name.0' => 'required|string|max:60',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();

        $cate = new Category();
        $save = $this->save($request, $cate, 'false');
        if($save > 0){

            foreach ($request->language_id as $key => $value) {
                $trans = new Category_translation();
                $trans->name = $request->name{$key};
                $trans->meta_title = $request->meta_title{$key};
                $trans->meta_description = $request->meta_description{$key};
                $trans->meta_keywords = $request->meta_keywords{$key};
                $trans->category_id = $save;
                $trans->language_id = $request->language_id{$key};
                $trans->save();
            }

            $hs = new CategoryHistory();
            $hs->category_id = $save;
            $hs->action = 'Add';
            $hs->updater_role = 'Admin';
            $hs->update_id = Auth::user()->id;
            $hs->client_code = Auth::user()->code;
            $hs->save();

            return response()->json([
                'status'=>'success',
                'message' => 'Category created Successfully!',
                'data' => $save
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendors = array();
        $category = Category::with('translation')->where('id', $id)->first();

        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->leftjoin('category_translations as cts', 'client_languages.language_id', 'cts.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'cts.id as trans_id', 'cts.name as cateName', 'cts.meta_title', 'cts.meta_description', 'cts.meta_keywords', 'cts.category_id')
                    ->where('cts.category_id', $id)
                    ->where('client_languages.client_code', Auth::user()->code)->get();

        $parCategory = Category::join('category_translations', 'categories.id', 'category_translations.category_id')
                        ->select('categories.id', 'categories.slug', 'category_translations.name')->where('categories.id', '!=', $id)->groupBy('category_translations.category_id')->get();
        
        $returnHTML = view('backend.catalog.edit-category')->with(['category' => $category,  'languages' => $langs, 'parCategory' => $parCategory])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'slug' => 'required|string|max:30|unique:categories,slug,'.$id,
            'name.0' => 'required|string|max:60',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();

        $category = Category::where('id', $id)->first();
        $save = $this->save($request, $category, 'true');
        if($save > 0){

            foreach ($request->trans_id as $key => $value) {

                $trans = Category_translation::where('id', $request->trans_id{$key})->first();
                $trans->name = $request->name{$key};
                $trans->meta_title = $request->meta_title{$key};
                $trans->meta_description = $request->meta_description{$key};
                $trans->meta_keywords = $request->meta_keywords{$key};
                $trans->save();
            }

            $hs = new CategoryHistory();
            $hs->category_id = $save;
            $hs->action = 'Update';
            $hs->updater_role = 'Admin';
            $hs->update_id = Auth::user()->id;
            $hs->client_code = Auth::user()->code;
            $hs->save();

            return response()->json([
                'status'=>'success',
                'message' => 'Category created Successfully!',
                'data' => $save
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
    public function save(Request $request, Category $cate, $update = 'false')
    {
        $cate->slug = $request->slug;
        $cate->type = $request->type;
        $cate->display_mode = $request->display_mode;
        $cate->is_visible = ($request->has('is_visible') && $request->is_visible == 'on') ? 1 : 0;
        $cate->can_add_products = ($request->has('can_add_products') && $request->can_add_products == 'on') ? 1 : 0;

        if($request->has('parent_cate') && $request->parent_cate > 0){
            $cate->parent_id = $request->parent_cate;
        }else{
            $cate->parent_id = 1;
        }

        if($update == 'false'){

            if($request->login_user_type != 'client'){
                $cate->is_core = 0;
                $cate->vendor_id = Auth::user()->id;
            }else{
                $cate->is_core = 1;
            }

            $cate->status = 1;
            $cate->position = 1;
            
            $cate->client_code = (!empty(Auth::user()->code)) ? Auth::user()->code : '';
        }

        if ($request->hasFile('icon')) {    /* upload category icon */
            $file = $request->file('icon');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            //$s3filePath = '/assets/Clientlogo/' . $file_name;
            //$path = Storage::disk('s3')->put($s3filePath, $file,'public');
            $cate->icon = $request->file('icon')->storeAs('/category/icon', $file_name, 'public');
        }
        if ($request->hasFile('image')) {    /* upload category image */
            $file = $request->file('image');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            $cate->image = $request->file('image')->storeAs('/category/image', $file_name, 'public');
        }
        $cate->save();
        return $cate->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function show(Category_translation $category_translation)
    {
        //
    }

    /**
     * Update the order of categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request)
    {
        $data = json_decode($request->orderDta);
        $arr = $this->buildArray($data);
        if($arr > 0){
            return redirect('client/category')->with('success', 'Category order updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('id', $id)->first();
        $category->status = 2;
        $category->save();

        $hs = new CategoryHistory();
        $hs->category_id = $category->id;
        $hs->action = 'Block';
        $hs->updater_role = 'Admin';
        $hs->update_id = Auth::user()->id;
        $hs->client_code = Auth::user()->code;
        $hs->save();
        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}
