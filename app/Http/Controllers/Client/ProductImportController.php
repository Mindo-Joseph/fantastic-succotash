<?php

namespace App\Http\Controllers\Client;
use DB;
use Image;
use File;
use Artisan;
use App\Models\Product;
use App\Models\Category;
use App\Models\CsvProductImport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ClientLanguage;
use App\Models\ProductVariant;
use App\Models\CategoryHistory;
use App\Models\VendorMedia;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\Auth;
use App\Models\Category_translation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductImportController extends Controller{
    private $folderName = 'prods';
    public function getProductImportViaWoocommerce(Request $request){
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $response = Http::get('https://yogo.gd/wc-api/v3/products?filter%5Blimit%5D=5&consumer_key=ck_8abd4b1f9ba171e4b21db9a70bef6c711d6ba3f0&consumer_secret=cs_b17a5e26234bae2899e0926c8762247d1f03c684');
            Storage::makeDirectory('app/public/json');
            $response_data = $response->json();
            $vendor_id = $request->vendor_id;
            $fileModel = new CsvProductImport;
            $fileName = md5(time()). '_datafile.json';
            $filePath = Storage::disk('public')->put($fileName, json_encode($response_data));
            $fileModel->vendor_id = $request->vendor_id;
            $fileModel->name = $fileName;
            $fileModel->path = '/storage/json/' . $fileName;
            $fileModel->status = 1;
            $fileModel->type = 1;
            $fileModel->raw_data = json_encode($response_data);
            $fileModel->save();
            DB::commit();
            Artisan::call('command:productImportData', ['--products' => json_encode($response_data)]);
            return response()->json([
                'status' => 'success',
                'message' => 'Import Product  Successfully!'
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
