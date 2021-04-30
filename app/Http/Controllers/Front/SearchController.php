<?php

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, Category_translation, ProductTranslation};
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
        if (empty($inp)) { 
            return view('backend.searchbar.search')->with(['products' => [],  'categories' => [], 'vendors' => [], 'navCategories' => $navCategories, 'search' => $inp]);
        }
        // $langId = Session::get('customerLanguage');
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


        $categoryTrans = Category_translation::select('category_id',   'meta_title', 'meta_keywords', 'meta_description')
            ->where('language_id', $langId)
            ->where(function ($q) use ($inp) {
                $q->where('meta_title', 'LIKE', '%' .  $inp . '%')
                    ->orWhere('meta_keywords', 'LIKE', '%' . $inp . '%')
                    ->orWhere('meta_description', 'LIKE', '%' . $inp . '%');
            })->get();
        $productIds = array();

        if ($categoryTrans) {
            foreach ($categoryTrans as $key => $val) {
                $categoryIds[] = $val->category_id;
            }
        }

        $categories = Category::join('category_translations as ct', 'ct.category_id', 'categories.id')
            ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.type_id', 'categories.image', 'ct.name', 'ct.trans-slug', 'ct.meta_title', 'ct.meta_description', 'ct.meta_keywords', 'ct.category_id')
            ->where('ct.language_id', $langId)
            ->where(function ($q) use ($inp) {
                $q->where('ct.name', ' LIKE', '%' . $inp . '%')
                    ->orWhere('ct.trans-slug', 'LIKE', '%' . $inp . '%')
                    ->orWhere('ct.meta_title', 'LIKE', '%' . $inp . '%')
                    ->orWhere('ct.meta_description', 'LIKE', '%' . $inp . '%')
                    ->orWhere('ct.meta_keywords', 'LIKE', '%' . $inp . '%');
            })->get();



        // dd($categories->toArray());

        // $categories  = Category::where('slug', 'LIKE', '%' . $request->input('query') . '%')->where('status', 1)->get()->toArray();
        $vendors  = Vendor::where('name', 'LIKE', '%' . $request->input('query') . '%')->get();

        return view('backend.searchbar.search')->with(['products' => $products,  'categories' => $categories, 'vendors' => $vendors, 'navCategories' => $navCategories, 'search' => $inp]);
    }
}
