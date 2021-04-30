<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartCoupon extends Model
{
    use HasFactory;

    public function promo()
    {
      return $this->belongsTo('App\Models\Promocode', 'coupon_id', 'id')->select('id', 'name', 'amount', 'allow_free_delivery', 'promo_type_id');
    }
}
