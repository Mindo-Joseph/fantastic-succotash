<?php

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, ProductTranslation};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class SearchController extends FrontController
{
    public function search(Request $request)
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);

        $inp = $request->input('query');
        $langId = Session::get('customerLanguage');

        $prodTrans = ProductTranslation::select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')
            ->where('language_id', $langId)
            ->where(function ($q) use ($inp) {
                $q->where('title', 'LIKE', '%' .  $inp . '%')
                    ->orWhere('body_html', 'LIKE', '%' . $inp . '%')
                    ->orWhere('meta_title', 'LIKE', '%' . $inp . '%')
                    ->orWhere('meta_keyword', 'LIKE', '%' . $inp . '%')
                    ->orWhere('meta_description', 'LIKE', '%' . $inp . '%');
            })->get();
        $productIds = array();

        if ($prodTrans) {
            foreach ($prodTrans as $key => $val) {
                $productIds[] = $val->product_id;
            }
        }

        $products = Product::select("id", "url_slug")
            ->with(['media.image', 'variant.vimage.pimage.image', 'translation' => function ($query) use ($langId) {
                $query->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $query->where('language_id', $langId);
            }])
            ->where(function ($q) use ($inp, $productIds) {
                $q->whereIn('id', $productIds)
                    ->orWhere('url_slug', 'LIKE', '%' . $inp . '%');
            })->get();

            // dd($products);
        // $products  = Product::where('url_slug', 'LIKE', '%' . $request->input('query') . '%')->where('is_live', 1)->with('translation')->with('variant.vimage.pimage.image')
        //     ->get()->toArray();

        $categories  = Category::where('slug', 'LIKE', '%' . $request->input('query') . '%')->where('status', 1)->get()->toArray();
        $vendors  = Vendor::where('name', 'LIKE', '%' . $request->input('query') . '%')->get()->toArray();

        return view('backend.searchbar.search')->with(['products' => $products,  'categories' => $categories, 'vendors' => $vendors, 'navCategories' => $navCategories]);
    }
}
