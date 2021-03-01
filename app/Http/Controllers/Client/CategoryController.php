<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\{Client, ClientPreference, MapProvider, Category, Category_translation, ClientLanguage, Variant, Brand, CategoryHistory, Type, CategoryTag};

class CategoryController extends BaseController
{
    private $folderName = 'category/icon';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$categories = Category::with('childs')->select('id', 'slug', 'parent_id')->wherenull('parent_id')->get();

        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id', 'type')
                        ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.type_id', 'categories.is_visible', 'categories.status', 'categories.is_core', 'categories.can_add_products', 'categories.parent_id', 'categories.vendor_id', 'cts.name')
                        ->where('categories.id', '>', '1')
                        ->where('categories.status', '!=', '2')
                        ->where('cts.language_id', 1)
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();

        $variants = Variant::with('option', 'varcategory.cate.primary')
                        ->where('status', '!=', 2)->orderBy('position', 'asc')->get();
        $brands = Brand::with( 'bc.cate.primary')
                        ->where('status', '!=', 2)->orderBy('position', 'asc')->get();

        //dd($variants->toArray());
        if($categories){
            $build = $this->buildTree($categories->toArray());
            //dd($build);
            $tree = $this->printTree($build);
        }
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->orderBy('client_languages.is_primary', 'desc')->get();
        //dd($langs->toArray());
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
        $type = Type::all();
        $category = new Category();
        $parCategory = Category::join('category_translations', 'categories.id', 'category_translations.category_id')
                        ->select('categories.id', 'categories.slug', 'category_translations.name')->get();

        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->orderBy('client_languages.is_primary', 'desc')->get();

        $returnHTML = view('backend.catalog.add-category')->with(['category' => $category,  'languages' => $langs, 'parCategory' => $parCategory, 'typeArray' => $type])->render();
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
                    ->orderBy('client_languages.is_primary', 'desc')->get();

        $existlangs = $langIds = array();
        foreach ($langs as $key => $value) {
            $langIds[] = $langs{$key}->langId;
        }
        foreach ($category->translation as $key => $value) {
            $existlangs[] = $value->language_id;
        }

        $parCategory = Category::join('category_translations', 'categories.id', 'category_translations.category_id')
                        ->select('categories.id', 'categories.slug', 'category_translations.name')->where('categories.id', '!=', $id)->groupBy('category_translations.category_id')->get();
        
        $returnHTML = view('backend.catalog.edit-category')->with(['typeArray' => $type, 'category' => $category,  'languages' => $langs, 'parCategory' => $parCategory, 'langIds' => $langIds, 'existlangs' => $existlangs, 'tagList' => $tagList])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML, 'tagList' => $tagList));
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
        $cate->type_id = $request->type_id;
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
