<?php

namespace App\Http\Controllers\Client;
use DB;
use Image;
use File;
use Artisan;
use App\Models\Product;
use App\Models\Category;
use App\Models\Woocommerce;
use Illuminate\Support\Str;
use App\Models\VendorMedia;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use App\Models\ClientLanguage;
use App\Models\ProductVariant;
use App\Models\CategoryHistory;
use App\Models\ProductCategory;
use App\Models\CsvProductImport;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\Auth;
use App\Models\Category_translation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class ProductImportController extends Controller{
    private $folderName = 'prods';
    public function postWoocommerceDetail(Request $request){
        try {
            $woocommerce_detail = Woocommerce::first();
            $woocommerce = $woocommerce_detail ? $woocommerce_detail : new Woocommerce();
            $woocommerce->consumer_key = $request->consumer_key;
            $woocommerce->consumer_secret = $request->consumer_secret;
            $woocommerce->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Woocommerce Detail Saved Successfully!'
            ]);
        } catch (Exception $e) {
            
        }
    }
    public function getProductImportViaWoocommerce(Request $request){
        try {
            $base_path = base_path();
            DB::beginTransaction();
            $user = Auth::user();
            $response = Http::get('https://yogo.gd/wc-api/v3/products?filter%5Blimit%5D=800&consumer_key=ck_8abd4b1f9ba171e4b21db9a70bef6c711d6ba3f0&consumer_secret=cs_b17a5e26234bae2899e0926c8762247d1f03c684');
            Storage::makeDirectory('app/public/json');
            $response_data = $response->json();
            $products = json_encode($response_data , true);
            $csv_product_import = new CsvProductImport;
            $fileName = md5(time()). '_datafile.json';
            $filePath = Storage::disk('public')->put($fileName, $products);
            $csv_product_import->vendor_id = $request->vendor_id;
            $csv_product_import->name = $fileName;
            $csv_product_import->path = '/storage/json/' . $fileName;
            $csv_product_import->status = 1;
            $csv_product_import->type = 1;
            $csv_product_import->raw_data = $products;
            $csv_product_import->save();
            DB::commit();
            shell_exec("nohup php $base_path/artisan command:productImportData > /dev/null 2>&1 &");
            return response()->json([
                'status' => 'success',
                'message' => 'Import Product Successfully!'
            ]);
        } catch (Exception $e) {
            DB::rollback();
        }
    }
    private function generateBarcodeNumber(){
        $random_string = substr(md5(microtime()), 0, 14);
        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }
}
