<?php

namespace App\Imports;

use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\{Brand, Category, ClientLanguage, CsvProductImport, Product, ProductCategory, ProductTranslation, ProductVariant, ProductVariantSet, TaxCategory, Variant, VariantOption, VendorCategory, VendorMedia};

class ProductsImport implements ToCollection{
    private $folderName = 'prods';
    
    public function  __construct($vendor_id){
        $this->vendor_id= $vendor_id;
    }
    public function collection(Collection $rows){
        $i = 0;
        $data = array();
        $error = array();
        $variant_exist = 0;
        foreach ($rows as $row) {
            $checker = 0;
            if ($row[0] != "Handle") { //header of excel check              

                if ($row[0] == "") { //if sku or handle is empty
                    $error[] = "Row " . $i . " : handle is empty";
                    $checker = 1;
                }
                if (Product::where('sku', $row[0])->exists()) { //if sku or handle is empty
                    $error[] = "Row " . $i . " : Product with this sku already exist";
                    $checker = 1;
                }
                if ($row[3] == "") { //check if published is empty
                    $error[] = "Row " . $i . " : Please mark published either true or false";
                    $checker = 1;
                }
                if ($row[4] == "") { // check if category is empty
                    $error[] = "Row " . $i . " : Category cannot be empty";
                    $checker = 1;
                }
                if ($row[4] != "") {
                    $category_check = Category::where('slug', $row[4])->first();
                    if (!$category_check) { //check if category doesn't exist
                        $error[] = "Row " . $i . " : Category doesn't exist";
                        $checker = 1;
                    } else {
                        $category_id = $category_check->id;
                        if (!VendorCategory::where([['vendor_id', '=', $this->vendor_id], ['category_id', '=', $category_id]])->exists()) { //check if category is activated for this vendor
                            $error[] = "Row " . $i . " : This category is not activated for this vendor";
                            $checker = 1;
                        }
                    }
                }
                if ($row[5] != "" && $row[6] == "") {
                    $error[] = "Row " . $i . " : There is no value for option 1";
                    $checker = 1;
                }

                if ($row[7] != "" && $row[8] == "") {
                    $error[] = "Row " . $i . " : There is no value for option 2";
                    $checker = 1;
                }

                if ($row[9] != "" && $row[10] == "") {
                    $error[] = "Row " . $i . " : There is no value for option 3";
                    $checker = 1;
                }

                if ($row[5] == "" && $row[6] != "") {
                    $error[] = "Row " . $i . " : There is no name for option 1";
                    $checker = 1;
                }

                if ($row[7] == "" && $row[8] != "") {
                    $error[] = "Row " . $i . " : There is no name for option 2";
                    $checker = 1;
                }

                if ($row[9] == "" && $row[10] != "") {
                    $error[] = "Row " . $i . " : There is no name for option 3";
                    $checker = 1;
                }

                if ($row[5] != "" && $row[6] != "") {
                    $variant_check = Variant::where('title', $row[5])->first();
                    if (!$variant_check) {
                        $error[] = "Row " . $i . " : Option1 Name doesn't exist";
                        $checker = 1;
                    }

                    $variant_option = VariantOption::where('title', $row[6])->first();
                    if (!$variant_option) {
                        $error[] = "Row " . $i . " : Option1 value doesn't exist";
                        $checker = 1;
                    }

                    if ($variant_check && $variant_option) {
                        if (($variant_option->variant_id) != ($variant_check->id)) {
                            $error[] = "Row " . $i . " : Option1 value is not available for this Name";
                            $checker = 1;
                        } else {
                            $variant_exist = 1;
                        }
                    }
                }
                if ($row[7] != "" && $row[8] != "") {
                    $variant_check = Variant::where('title', $row[7])->first();
                    if (!$variant_check) {
                        $error[] = "Row " . $i . " : Option2 Name doesn't exist";
                        $checker = 1;
                    }

                    $variant_option = VariantOption::where('title', $row[8])->first();
                    if (!$variant_option) {
                        $error[] = "Row " . $i . " : Option2 value doesn't exist";
                        $checker = 1;
                    }

                    if ($variant_check && $variant_option) {
                        if (($variant_option->variant_id) != ($variant_check->id)) {
                            $error[] = "Row " . $i . " : Option2 value is not available for this Name";
                            $checker = 1;
                        } else {
                            $variant_exist = 1;
                        }
                    }
                }

                if ($row[9] != "" && $row[10] != "") {
                    $variant_check = Variant::where('title', $row[9])->first();
                    if (!$variant_check) {
                        $error[] = "Row " . $i . " : Option3 Name doesn't exist";
                        $checker = 1;
                    }

                    $variant_option = VariantOption::where('title', $row[10])->first();
                    if (!$variant_option) {
                        $error[] = "Row " . $i . " : Option3 value doesn't exist";
                        $checker = 1;
                    }

                    if ($variant_check && $variant_option) {
                        if (($variant_option->variant_id) != ($variant_check->id)) {
                            $error[] = "Row " . $i . " : Option3 value is not available for this Name";
                            $checker = 1;
                        } else {
                            $variant_exist = 1;
                        }
                    }
                }

                if ($variant_exist == 1) {
                    if ($row[11] == "") {
                        $error[] = "Row " . $i . " : Variant Sku is empty";
                        $checker = 1;
                    } else {
                        $proVariant = ProductVariant::where('sku', $row[11])->first();
                        if ($proVariant) {
                            $error[] = "Row " . $i . " : Variant Sku already exist";
                            $checker = 1;
                        }
                    }
                }

                if($row[23] != ""){
                    $brand = Brand::where('title', "LIKE", $row[23])->first();
                    if(!$brand){
                        $error[] = "Row " . $i . " : Brand doesn't exist";
                        $checker = 1;
                    }
                }

                if($row[24] != ""){
                    $tax_category = TaxCategory::where('title', "LIKE", $row[24])->first();
                    if(!$tax_category){
                        $error[] = "Row " . $i . " : Tax Category doesn't exist";
                        $checker = 1;
                    }
                }

                if ($checker == 0) {
                    $data[] = $row;
                }
            }
            $i++;
        }
        if (!empty($data)) {
            foreach ($data as $da) {

                if (!Product::where('sku', $da[0])->exists()) {

                    if($da[23] != ""){
                        $brand = Brand::where('title', "LIKE", $da[23])->first();
                        if($brand){
                            $brand_id = $brand->id;
                        }
                    }
                    else{
                        $brand_id = null;
                    }

                    if($da[24] != ""){
                        $tax_category = TaxCategory::where('title', "LIKE", $da[24])->first();
                        if($tax_category){
                            $tax_category_id = $tax_category->id;
                        }
                    }
                    else{
                        $tax_category_id = null;
                    }

                    // insert product
                    $product = Product::insertGetId([
                        'sku' => $da[0],
                        'url_slug' => $da[0],
                        'title' => ($da[1] == "") ? "" : $da[1],
                        'body_html' => ($da[2] == "") ? "" : $da[2],
                        'vendor_id' => $this->vendor_id,
                        'type_id' => 1,
                        'is_new' => 1,
                        'is_featured' => 0,
                        'is_live' => ($da[3] == 'TRUE') ? 1 : 0,
                        'is_physical' => 0,
                        'has_inventory' => 0,
                        'sell_when_out_of_stock' => 0,
                        'requires_shipping' => 0,
                        'Requires_last_mile' => 0,
                        'brand_id' => $brand_id,
                        'tax_category_id' => $tax_category_id,
                    ]);

                    //insertion into product category
                    $category_check = Category::where('slug', $da[4])->first();
                    $category_id = $category_check->id;
                    $cat[] = [
                        'product_id' => $product,
                        'Category_id' => $category_id,
                    ];
                    ProductCategory::insert($cat);

                    $client_lang = ClientLanguage::where('is_primary', 1)->first();
                    if (!$client_lang) {
                        $client_lang = ClientLanguage::where('is_active', 1)->first();
                    }

                    //insertion into product translations
                    $datatrans[] = [
                        'title' => ($da[1] == "") ? "" : $da[1],
                        'body_html' => ($da[2] == "") ? "" : $da[2],
                        'meta_title' => '',
                        'meta_keyword' => '',
                        'meta_description' => '',
                        'product_id' => $product,
                        'language_id' => $client_lang->language_id
                    ];

                    ProductTranslation::insert($datatrans);

                    if ($da[5] != "" || $da[7] != "" || $da[9] != "") {

                        $product_hasvariant = Product::where('id', $product)->first();
                        $product_hasvariant->has_variant = 1;
                        $product_hasvariant->save();

                        //inserting product variant
                        $proVariant = ProductVariant::insertGetId([
                            'sku' => $da[11],
                            'title' => $da[11],
                            'product_id' => $product,
                            'quantity' => $da[13],
                            'price' => $da[12],
                            'compare_at_price' => $da[14],
                            'cost_price' => $da[21],
                            'barcode' => $this->generateBarcodeNumber(),
                        ]);

                        if ($da[5] != "") {
                            $variant = Variant::where('title', $da[5])->first();
                            $variant_optionn = VariantOption::where('title', $da[6])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }

                        if ($da[7] != "") {
                            $variant = Variant::where('title', $da[7])->first();
                            $variant_optionn = VariantOption::where('title', $da[8])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }

                        if ($da[9] != "") {
                            $variant = Variant::where('title', $da[9])->first();
                            $variant_optionn = VariantOption::where('title', $da[10])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }
                    }
                }
                else{
                    $product_id = Product::where('sku', $da[0])->first();
                    if ($da[5] != "" || $da[7] != "" || $da[9] != "") {

                        $product_hasvariant = Product::where('id', $product_id->id)->first();
                        $product_hasvariant->has_variant = 1;
                        $product_hasvariant->save();

                        //inserting product variant
                        $proVariant = ProductVariant::insertGetId([
                            'sku' => $da[11],
                            'title' => $da[11],
                            'product_id' => $product_id->id,
                            'quantity' => $da[13],
                            'price' => $da[12],
                            'compare_at_price' => $da[14],
                            'cost_price' => $da[21],
                            'barcode' => $this->generateBarcodeNumber(),
                        ]);

                        if ($da[5] != "") {
                            $variant = Variant::where('title', $da[5])->first();
                            $variant_optionn = VariantOption::where('title', $da[6])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product_id->id;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }

                        if ($da[7] != "") {
                            $variant = Variant::where('title', $da[7])->first();
                            $variant_optionn = VariantOption::where('title', $da[8])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product_id->id;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }

                        if ($da[9] != "") {
                            $variant = Variant::where('title', $da[9])->first();
                            $variant_optionn = VariantOption::where('title', $da[10])->first();
                            //inserting product variant sets
                            $proVariantSet = new ProductVariantSet();
                            $proVariantSet->product_id = $product_id->id;
                            $proVariantSet->product_variant_id = $proVariant;
                            $proVariantSet->variant_type_id = $variant->id;
                            $proVariantSet->variant_option_id = $variant_optionn->id;
                            $proVariantSet->save();
                        }
                    }
                }
            }
        } 

        if (!empty($error)) {
            $vendor_csv = CsvProductImport::where('vendor_id', $this->vendor_id)->first();
            $vendor_csv->status = 3;
            $vendor_csv->error = json_encode($error);
            $vendor_csv->save();
        }
        else{
            $vendor_csv = CsvProductImport::where('vendor_id', $this->vendor_id)->first();
            $vendor_csv->status = 2;
            $vendor_csv->save();
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
