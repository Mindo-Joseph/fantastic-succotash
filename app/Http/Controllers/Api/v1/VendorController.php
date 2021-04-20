<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Brand};
use Validation;
use DB;

class VendorController extends BaseController
{
    private $field_status = 2;
    /**
     * Get Company ShortCode
     *
     */
    public function productsByVendor(Request $request, $vid = 0)
    {
        if($vid == 0){
            return response()->json(['error' => 'No record found.'], 404);
        }
        $userid = Auth::user()->id;
        $paginate = $request->has('limit') ? $request->limit : 12;
        $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
        $langId = Auth::user()->language;
        $vendor = Vendor::select('id', 'name', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 
                    'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery')
                    ->where('id', $vid)->first();
        if(!$vendor){
            return response()->json(['error' => 'No record found.'], 200);
        }

        $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
                            $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                            $zx->select('variant_options.*', 'vt.title');
                            $zx->where('vt.language_id', $langId);
                        }
                    ])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                    ->join('variant_translations as vt','vt.variant_id','vr.id')
                    ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                    ->where('vt.language_id', $langId)
                    ->whereIn('product_variant_sets.product_id', function($qry) use($cid){ 
                        $qry->select('product_id')->from('product_categories')
                            ->where('category_id', $cid);
                        })
                    ->groupBy('product_variant_sets.variant_type_id')->get();

        $products = Product::with(['inwishlist' => function($qry) use($userid){
                        $qry->where('user_id', $userid);
                    },
                    'media.image', 'translation' => function($q) use($langId){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    },
                    'variant' => function($q) use($langId){
                        $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                        $q->groupBy('product_id');
                    },
                ])
                ->select('id', 'sku', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating')
                ->where('vendor_id', $vid)
                ->where('is_live', 1)->paginate($paginate);
        
        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                }
            }
        }
        $response['vendor'] = $vendor;
        $response['products'] = $products;
        $response['filterData'] = $variantSets;

        return response()->json([
            'data' => $response,
        ]);
    }

}
