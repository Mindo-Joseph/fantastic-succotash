<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class VendorController extends FrontController
{
    private $field_status = 2;
    
    /**
     * Display product By Vendor
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorProducts(Request $request, $domain = '', $vid = 0)
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();

        $vendor = Vendor::select('id', 'name', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery')
                ->where('id', $vid)->firstOrFail();

        /*'media' => function($q){
            $q->groupBy('product_id');
        },*/

        $listData = Product::with(['media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating')
                    ->where('is_live', 1)->where('vendor_id', $vid)->paginate(8);

        if(!empty($listData)){
            foreach ($listData as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                }
            }
        }

        $navCategories = Session::get('navCategories');

        if(empty($navCategories)){
            $navCategories = $this->categoryNav($langId);
        }
        $vendorIds[] = $vid;
        //dd($listData->toArray());

        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;

        return view('forntend/vendor-products')->with(['vendor' => $vendor, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts]);
    }

}