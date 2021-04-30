<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    public function cartProducts()
    {
      return $this->hasMany('App\Models\CartProduct')
            ->join('client_currencies as cc', 'cc.currency_id', 'cart_products.currency_id')
            ->select('cart_products.id', 'cart_products.cart_id', 'cart_products.product_id', 'cart_products.quantity', 'cart_products.variant_id', 'cart_products.is_tax_applied', 'cart_products.tax_rate_id', 'cart_products.currency_id', 'cc.doller_compare');
    }

    public function coupon()
    {
      return $this->hasMany('App\Models\CartCoupon')->select("cart_id", "coupon_id");
    }

    public function product()
    {
      return $this->belongsTo('App\Models\Product');
    }

    public function variant()
    {
      return $this->belongsTo('App\Models\ProductVariant');
    }
}