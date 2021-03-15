<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class UserhomeController extends FrontController
{
    private $field_status = 2;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $home = array();
        $client_config = Session::get('client_config');
        $value = Session::get('preferences');
        //$clientLanguage = ClientLanguage::where('is_primary', 1)->first();

        $vends = array();

        $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount');

        if(session('preferences.is_hyperlocal') == 1){
            /*$vendorData = $vendorData->whereIn('id', function($query) use($lats, $longs){
                    $query->select('vendor_id')
                    ->from(with(new ServiceArea)->getTable())
                    ->whereRaw("ST_Contains(polygon, GeomFromText('POINT(".$lats." ".$longs.")'))");
            });*/
        }
        $vendorData = $vendorData->where('status', '!=', $this->field_status)->get();

        foreach ($vendorData as $key => $value) {
            $vends[] = $value->id;
        }

        $banners = Banner::where('status', 1)
                        ->where(function($q){
                              $q->whereNull('start_date_time')
                                ->orWhere(function($q2){
                                      $q2->where('start_date_time', '<=', Carbon::now())
                                        ->where('end_date_time', '>=', Carbon::now());
                                });
                        })
                        ->orderBy('sorting', 'asc')->get();

        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id', 'type')
                        ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name')
                        ->where('categories.id', '>', '1')
                        ->where('categories.status', '!=', $this->field_status)
                        ->where('cts.language_id', Session::get('lang_id'))
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();
        if($categories){
            $categories = $this->buildTree($categories->toArray());
        }

        $brands = Brand::select('id', 'title', 'image')
                        ->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->get();

        $featured = $this->productList($vends, Session::get('lang_id'), 'USD', 'is_featured');
        $newProdu = $this->productList($vends, Session::get('lang_id'), 'USD', 'is_new');
        
        $onSale = $this->productList($vends, Session::get('lang_id'), 'USD');
        //dd($vendorData->toArray());
        $featuredPro = array_chunk($featured->toArray(), ceil(count($featured) / 2));
        $newProducts = array_chunk($newProdu->toArray(), ceil(count($newProdu) / 2));
        $onSaleProds = array_chunk($onSale->toArray(), ceil(count($onSale) / 2));

        return view('forntend/home')->with(['home' => $home, 'banners' => $banners, 'categories' => $categories, 'brands' => $brands, 'vendors' => $vendorData, 'featuredProducts' => $featuredPro, 'newProducts' => $newProducts, 'onSaleProducts' => $onSaleProds]);
    }

    public function productList($venderIds, $langID, $currency = 'USD', $where = '')
    {
        $products = Product::with('pimage', 'baseprice', 'primary')
                        //->leftjoin('product_translations as trans', 'trans.product_id', 'products.id')
                        ->select('id', 'sku');
                       // ->where('trans.language_id', $langID);
        if($where !== ''){
            $products = $products->where('products.'.$where, 1);
        }
        if(is_array($venderIds) && count($venderIds) > 0){
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        $products = $products->get();

        $folderPath = env('IMG_URL1').'300/300'.env('IMG_URL2');

        if(!empty($products)){

            foreach ($products as $key => $value) {
                if(!empty($value->primary)){
                    $products{$key}->product_name = $value->primary->title;
                }else{
                    $products{$key}->product_name = $value->sku;
                }

                if(!empty($value->pimage) && count($value->pimage) > 0){
                    $imgs = array();
                    foreach ($value->pimage as $k => $v) {
                        $products{$key}->image = $folderPath.'/'.\Storage::disk('s3')->url($v->path);
                    }
                }else{
                    $products{$key}->image = $folderPath.'/'.\Storage::disk('s3')->url('default/default_image.png');
                }

                unset($products{$key}->pimage);

                $prodPrice = '0.00';

                if(!empty($value->baseprice) && count($value->baseprice) > 0){

                    //echo '<pre>';print_r($value->baseprice->toArray());die;
                    $prodPrice = $value->baseprice[0]->price;

                    if(!empty($value->baseprice[0]->price) && $value->baseprice[0]->price > 0 && $currency != 'USD'){

                        //$prodPrice = $this->changeCurrency($currency, $value->baseprice[0]->price);
                        //$products{$key}->price = $amount;
                        $prodPrice = $value->baseprice[0]->price;

                    }
                }
                $products{$key}->price = $prodPrice;
                unset($products{$key}->baseprice);

            }
        }
        return $products;
    }

}
