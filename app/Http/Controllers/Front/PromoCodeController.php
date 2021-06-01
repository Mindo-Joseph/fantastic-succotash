<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromoCodeController extends Controller{

    public function postVerifyPromoCode(Request $request){
        try {
            $validator = $this->validatePromoCode();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if(!$vendor){
                return response()->json(['error' => 'Invalid vendor id.'], 404);
            }
            $cart_detail = Cart::where('id', $request->cart_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Cart Id', 422);
            }
            $cart_detail = Promocode::where('id', $request->coupon_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Promocode Id', 422);
            }
            $cart_coupon_detail = CartCoupon::where('cart_id', $request->cart_id)->where('vendor_id', $request->vendor_id)->where('coupon_id', $request->coupon_id)->first();
            if($cart_coupon_detail){
                return $this->errorResponse('Coupon Code already applied.', 422);
            }
            $cart_coupon_detail2 = CartCoupon::where('cart_id', $request->cart_id)->where('coupon_id', $request->coupon_id)->first();
            if($cart_coupon_detail2){
                return $this->errorResponse('Coupon Code already applied other vendor.', 422);
            }
            $cart_coupon = new CartCoupon();
            $cart_coupon->cart_id = $request->cart_id;
            $cart_coupon->vendor_id = $request->vendor_id;
            $cart_coupon->coupon_id = $request->coupon_id;
            $cart_coupon->save();
            return $this->successResponse($cart_coupon, 'Promotion Code Used Successfully.', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    
    public function postRemovePromoCode(Request $request){
        try {
            $validator = $this->validatePromoCode();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $cart_detail = Cart::where('id', $request->cart_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Cart Id', 422);
            }
            $cart_detail = Promocode::where('id', $request->coupon_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Promocode Id', 422);
            }
            CartCoupon::where('cart_id', $request->cart_id)->where('coupon_id', $request->coupon_id)->delete();
            return $this->successResponse(null, 'Promotion Code Removed Successfully.', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function validatePromoCodeList(){
        return Validator::make(request()->all(), [
            'vendor_id' => 'required',
        ]);
    }
    
    public function validatePromoCode(){
        return Validator::make(request()->all(), [
            'cart_id' => 'required',
            'vendor_id' => 'required',
            'coupon_id' => 'required',
        ]);
    }
}
