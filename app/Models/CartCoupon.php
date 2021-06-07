<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartCoupon extends Model
{
    use HasFactory;

    public function promo(){
      return $this->belongsTo('App\Models\Promocode', 'coupon_id', 'id')->select('id', 'name', 'amount', 'promo_type_id', 'expiry_date', 'allow_free_delivery', 'minimum_spend', 'maximum_spend', 'first_order_only', 'limit_total', 'restriction_on');
    }
}
