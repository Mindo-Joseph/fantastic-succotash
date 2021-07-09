<?php

namespace App\Http\Controllers\Client;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, MapProvider, Category, Category_translation, ClientLanguage, Variant, Brand, CategoryHistory, Type, CategoryTag, Vendor, DispatcherWarningPage, DispatcherTemplateTypeOption};
use GuzzleHttp\Client as GCLIENT;
class CategoryController extends BaseController{
    private $blocking = '2';
    private $folderName = 'category/icon';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $brands = Brand::with( 'bc.cate.primary')->where('status', '!=', 2)->orderBy('position', 'asc')->get();
        $variants = Variant::with('option', 'varcategory.cate.primary')->where('status', '!=', 2)->orderBy('position', 'asc')->get();
        $categories = Category::with('translation_one')->where('id', '>', '1')->where('is_core', 1)->orderBy('parent_id', 'asc')->orderBy('position', 'asc')->where('deleted_at', NULL)->get();
        if($categories){
            $build = $this->buildTree($categories->toArray());
            $tree = $this->printTree($build);
        }
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->where('client_languages.is_active', 1)
                    ->orderBy('client_languages.is_primary', 'desc')->get();
        return view('backend.catalog.index')->with(['categories' => $categories, 'html' => $tree,  'languages' => $langs, 'variants' => $variants, 'brands' => $brands]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
        $is_vendor = ($request->has('is_vendor')) ? $request->is_vendor : 0;
        $vendors = array();
        $category = new Category();
        $preference = ClientPreference::first();
        $type = Type::where('title','!=', 'Pickup/Parent')->orderBY('sequence', 'ASC')->get();
        $parCategory = Category::with('translation_one')->select('id', 'slug')->where('deleted_at', NULL)->get();
        $vendor_list = Vendor::select('id', 'name')->where('status', '!=', $this->blocking)->get();
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->where('client_languages.is_active', 1)
                    ->orderBy('client_languages.is_primary', 'desc')->get();
        $dispatcher_warning_page_options = DispatcherWarningPage::where('status', 1)->get();
        $dispatcher_template_type_options = DispatcherTemplateTypeOption::where('status', 1)->get();
        $returnHTML = view('backend.catalog.add-category')->with(['category' => $category, 'is_vendor' => $is_vendor, 'languages' => $langs, 'parCategory' => $parCategory, 'typeArray' => $type, 'vendor_list' => $vendor_list,'dispatcher_template_type_options'=> $dispatcher_template_type_options, 'dispatcher_warning_page_options' => $dispatcher_warning_page_options, 'preference' => $preference])->render();
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
        $tagList = array();
        $preference = ClientPreference::first();
        $type = Type::where('title','!=', 'Pickup/Parent')->orderBY('sequence', 'ASC')->get();
        $category = Category::with('translation', 'tags')->where('id', $id)->first();
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
        $parCategory = Category::with('translation_one')->select('id', 'slug')->where('categories.id', '!=', $id)->where('status', '!=', $this->blocking)->where('deleted_at', NULL)->get();
        $dispatcher_warning_page_options = DispatcherWarningPage::where('status', 1)->get();
        $dispatcher_template_type_options = DispatcherTemplateTypeOption::where('status', 1)->get();
        $returnHTML = view('backend.catalog.edit-category')->with(['typeArray' => $type, 'category' => $category,  'languages' => $langs, 'is_vendor' => $is_vendor, 'parCategory' => $parCategory, 'langIds' => $langIds, 'existlangs' => $existlangs, 'tagList' => $tagList,'dispatcher_warning_page_options' => $dispatcher_warning_page_options, 'dispatcher_template_type_options' => $dispatcher_template_type_options, 'preference' => $preference])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
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
        $cate->warning_page_id = $request->warning_page_id;
        $cate->template_type_id = $request->template_type_id;
        $cate->warning_page_design = $request->warning_page_design;
        $cate->is_visible = ($request->has('is_visible') && $request->is_visible == 'on') ? 1 : 0;
        $cate->show_wishlist = ($request->has('show_wishlist') && $request->show_wishlist == 'on') ? 1 : 0;
        $cate->can_add_products = ($request->has('can_add_products') && $request->can_add_products == 'on' && ($request->type_id == 1 || $request->type_id == 3)) ? 1 : 0;
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
        $user = Auth::user();
        Category::where('id', $id)->delete();
        CategoryHistory::insert([
            'category_id' => $id,
            'action' => 'deleted',
            'update_id' =>$user->id,
            'updater_role' =>'Admin',
            'client_code' => $user->code,
        ]);
        return redirect()->back()->with('success', 'Category deleted successfully!');
    }









      # get dispatcher tags from dispatcher panel  
      public function getDispatcherTags(){
        try {   
            $dispatch_domain = $this->checkIfPickupDeliveryOn();
                if ($dispatch_domain && $dispatch_domain != false) {
                            $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->pickup_delivery_service_key,
                                                        'shortcode' => $dispatch_domain->pickup_delivery_service_key_code,
                                                        'content-type' => 'application/json']
                                                            ]);
                            $url = $dispatch_domain->pickup_delivery_service_key_url;                      
                            $res = $client->get($url.'/api/get-agent-tags');
                            $response = json_decode($res->getBody(), true); 
                            if($response && $response['message'] == 'success'){
                                return $response['tags'];
                            }
                    
                }
            }    
            catch(\Exception $e){
              
            }
    }
    # check if last mile delivery on 
    public function checkIfPickupDeliveryOn(){
        $preference = ClientPreference::first();
        if($preference->need_dispacher_ride == 1 && !empty($preference->pickup_delivery_service_key) && !empty($preference->pickup_delivery_service_key_code) && !empty($preference->pickup_delivery_service_key_url))
            return $preference;
        else
            return false;
    }

}
