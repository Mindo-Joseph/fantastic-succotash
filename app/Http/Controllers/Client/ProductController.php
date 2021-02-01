<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\{Client, Product, Category, ProductTranslation, Type, Vendor, AddonSet, ProductUpSell, ProductRelated, ProductCrossSell, ProductAddon, ProductCategory, ClientLanguage, ProductVariant, ProductImage, TaxCategory, ProductVariantSet, Country, Variant};

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $vendor = Vendor::findOrFail($id);
        $type = Type::all();
        $countries = Country::all();
        $addons = AddonSet::with('option')->select('id', 'title')
                        ->where('status', '!=', 2)
                        ->where('vendor_id', $id)
                        ->orderBy('position', 'asc')->get();

        $categories = Category::with('english')->select('id', 'slug')
                        ->where('id', '>', '1')->where('status', '!=', '2')
                        ->where('can_add_products', 1)->orderBy('parent_id', 'asc')
                        ->orderBy('position', 'asc')->get();

        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code')
                    ->where('client_languages.client_code', Auth::user()->code)->get();

        $taxCate = TaxCategory::all();


        // dd($categories->toArray());
        return view('backend/product/create', ['typeArray' => $type, 'categories' => $categories, 'vendor_id' => $vendor->id, 'addons' => $addons, 'languages' => $langs, 'taxCate' => $taxCate, 'countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $rule = array(
            'sku' => 'required|unique:products',
        );

        $validation  = Validator::make($request->all(), $rule);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $yes = '2';

        $product = new Product();
        foreach ($request->only('sku', 'is_live', 'url_slug', 'vendor_id', 'type_id', 'country_origin_id', 'weight', 'weight_unit') as $key => $value) {
            $product->{$key} = $value;
        }
        $product->is_new                    = ($request->has('is_new') && $request->is_new == 'on') ? 1 : 0;
        $product->is_featured               = ($request->has('is_featured') && $request->is_featured == 'on') ? 1 : 0;
        $product->is_physical               = ($request->has('is_physical') && $request->is_physical == 'on') ? 1 : 0;
        $product->has_inventory             = ($request->has('has_inventory') && $request->has_inventory == 'on') ? 1 : 0;
        $product->sell_when_out_of_stock    = ($request->has('sell_stock_out') && $request->sell_stock_out == 'on') ? 1 : 0;
        $product->requires_shipping         = ($request->has('require_ship') && $request->require_ship == 'on') ? 1 : 0;
        $product->Requires_last_mile        = ($request->has('last_mile') && $request->last_mile == 'on') ? 1 : 0;

       
        $product->publish_at = ($request->is_live == 1) ? date('Y-m-d H:i:s') : '';
        $product->category_id = ($request->has('category') && count($request->category) > 0) ? $request->category[0] : '';

        $product->save();

        if($product->id > 0){
            $updateImage = array();            

            if($request->has('fileIds')){
                $image = ProductImage::whereIn('id', $request->fileIds)->update(['product_id' => $product->id]);
            }

            $cat = $addonsArray = $upArray = $crossArray = $relateArray = array();

            if($request->has('category') && count($request->category) > 0){
                foreach ($request->category as $key => $value) {
                    $cat[] = [
                        'product_id' => $product->id,
                        'Category_id' => $value
                    ];
                }
                ProductCategory::insert($cat);
            }

            if($request->has('addon_sets') && count($request->addon_sets) > 0){
                foreach ($request->addon_sets as $key => $value) {
                    $addonsArray[] = [
                        'product_id' => $product->id,
                        'addon_id' => $value
                    ];
                }
                ProductAddon::insert($addonsArray);
            }

            if($request->has('up_cell') && count($request->up_cell) > 0 && $yes == '1'){
                foreach ($request->up_cell as $key => $value) {
                    $upArray[] = [
                        'product_id' => $product->id,
                        'upsell_product_id' => $value
                    ];
                }
                ProductUpSell::insert($upArray);
            }

            if($request->has('cross_cell') && count($request->cross_cell) > 0 && $yes == '1'){
                foreach ($request->cross_cell as $key => $value) {
                    $crossArray[] = [
                        'product_id' => $product->id,
                        'cross_product_id' => $value
                    ];
                }
                ProductCrossSell::insert($crossArray);
            }

            if($request->has('releted_product') && count($request->releted_product) > 0 && $yes == '1'){
                foreach ($request->releted_product as $key => $value) {
                    $relateArray[] = [
                        'product_id' => $product->id,
                        'related_product_id' => $value
                    ];
                }
                ProductRelated::insert($relateArray);
            }
            $prodVarSet = array();
            $i = 0;

            $varOptArray = array();

            $varOpts = explode(';', $request->all_variant_set);
            foreach ($varOpts as $varOpt) { //1=>1,2,3
                $ops = explode('=>', $varOpt);
                $varOptArray[$ops[0]] = explode(',' ,$ops[1]);
            }

            if($request->has('variant_skus') && !empty($request->variant_skus)){
                foreach ($request->variant_skus as $key => $value) {
                    $proVariant = new ProductVariant();
                    $proVariant->sku = $value.'-'.$product->id;
                    $proVariant->product_id = $product->id;
                    $proVariant->title = $request->variant_titles{$key};
                    $proVariant->quantity = $request->variant_quantity{$key};
                    $proVariant->price = $request->variant_price{$key};
                    $proVariant->position = 1;
                    $proVariant->compare_at_price = $request->variant_compare_price{$key};
                    $proVariant->barcode = $this->generateBarcodeNumber();
                    $proVariant->cost_price = $request->variant_cost_price{$key};
                    $proVariant->currency_id = 1;
                    $proVariant->tax_category_id = $request->tax_category;
                    $proVariant->inventory_policy = '';
                    $proVariant->fulfillment_service = '';
                    $proVariant->inventory_management = '';
                    $proVariant->save();

                    $img = ''; $fname = 'variantImage-'.$key;
                    if($request->has($fname) && !empty($request->{$fname})){
                        $image = new ProductImage();
                        $image->media_type = 1;
                        $file = $request->file($fname);
                        $image->product_id = $product->id;
                        $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                        //$s3filePath = '/assets/Clientlogo/' . $file_name;
                        //$path = Storage::disk('s3')->put($s3filePath, $file,'public');
                        $image->path = $request->file($fname)->storeAs('/product', $file_name, 'public');
                        $image->save();
                        $img = $image->id;

                    }
                    foreach ($request->variant{$key} as $k => $v) {

                        $prodVarSet[$i] = [
                            'product_id' => $product->id,
                            'product_variant_id' => $proVariant->id,
                            'variant_option_id' => $v,
                        ];

                        foreach ($varOptArray as $key => $value) {

                            if(in_array($v, $value)){
                                $prodVarSet[$i]['variant_type_id'] = $key;
                            }

                        }
                        
                        if(!empty($img)){
                            $prodVarSet[$i]['media_id'] = $img;
                        }
                        $i++;
                    } 
                }

                ProductVariantSet::insert($prodVarSet);

            }else{
                $proVariant = new ProductVariant();
                $proVariant->sku = $request->sku;
                $proVariant->product_id = $product->id;
                $proVariant->title = $request->product_name;
                $proVariant->quantity = $request->quantity;
                $proVariant->price = $request->price;
                $proVariant->position = 1;
                $proVariant->compare_at_price = $request->compare_at_price;
                $proVariant->barcode = $this->generateBarcodeNumber();
                $proVariant->cost_price = $request->cost_price;
                $proVariant->currency_id = 1;
                $proVariant->tax_category_id = $request->tax_category;
                $proVariant->inventory_policy = '';
                $proVariant->fulfillment_service = '';
                $proVariant->inventory_management = '';
                $proVariant->save();
            }

            $datatrans[] = [
                'title' => $request->product_name,
                'body_html' => $request->body_html,
                'meta_title' => $request->meta_title,
                'meta_keyword' => $request->meta_keyword,
                'meta_description' => $request->meta_description,
                'product_id' => $product->id,
                'language_id' => $request->language_id
            ];
            ProductTranslation::insert($datatrans);

        }
        return redirect()->back()->with('success', 'Product added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('variant.set', 'english', 'category.cat','variantSet', 'addOn', 'media')->where('id', $id)->firstOrFail();
        //dd($product->toArray());
        $type = Type::all();
        $countries = Country::all();
        $addons = AddonSet::with('option')->select('id', 'title')
                        ->where('status', '!=', 2)
                        ->where('vendor_id', $product->vendor_id)
                        ->orderBy('position', 'asc')->get();

        /*$categories = Category::with('english')->select('id', 'slug')
                        ->where('id', '>', '1')->where('status', '!=', '2')
                        ->where('can_add_products', 1)->orderBy('parent_id', 'asc')
                        ->orderBy('position', 'asc')->get();*/

        $clientLanguages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code')
                    ->where('client_languages.client_code', Auth::user()->code)->get();

        
        $productVariants = Variant::with('option', 'varcategory.cate.english')
                        ->select('variants.*')
                        ->join('variant_categories', 'variant_categories.variant_id', 'variants.id')
                        ->where('variant_categories.category_id', $product->category->category_id)
                        ->where('variants.status', '!=', 2)
                        ->orderBy('position', 'asc')->get();

        $taxCate = TaxCategory::all();

        $existOptions = $addOn_ids = array();

        foreach ($product->addOn as $key => $value) {
            $addOn_ids[] = $value->addon_id;
        }

        foreach ($product->variantSet as $key => $value) {

            if(!in_array($value->variant_option_id, $existOptions)){
                $existOptions[] = $value->variant_option_id;
            }
        }

        return view('backend/product/edit', ['typeArray' => $type, 'addons' => $addons, 'productVariants' => $productVariants, 'languages' => $clientLanguages, 'taxCate' => $taxCate, 'countries' => $countries, 'product' => $product, 'addOn_ids' => $addOn_ids, 'existOptions' => $existOptions]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());

        $product = Product::where('id', $id)->firstOrFail();

        $yes = '2';
        foreach ($request->only('is_live', 'url_slug', 'vendor_id', 'type_id', 'country_origin_id', 'weight', 'weight_unit') as $key => $value) {
            $product->{$key} = $value;
        }
        $product->is_new                    = ($request->has('is_new') && $request->is_new == 'on') ? 1 : 0;
        $product->is_featured               = ($request->has('is_featured') && $request->is_featured == 'on') ? 1 : 0;
        $product->is_physical               = ($request->has('is_physical') && $request->is_physical == 'on') ? 1 : 0;
        $product->has_inventory             = ($request->has('has_inventory') && $request->has_inventory == 'on') ? 1 : 0;
        $product->sell_when_out_of_stock    = ($request->has('sell_stock_out') && $request->sell_stock_out == 'on') ? 1 : 0;
        $product->requires_shipping         = ($request->has('require_ship') && $request->require_ship == 'on') ? 1 : 0;
        $product->Requires_last_mile        = ($request->has('last_mile') && $request->last_mile == 'on') ? 1 : 0;

        if(empty($product->publish_at)){
            $product->publish_at = ($request->is_live == 1) ? date('Y-m-d H:i:s') : '';
        }
        $product->save();
        if($product->id > 0){

            $varOptArray = $prodVarSet = $updateImage = array();
            $i = 0;

            if($request->has('fileIds')){
                $image = ProductImage::whereIn('id', $request->fileIds)->update(['product_id' => $product->id]);
            }

            $cat = $addonsArray = $upArray = $crossArray = $relateArray = array();

            $delete = ProductAddon::where('product_id', $product->id)->delete();
            $delete = ProductUpSell::where('product_id', $product->id)->delete();
            $delete = ProductCrossSell::where('product_id', $product->id)->delete();
            $delete = ProductRelated::where('product_id', $product->id)->delete();

            if($request->has('addon_sets') && count($request->addon_sets) > 0){
                foreach ($request->addon_sets as $key => $value) {
                    $addonsArray[] = [
                        'product_id' => $product->id,
                        'addon_id' => $value
                    ];
                }
                ProductAddon::insert($addonsArray);
            }

            if($request->has('up_cell') && count($request->up_cell) > 0 && $yes == '1'){
                foreach ($request->up_cell as $key => $value) {
                    $upArray[] = [
                        'product_id' => $product->id,
                        'upsell_product_id' => $value
                    ];
                }
                ProductUpSell::insert($upArray);
            }

            if($request->has('cross_cell') && count($request->cross_cell) > 0 && $yes == '1'){
                foreach ($request->cross_cell as $key => $value) {
                    $crossArray[] = [
                        'product_id' => $product->id,
                        'cross_product_id' => $value
                    ];
                }
                ProductCrossSell::insert($crossArray);
            }

            if($request->has('releted_product') && count($request->releted_product) > 0 && $yes == '1'){
                foreach ($request->releted_product as $key => $value) {
                    $relateArray[] = [
                        'product_id' => $product->id,
                        'related_product_id' => $value
                    ];
                }
                ProductRelated::insert($relateArray);
            }

            $trans = ProductTranslation::where('product_id', $product->id)->where('language_id', $request->language_id)->first();

            if(!$trans){
                $trans = new ProductTranslation();
                $trans->product_id = $product->id; 
                $trans->language_id = $request->language_id; 
            }
            $trans->title               = $request->product_name; 
            $trans->body_html           = $request->body_html; 
            $trans->meta_title          = $request->meta_title; 
            $trans->meta_keyword        = $request->meta_keyword; 
            $trans->meta_description    = $request->meta_description; 
            $trans->save(); 

            if($request->has('exist_variant') && count($request->exist_variant) > 0){
                foreach ($request->exist_variant as $key => $value) {

                    $update = DB::table('product_variants')->where('id', $request->get('area_id'))
                                ->update([
                                     'title'            => $request->exist_variant_titles{$key},
                                     'price'            => $request->exist_variant_price{$key},
                                     'compare_at_price' => $request->exist_variant_compare_price{$key},
                                     'cost_price'       => $request->exist_variant_cost_price{$key},
                                     'quantity'         => $request->exist_variant_quantity{$key},
                                     'currency_id'      => 1,
                                     'tax_category_id'  => $request->tax_category,
                                ]);
                }
            }

            if($request->has('variant_skus') && !empty($request->variant_skus)){

                $varOpts = explode(';', $request->all_variant_set);
                foreach ($varOpts as $varOpt) {
                    $ops = explode('=>', $varOpt);
                    $varOptArray[$ops[0]] = explode(',' ,$ops[1]);
                }

                foreach ($request->variant_skus as $key => $value) {
                    $proVariant = new ProductVariant();
                    $proVariant->sku = $value.'-'.$product->id;
                    $proVariant->product_id = $product->id;
                    $proVariant->title = $request->variant_titles{$key};
                    $proVariant->quantity = $request->variant_quantity{$key};
                    $proVariant->price = $request->variant_price{$key};
                    $proVariant->position = 1;
                    $proVariant->compare_at_price = $request->variant_compare_price{$key};
                    $proVariant->barcode = $this->generateBarcodeNumber();
                    $proVariant->cost_price = $request->variant_cost_price{$key};
                    $proVariant->currency_id = 1;
                    $proVariant->tax_category_id = $request->tax_category;
                    $proVariant->inventory_policy = '';
                    $proVariant->fulfillment_service = '';
                    $proVariant->inventory_management = '';
                    $proVariant->save();

                    $img = ''; $fname = 'variantImage-'.$key;
                    if($request->has($fname) && !empty($request->{$fname})){
                        $image = new ProductImage();
                        $image->media_type = 1;
                        $file = $request->file($fname);
                        $image->product_id = $product->id;
                        $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
                        //$s3filePath = '/assets/Clientlogo/' . $file_name;
                        //$path = Storage::disk('s3')->put($s3filePath, $file,'public');
                        $image->path = $request->file($fname)->storeAs('/product', $file_name, 'public');
                        $image->save();
                        $img = $image->id;

                    }
                    foreach ($request->variant{$key} as $k => $v) {

                        $prodVarSet[$i] = [
                            'product_id' => $product->id,
                            'product_variant_id' => $proVariant->id,
                            'variant_option_id' => $v,
                        ];

                        foreach ($varOptArray as $key => $value) {

                            if(in_array($v, $value)){
                                $prodVarSet[$i]['variant_type_id'] = $key;
                            }
                        }
                        
                        if(!empty($img)){
                            $prodVarSet[$i]['media_id'] = $img;
                        }
                        $i++;
                    }
                }
                ProductVariantSet::insert($prodVarSet);
            }
        }
        return redirect()->back()->with('success', 'Product updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function images(Request $request)
    {
        if ($request->hasFile('file')) {    /* upload logo file */

            $image = new ProductImage();
            $image->media_type = 1;
            $file = $request->file('file');
            $file_name = uniqid() .'.'.  $file->getClientOriginalExtension();
            //$s3filePath = '/assets/Clientlogo/' . $file_name;
            //$path = Storage::disk('s3')->put($s3filePath, $file,'public');
            $image->path = $request->file('file')->storeAs('/product', $file_name, 'public');
            $image->save();
            return response()->json(['imageId'=>$image->id]);
        }else{
            return response()->json(['error'=>'No file']);
        }  
    }

    /**      Make variant rows          */
    public function makeVariantRows(Request $request)
    {
        $multiArray = array();
        $variantNames = array();
        $msgRes = 'Please check variants to create variant set.';
        if(!$request->has('optionIds') || !$request->has('variantIds')){
            return response()->json(array('success' => 'false', 'msg' => $msgRes));
        }
        //dd($request->all());
        
        foreach ($request->optionIds as $key => $value) {

            $name = explode(';', $request->variantIds{$key});

            if(!in_array($name[1], $variantNames)){
                $variantNames[] = $name[1];
            }

            $multiArray[$request->variantIds{$key}][] = $value;
        }


        $combination = $this->array_combinations($multiArray);
        //echo '<pre>'; print_r($combination); echo '</pre>';
        $new_combination = array();
        $edit = 0;

        if($request->has('existing') && !empty($request->existing)){
            $existingComb = $request->existing;
            $edit = 1;
            foreach ($combination as $key => $value) {
                $comb = $arrayVal = '';
                foreach ($value as $k => $v) {
                    $arrayVal = explode(';', $v);
                    $comb .= $arrayVal[0].'*';
                }
                
                $comb = rtrim($comb, '*');

                if(!in_array($comb, $existingComb)){
                    $new_combination[$key] = $value;
                }
            }
            $combination = $new_combination;
            $msgRes = 'No new variant set found.';
        }

        if(count($combination) < 1){
            return response()->json(array('success' => 'false', 'msg' => $msgRes));
        }

        $makeHtml = $this->combinationHtml($combination, $multiArray, $variantNames, $request->sku, $edit);
        return response()->json(array('success' => true, 'html'=>$makeHtml));
    }

    function combinationHtml($combination, $multiArray, $variantNames, $sku = '', $edit = 0)
    {
        $arrVal = '';
        foreach ($multiArray as $key => $value) {
            $varStr = $optStr = '';
            $vv = explode(';', $key);
            
            foreach ($value as $k => $v) {
                $ov = explode(';', $v);
                $optStr .= $ov[0].',';
            }

            $arrVal .= $vv[0] . '=>' . rtrim($optStr, ',') . ';';
            
        }
        $arrVal = rtrim($arrVal, ';');
        $name1 = ''; 

        $html = '<input type="hidden" name="all_variant_set" value="'.$arrVal.'">';
        if($edit == 1){
            $html .= '<h5 >New Variants Set</h5>';
        }
        $html .= '<table class="table table-centered table-nowrap table-striped">
            <thead>
                <th>Image</th>
                <th>Name</th>
                <th>Variants</th>
                <th>Price</th>
                <th>Compare at price</th>
                <th>Cost Price</th>
                <th>Quantity</th>
                <th> </th>
                </thead>';
        $ss = 0;
        foreach ($combination as $key => $value) {
            $names = array(); 
            $ids = array();
            foreach ($value as $k => $v) {
                $variant = explode(';', $v);
                $ids[] = $variant[0];
                $names[] = $variant[1];
            }

            $name1 = $sku.'-'.implode('*', $ids);
            $name2 = $sku.'-'.implode('-', $names);
            $html .= '<tr>';
            $html .= '<td><div class="image-upload">
                      <label class="file-input" for="file-input_'.$ss.'">
                        <img src="'.asset("assets/images/default_image.png").'" width="30" height="30" class="vimg_'.$ss.'"/>
                      </label>

                      <input id="file-input_'.$ss.'" type="file" name="variantImage-'.$ss.'" class="vimage" for="'.$ss.'"/>
                    </div></td>';
            $html .= '<td> <input type="hidden" name="variant_skus[]" value="'.$name1.'">';
            foreach ($ids as $id1) {
                $html .= '<input type="hidden" name="variant['.$ss.'][]" value="'.$id1.'">';
            }
            
            $html .= '<input type="text" name="variant_titles[]" value="'.$name2.'"></td>';
            $html .= '<td>'.implode(", ", $names).'</td>';
            $html .= '<td> <input type="text" style="width: 70px;" name="variant_price[]" value="0" onkeypress="return isNumberKey(event)"> </td>';
            $html .= '<td> <input type="text" style="width: 100px;" name="variant_compare_price[]" value="0" onkeypress="return isNumberKey(event)"> </td>';
            $html .= '<td> <input type="text" style="width: 70px;" name="variant_cost_price[]" value="0" onkeypress="return isNumberKey(event)"> </td>';
            $html .= '<td> <input type="text" style="width: 70px;" name="variant_quantity[]" value="0" onkeypress="return isNumberKey(event)"> </td><td>
            <a href="javascript:void(0);" class="action-icon deleteCurRow"> <h3> <i class="mdi mdi-delete"></i> </h3></a></td>';

            $html .= '</tr>';
            $ss++;
        }
       
        $html .= '</table>';
        return $html;
    }

    private function array_combinations($arrays)
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i ++)
        {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn -1); $j >= 0; $j --)
            {
                if (next($arrays[$j]))
                    break;
                elseif (isset ($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }

    private function generateBarcodeNumber(){
        $random_string = substr(md5(microtime()), 0, 14);
        // $number = mt_rand(1000000000, 9999999999);

        while(ProductVariant::where('barcode', $random_string)->exists()){
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }

    public function deleteVariant(Request $request){
        //$pv = ProductVariant::where('id', $request->prod_var_id)->where('product_id', $request->prod_id)->delete();
        //$pv = ProductVariantSet::where('product_variant_id', $request->prod_var_id)->delete();

        return response()->json(array('success' => true, 'msg' => 'Product variant deleted successfully.'));
    }

}