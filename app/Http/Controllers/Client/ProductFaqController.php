<?php

namespace App\Http\Controllers\Client;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\ProductFaq;
use App\Http\Controllers\Client\BaseController;
use App\Models\ProductFaqTranslation;

class ProductFaqController extends BaseController{
    use ApiResponser;
    public function store(Request $request){
        try {
            $this->validate($request, [
              'name.0' => 'required|string|max:255',
            ],['name.0' => 'The default language question field is required.']);
            DB::beginTransaction();
            $product_faq = new ProductFaq();
            $product_faq->is_required = $request->is_required;
            $product_faq->product_id = $request->product_id;
            $product_faq->save();
            $language_id = $request->language_id;
            
            foreach ($request->name as $k => $name) {
                if($name){
                    $ProductFaqTranslation = new ProductFaqTranslation();
                    $ProductFaqTranslation->name = $name;
                    $ProductFaqTranslation->slug = Str::slug($name, '-');
                    $ProductFaqTranslation->language_id = $language_id[$k];
                    $ProductFaqTranslation->product_faq_id = $product_faq->id;
                    $ProductFaqTranslation->save();
                }
            }
            DB::commit();
            return $this->successResponse($product_faq, 'Product Order Form Added Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request){
        try {
            $product_faq = ProductFaq::with(['translations'])->where(['id' => $request->product_faq_id])->firstOrFail();
            return $this->successResponse($product_faq, '');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductFaq $ProductFaq){
         try {
            $this->validate($request, [
              'name.0' => 'required|string|max:255',
            ],['name.0' => 'The default language question field is required.']);
            DB::beginTransaction();
            $product_faq_id = $request->product_faq_id;
            $product_faq = ProductFaq::where('id', $product_faq_id)->first();
            $product_faq->is_required = $request->is_required;
            $product_faq->product_id = $request->product_id;
            $product_faq->save();
            $language_id = $request->language_id;
            ProductFaqTranslation::where('product_faq_id', $product_faq_id)->delete();
            foreach ($request->name as $k => $name) {
                if($name){
                    $ProductFaqTranslation = new ProductFaqTranslation();
                    $ProductFaqTranslation->name = $name;
                    $ProductFaqTranslation->slug = Str::slug($name, '-');
                    $ProductFaqTranslation->language_id = $language_id[$k];
                    $ProductFaqTranslation->product_faq_id = $product_faq->id;
                    $ProductFaqTranslation->save();
                }
            }
            DB::commit();
            return $this->successResponse($product_faq, 'Product Order Form Updated Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        try {
            ProductFaq::where('id', $request->product_faq_id)->delete();
            ProductFaqTranslation::where('product_faq_id', $request->product_faq_id)->delete();
            return $this->successResponse([], 'Product Order Form Deleted Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
}
