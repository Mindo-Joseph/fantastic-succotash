<?php

namespace App\Http\Controllers\Client;
use DataTables;
use App\Models\Role;
use App\Models\User;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Models\ProductInquiry;
use App\Http\Controllers\Client\BaseController;

class ProductInquiryController extends BaseController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $total_vendor = ProductInquiry::distinct()->count('vendor_id');
        $total_product = ProductInquiry::distinct()->count('product_id');
        return view('backend.inquries.index')->with([
            'total_vendor' => $total_vendor, 
            'total_product' => $total_product
        ]);
    }
    public function show(Request $request){
        $product_inquiries = ProductInquiry::with('product')->get();
        return Datatables::of($product_inquiries)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
            if (!empty($request->get('search'))) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request){
                    if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))){
                        return true;
                    }
                    return false;
                });
            }
        })->make(true);
    }
}
