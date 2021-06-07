<?php

namespace App\Http\Controllers\Client;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, MapProvider, Category, Category_translation, ClientLanguage, Variant, Brand, CategoryHistory, Type, CategoryTag, Vendor};

class CategoryController extends BaseController{
    private $blocking = '2';
    private $folderName = 'category/icon';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $categories = Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
                        ->where('categories.id', '>', '1')
                        ->where('categories.status', '!=', '2')
                        ->where('categories.is_core', 1)
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();
        $variants = Variant::with('option', 'varcategory.cate.primary')->where('status', '!=', 2)->orderBy('position', 'asc')->get();
        $brands = Brand::with( 'bc.cate.primary')->where('status', '!=', 2)->orderBy('position', 'asc')->get();
        if($categories){
            $build = $this->buildTree($categories->toArray());
            $tree = $this->printTree($build);
        }
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->where('client_languages.is_active', 1)
                    ->orderBy('client_languages.is_primary', 'desc')->get();
        return view('backend/catalog/index')->with(['categories' => $categories, 'html' => $tree,  'languages' => $langs, 'variants' => $variants, 'brands' => $brands]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $is_vendor = ($request->has('is_vendor')) ? $request->is_vendor : 0;
        $vendors = array();
        $type = Type::all();
        $category = new Category();
        $parCategory = Category::select('id', 'slug')->where('status', '!=', $this->blocking)->get();
        $vendor_list = Vendor::select('id', 'name')->where('status', '!=', $this->blocking)->get();
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->where('client_languages.is_active', 1)
                    ->orderBy('client_languages.is_primary', 'desc')->get();
        $returnHTML = view('backend.catalog.add-category')->with(['category' => $category, 'is_vendor' => $is_vendor, 'languages' => $langs, 'parCategory' => $parCategory, 'typeArray' => $type, 'vendor_list' => $vendor_list])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = array(
            'name.0' => 'required|string|max:60',
            'slug' => 'required|string|max:30|unique:categories',
        );
        if($request->type == 'Vendor'){
            $rules['vendor_ids'] = "required";
        }
        $validation  = Validator::make($request->all(), $rules)->validate();
        $cate = new Category();
        $save = $this->save($request, $cate, 'false');
        if($save > 0){
            foreach ($request->language_id as $key => $value) {
                $trans = new Category_translation();
                $trans->name = $request->name[$key];
                $trans->meta_title = $request->meta_title[$key];
                $trans->meta_description = $request->meta_description[$key];
                $trans->meta_keywords = $request->meta_keywords[$key];
                $trans->category_id = $save;
                $trans->language_id = $request->language_id[$key];
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
    public function edit(Request $request, $domain = '', $id){
        $is_vendor = ($request->has('is_vendor')) ? $request->is_vendor : 0;
        $vendors = array();
        $type = Type::all();
        $tagList = array();
        $category = Category::with('translation', 'tags')->where('id', $id)->first();
        if(!empty($category->tags)){
            foreach ($category->tags as $key => $value) {
                $tagList[] = $value->tag;
            }
        }
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->where('client_languages.is_active', 1)
                    ->orderBy('client_languages.is_primary', 'desc')->get();

        $existlangs = $langIds = array();
        foreach ($langs as $key => $value) {
            $langIds[] = $langs[$key]->langId;
        }
        foreach ($category->translation as $key => $value) {
            $existlangs[] = $value->language_id;
        }
        $parCategory = Category::select('id', 'slug')->where('categories.id', '!=', $id)->where('status', '!=', $this->blocking)->get();
        $returnHTML = view('backend.catalog.edit-category')->with(['typeArray' => $type, 'category' => $category,  'languages' => $langs, 'is_vendor' => $is_vendor, 'parCategory' => $parCategory, 'langIds' => $langIds, 'existlangs' => $existlangs, 'tagList' => $tagList])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML, 'tagList' => $tagList));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id){
        $rules = array(
            'slug' => 'required|string|max:30|unique:categories,slug,'.$id,
            'name.0' => 'required|string|max:60',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $category = Category::where('id', $id)->first();
        $save = $this->save($request, $category, 'true');
        if($save > 0){
            if($request->has('language_id')){
                foreach ($request->language_id as $key => $value) {
                    $trans = Category_translation::where('category_id', $save)->where('language_id', $value)->first();
                    if(!$trans){
                        $trans = new Category_translation();
                        $trans->category_id = $save;
                        $trans->language_id = $value;
                    }
                    $trans->name = $request->name[$key];
                    $trans->meta_title = $request->meta_title[$key];
                    $trans->meta_description = $request->meta_description[$key];
                    $trans->meta_keywords = $request->meta_keywords[$key];
                    $trans->save();
                }
            }
            $hs = new CategoryHistory();
            $hs->action = 'Update';
            $hs->category_id = $save;
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
        $cate->type_id = $request->type_id;
        $cate->display_mode = $request->display_mode;
        $cate->is_visible = ($request->has('is_visible') && $request->is_visible == 'on') ? 1 : 0;
        $cate->show_wishlist = ($request->has('show_wishlist') && $request->show_wishlist == 'on') ? 1 : 0;
        $cate->can_add_products = ($request->has('can_add_products') && $request->can_add_products == 'on' && $request->type_id == 1) ? 1 : 0;

        if($request->has('parent_cate') && $request->parent_cate > 0){
            $cate->parent_id = $request->parent_cate;
        }else{
            $cate->parent_id = 1;
        }

        if($update == 'false'){
            if($request->has('vendor_id')){
                $cate->is_core = 0;
                $cate->vendor_id = $request->vendor_id;
            }else{
                $cate->is_core = 1;
            }
            $cate->status = 1;
            $cate->position = 1;
            
            $cate->client_code = (!empty(Auth::user()->code)) ? Auth::user()->code : '';
        }

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $cate->icon = Storage::disk('s3')->put($this->folderName, $file,'public');
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $cate->image = Storage::disk('s3')->put('/category/image', $file,'public');
        }
        $cate->save();

        $tagDelete = CategoryTag::where('category_id', $cate->id)->delete();

        if($request->has('tags') && !empty($request->tags)){
            $tagArray = array();
            $tags = explode(',', $request->tags);
            foreach ($tags as $k => $v) {
                $tagArray[] = [
                    'category_id' => $cate->id,
                    'tag' => $v
                ];
            }
            CategoryTag::insert($tagArray);
        }
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
    public function updateOrder(Request $request){
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
    public function destroy($domain = '', $id){
        $category = Category::where('id', $id)->first();
        $category->status = 2;
        $category->save();
        $action = 'deleted';
        $hs = new CategoryHistory();
        $hs->category_id = $category->id;
        $hs->action = $action;
        $hs->updater_role = 'Admin';
        $hs->update_id = Auth::user()->id;
        $hs->client_code = Auth::user()->code;
        $hs->save();
        return redirect()->back()->with('success', 'Category '.$action.' successfully!');
    }
}
