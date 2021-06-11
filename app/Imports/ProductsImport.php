<?php

namespace App\Imports;

use App\Models\{Category, ClientLanguage, CsvProductImport, Product, ProductCategory, ProductTranslation, ProductVariant, ProductVariantSet, Variant, VariantOption, VendorCategory, VendorMedia};
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Intervention\Image\Facades\Image;

class ProductsImport implements ToCollection
{
    private $folderName = 'prods';

    public function collection(Collection $rows)
    {
        $data = array();
        $error = array();
        $i = 0;

        $variant_exist = 0;

        foreach ($rows as $row) {
            $checker = 0;
            $row = $row->toArray();

            if ($row[0] != "Handle") { //header of excel check
                if ($row[0] == "") { //if sku or handle is empty
                    $error[] = "Line " . $i . " : handle is empty";
                    $checker = 1;
                }
                if ($row[3] == "") { //check if published is empty
                    $error[] = "Line " . $i . " : Please mark published either true or false";
                    $checker = 1;
                }
                if ($row[4] == "") { // check if category is empty
                    $error[] = "Line " . $i . " : Category cannot be empty";
                    $checker = 1;
                }
                if ($row[4] != "") {
                    $category_check = Category::where('slug', $row[4])->first();
                    if (!$category_check) { //check if category doesn't exist
                        $error[] = "Line " . $i . " : Category doesn't exist";
                        $checker = 1;
                    } else {
                        $category_id = $category_check->id;
                        if (!VendorCategory::where([['vendor_id', '=', '6'], ['category_id', '=', $category_id]])->exists()) { //check if category is activated for this vendor
                            $error[] = "Line " . $i . " : This category is not activated for this vendor";
                            $checker = 1;
                        }
                    }
                }

                if ($row[5] != "" && $row[6] == "") {
                    $error[] = "Line " . $i . " : There is no value for option 1";
                    $checker = 1;
                }

                if ($row[7] != "" && $row[8] == "") {
                    $error[] = "Line " . $i . " : There is no value for option 2";
                    $checker = 1;
                }

                if ($row[9] != "" && $row[10] == "") {
                    $error[] = "Line " . $i . " : There is no value for option 3";
                    $checker = 1;
                }

                if ($row[5] == "" && $row[6] != "") {
                    $error[] = "Line " . $i . " : There is no name for option 1";
                    $checker = 1;
                }

                if ($row[7] == "" && $row[8] != "") {
                    $error[] = "Line " . $i . " : There is no name for option 2";
                    $checker = 1;
                }

                if ($row[9] == "" && $row[10] != "") {
                    $error[] = "Line " . $i . " : There is no name for option 3";
                    $checker = 1;
                }

                if ($row[5] != "" && $row[6] != "") {
                    $variant_check = Variant::where('title', $row[5])->first();
                    if (!$variant_check) {
                        $error[] = "Line " . $i . " : Option1 Name doesn't exist";
                        $checker = 1;
                    }

                    $variant_option = VariantOption::where('title', $row[6])->first();
                    if (!$variant_option) {
                        $error[] = "Line " . $i . " : Option1 value doesn't exist";
                        $checker = 1;
                    }

                    if ($variant_check && $variant_option) {
                        if (($variant_option->variant_id) != ($variant_check->id)) {
                            $error[] = "Line " . $i . " : Option1 value is not available for this Name";
                            $checker = 1;
                        } else {
                            $variant_exist = 1;
                        }
                    }
                }

                if ($row[7] != "" && $row[8] != "") {
                    $variant_check = Variant::where('title', $row[7])->first();
                    if (!$variant_check) {
                        $error[] = "Line " . $i . " : Option2 Name doesn't exist";
                        $checker = 1;
                    }

                    $variant_option = VariantOption::where('title', $row[8])->first();
                    if (!$variant_option) {
                        $error[] = "Line " . $i . " : Option2 value doesn't exist";
                        $checker = 1;
                    }

                    if ($variant_check && $variant_option) {
                        if (($variant_option->variant_id) != ($variant_check->id)) {
                            $error[] = "Line " . $i . " : Option2 value is not available for this Name";
                            $checker = 1;
                        } else {
                            $variant_exist = 1;
                        }
                    }
                }

                if ($row[9] != "" && $row[10] != "") {
                    $variant_check = Variant::where('title', $row[9])->first();
                    if (!$variant_check) {
                        $error[] = "Line " . $i . " : Option3 Name doesn't exist";
                        $checker = 1;
                    }

                    $variant_option = VariantOption::where('title', $row[10])->first();
                    if (!$variant_option) {
                        $error[] = "Line " . $i . " : Option3 value doesn't exist";
                        $checker = 1;
                    }

                    if ($variant_check && $variant_option) {
                        if (($variant_option->variant_id) != ($variant_check->id)) {
                            $error[] = "Line " . $i . " : Option3 value is not available for this Name";
                            $checker = 1;
                        } else {
                            $variant_exist = 1;
                        }
                    }
                }

                if ($variant_exist == 1) {
                    if ($row[11] == "") {
                        $error[] = "Line " . $i . " : Variant Sku is empty";
                        $checker = 1;
                    } else {
                        $proVariant = ProductVariant::where('sku', $row[11])->first();
                        if ($proVariant) {
                            $error[] = "Line " . $i . " : Variant Sku already exist";
                            $checker = 1;
                        }
                    }
                }

                if ($checker == 0) {
                    $data[] = $row;
                }
            }
            $i++;
        }

        // if (!empty($data)) {
        //     foreach ($data as $da) {
        //         if ($da[17] != "") {
        //             // dd($da[17]);
        //             //$homepage = file_get_contents($da[17]);
        //             $venmedia[] = [
        //                 'vendor_id' => 6,
        //                 'path' => 'true',
        //             ];
        //             VendorMedia::insert($venmedia);
        //         }
        //     }
        // }
        // dd("Done1");

        if (!empty($data)) {
            foreach ($data as $da) {

                if (!Product::where('sku', $da[0])->exists()) {
                    // insert product
                    $product = Product::insertGetId([
                        'sku' => $da[0],
                        'url_slug' => $da[0],
                        'title' => ($da[1] == "") ? "NULL" : $da[1],
                        'body_html' => ($da[2] == "") ? "NULL" : $da[2],
                        'vendor_id' => 6,
                        'type_id' => 1,
                        'is_new' => 1,
                        'is_featured' => 0,
                        'is_live' => ($da[3] == 'TRUE') ? 1 : 0,
                        'is_physical' => 0,
                        'has_inventory' => 0,
                        'sell_when_out_of_stock' => 0,
                        'requires_shipping' => 0,
                        'Requires_last_mile' => 0,
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
                        'title' => ($da[1] == "") ? "NULL" : $da[1],
                        'body_html' => ($da[2] == "") ? "NULL" : $da[2],
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

        // if (!empty($error)) {
        //     dd($error);
        //     $vendor_csv = CsvProductImport::where('vendor_id', '6')->first();
        //     $vendor_csv->status = 2;
        //     $vendor_csv->error = json_encode($error);
        //     $vendor_csv->save();
        // }
    }

    private function generateBarcodeNumber()
    {
        $random_string = substr(md5(microtime()), 0, 14);
        // $number = mt_rand(1000000000, 9999999999);

        while (ProductVariant::where('barcode', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 14);
        }
        return $random_string;
    }
}
