<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{Variant, VariantOption, VariantTranslation, VariantOptionTranslation, VariantCategory, Category, ClientLanguage};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Validator;

class VariantController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->orderBy('client_languages.is_primary', 'desc')->get();
        $returnHTML = view('backend.catalog.add-variant')->with(['categories' => $categories,  'languages' => $langs])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v_pos = Variant::select('id','position')->where('position', \DB::raw("(select max(`position`) from variants)"))->first();
        
        $variant = new Variant();
        $variant->title = (!empty($request->title[0])) ? $request->title[0] : '';
        $variant->type = $request->type;

        $variant->position = 1;
        if($v_pos){
            $variant->position = $v_pos->position + 1;
        }
        $variant->save();

        $data = $data_cate = array();

        if($variant->id > 0){

            $data_cate['variant_id'] = $variant->id;
            $data_cate['category_id'] = $request->cate_id;
            VariantCategory::insert($data_cate);

            foreach ($request->title as $key => $value) {
                $varTrans = new VariantTranslation();
                $varTrans->title = $request->title{$key};
                $varTrans->variant_id = $variant->id;
                $varTrans->language_id = $request->language_id{$key};
                $varTrans->save();
            }

            foreach ($request->hexacode as $key => $value) {

                $varOpt = new VariantOption();
                $varOpt->title = $request->opt_color[0]{$key};
                $varOpt->variant_id = $variant->id;
                $varOpt->hexacode = ($value == '') ? '' : $value;
                $varOpt->save();

                foreach($request->language_id as $k => $v) {

                    /*$varOptTrans = new VariantOptionTranslation();
                    $varOptTrans->title = $request->opt_color{$k}{$key};
                    $varOptTrans->variant_option_id = $varOpt->id;
                    $varOptTrans->language_id = $v;
                    $varOptTrans->save();*/

                    $data[] = [
                        'title' => $request->opt_color{$k}{$key},
                        'variant_option_id' => $varOpt->id,
                        'language_id' => $v
                    ];
                    
                }
            }
            VariantOptionTranslation::insert($data);
        }
        return redirect()->back()->with('success', 'Variant added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function show(Variant $variant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $variant = Variant::with('translation', 'option.translation', 'varcategory')
                        ->where('id', $id)->firstOrFail();
        
        $categories = Category::with('english')
                        ->select('id', 'slug')
                        ->where('id', '>', '1')
                        ->where('status', '!=', '2')
                        ->orderBy('parent_id', 'asc')
                        ->orderBy('position', 'asc')->get();

        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->orderBy('client_languages.is_primary', 'desc')->get();

        $langIds = array();
        foreach ($langs as $key => $value) {
            $langIds[] = $langs{$key}->langId;
        }
        $existlangs = array();
        foreach ($variant->translation as $key => $value) {
            $existlangs[] = $value->language_id;
        }

        $submitUrl = route('variant.update', $id);

        $returnHTML = view('backend.catalog.edit-variant')->with(['categories' => $categories,  'languages' => $langs, 'variant' => $variant, 'langIds' => $langIds, 'existlangs' => $existlangs])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML, 'submitUrl' => $submitUrl));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $variant = Variant::where('id', $id)->firstOrFail();
        $variant->title = $request->title[0];
        $variant->type = $request->type;
        $variant->save();

        $affected = VariantCategory::where('variant_id', $variant->id)->update(['category_id' => $request->cate_id]);

        foreach ($request->language_id as $key => $value) {

            $varTrans = VariantTranslation::where('language_id', $value)->where('variant_id', $variant->id)->first();
            if(!$varTrans){
                $varTrans = new VariantTranslation();
                $varTrans->variant_id = $variant->id;
                $varTrans->language_id = $value;
            }
            $varTrans->title = $request->title{$key};
            $varTrans->save();
        }

        $exist_options = array();
        foreach ($request->option_id as $key => $value) {

            $varOpt = VariantOption::where('id', $value)->first();

            if(!$varOpt){
                $varOpt = new VariantOption();
                $varOpt->variant_id = $variant->id;
            }
            $varOpt->title = $request->opt_color[1][$key];
            $varOpt->hexacode = ($request->hexacode[$key] == '') ? '' : $request->hexacode[$key];
            $varOpt->save();
            $exist_options[$key] = $value;
        }

        foreach($request->opt_id as $lid => $options) {

            foreach($options as $key => $value) {

                $varOptTrans = VariantOptionTranslation::where('language_id', $lid)->where('variant_option_id', $value)->first();
                if(!$varOptTrans){
                    $varOptTrans = new VariantOptionTranslation();
                    $varOptTrans->variant_option_id =$exist_options[$key];
                    $varOptTrans->language_id = $lid;
                }
                $varOptTrans->title = $request->opt_color{$lid}{$key};
                $varOptTrans->save();
            }
        }

        if($request->has('opt_color_new') && count($request->opt_color_new) > 0)

        foreach($request->opt_color_new as $lanId => $optValue) {

            foreach($optValue as $key => $value) {
                $varOptTrans = new VariantOptionTranslation();
                $varOptTrans->variant_option_id =$exist_options[$key];
                $varOptTrans->language_id = $lanId;
                $varOptTrans->title = $value;
                $varOptTrans->save();
            }
        }
        $delOpt = VariantOption::whereNotIN('id', $exist_options)->where('variant_id', $variant->id)->delete();
        return redirect()->back()->with('success', 'Variant updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category_translation  $category_translation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $var = Variant::where('id', $id)->first();
        $var->status = 2;
        $var->save();
        return redirect()->back()->with('success', 'Variant deleted successfully!');
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
            $variant = Variant::where('id', $value)->first();
            if($variant){
                $variant->position = $key + 1;
                $variant->save();
            }
        }
        return redirect('client/category')->with('success', 'Variant order updated successfully!');
    }

    /**
     * save the order of variant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function makeHtmlData($variants)
    {
        $html = '<div class="row mb-2">';
      
        foreach ($variants as $vk => $var) {
            $html .= '<div class="col-sm-3"> <label class="control-label">'.$var->title.'</label> </div> 
                    <div class="col-sm-9">';
            foreach ($var->option as $key => $opt) {
                $html .='<div class="checkbox checkbox-success form-check-inline pr-3">
                    <input type="checkbox" name="variant'.$var->id.'" class="intpCheck" opt="'.$opt->id.';'.$opt->title.'" varId="'.$var->id.';'.$var->title.'" id="opt_vid_'.$opt->id.'"> 
                    <label  for="opt_vid_'.$opt->id.'">'.$opt->title.'</label></div>';
            }

            $html .='</div>';
        }
        $html .='<div>';
        return $html;
    }

    /**
     * save the order of variant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function variantbyCategory($cid)
    {
        $variants = Variant::with('option', 'varcategory.cate.english')
                        ->select('variants.*')
                        ->join('variant_categories', 'variant_categories.variant_id', 'variants.id')
                        ->where('variant_categories.category_id', $cid)
                        ->where('variants.status', '!=', 2)
                        ->orderBy('position', 'asc')->get();

        $makeHtml = $this->makeHtmlData($variants);
        return response()->json(array('success' => true, 'resp'=>$makeHtml));
    }

}
