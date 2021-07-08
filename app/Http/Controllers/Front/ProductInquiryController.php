<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use Illuminate\Http\Request;
use App\Models\ProductInquiry;
use Illuminate\Support\Facades\Validator;

class ProductInquiryController extends FrontController
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '')
    {
        try {
            $rules = array(
                'name' => 'required',
                'email' => 'required',
                'number' => 'required',
                'message' => 'required',
            );
            $validation  = Validator::make($request->all(), $rules)->validate();
            ProductInquiry::create(['name' => $request->name, 'email' => $request->email, 'phone_number' => $request->number, 'company_name' => $request->company_name, 'message' => $request->message, 'product_id' => $request->product_id, 'vendor_id' => $request->vendor_id, 'product_variant_id' => $request->variant_id]);
            return response()->json(['success', 'Inquiry Submitted Successfully.']);
        } catch (Exception $e) {
            return response()->json(['error', $e->getMessage()]);
        }
    }
}
