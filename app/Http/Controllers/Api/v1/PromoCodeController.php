<?php

namespace App\Http\Controllers\Api\v1;
use Carbon\Carbon;
use App\Models\Promocode;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PromoCodeController extends Controller{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postPromoCodeList(){

        try {
            $now = Carbon::now();
            $promo_codes = Promocode::get();
            dd($promo_codes->toArray());
            return $this->successResponse($promo_codes, '', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function postVerifyPromoCode(Request $request){
        try {
            $validator = $this->validatePromoCode();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $cart_coupon = new CartCoupon();
            $cart_coupon->cart_id = $request->cart_id;
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
            CartCoupon::where('cart_id', $request->cart_id)->where('coupon_id', $request->coupon_id)->delete();
            return $this->successResponse(null, 'Promotion Code Removed Successfully.', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    public function validatePromoCode(){
        return Validator::make(request()->all(), [
            'cart_id' => 'required|exists:carts, id',
            'coupon_id' => 'required|exists:promocodes, id',
        ]);
    }
}
