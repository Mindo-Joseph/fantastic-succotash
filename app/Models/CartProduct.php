<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    use HasFactory;
    public function cart()
    {
        return $this->belongsTo('App\Models\Cart');
    }

    public function addon(){
       return $this->hasMany('App\Models\CartAddon', 'cart_product_id', 'id')->select('cart_product_id', 'addon_id', 'option_id'); 
    }


 	public function product()
    {
    	return $this->belongsTo('App\Models\Product')->select('id', 'sku', 'url_slug', 'is_live', 'weight', 'weight_unit', 'averageRating', 'brand_id', 'tax_category_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->select('id', 'name', 'desc', 'logo', 'banner', 'order_pre_time', 'auto_reject_time', 'order_min_amount');
    }

    public function pvariant()
    {
    	return $this->belongsTo('App\Models\ProductVariant', 'variant_id', 'id')->select('id', 'sku', 'product_id', 'title', 'price', 'tax_category_id', 'barcode');

    	// return $this->belongsTo('App\Models\ProductVariant', 'variant_id', 'id')
    	// 		->join('products as pro', 'pro.id', 'product_variants.product_id')
    	// 		->select('product_variants.id', 'product_variants.sku as variant_sku', 'product_variants.product_id', 'product_variants.title', 'product_variants.price', 'product_variants.barcode', 'pro.sku as product_sku', 'pro.url_slug', 'pro.is_live', 'pro.weight', 'pro.weight_unit', 'pro.averageRating', 'pro.brand_id', 'pro.tax_category_id');
    }

    public function coupon()
    {
      return $this->hasOne('App\Models\CartCoupon', 'cart_id', 'cart_id')->select("cart_id", "coupon_id");
    }

    public function vendorProducts()
    {
      return $this->hasMany(CartProduct::class, 'vendor_id', 'vendor_id')->leftjoin('client_currencies as cc', 'cc.currency_id', 'cart_products.currency_id')
            ->select('cart_products.id', 'cart_products.cart_id', 'cart_products.product_id', 'cart_products.quantity', 'cart_products.variant_id', 'cart_products.is_tax_applied', 'cart_products.tax_category_id', 'cart_products.currency_id', 'cc.doller_compare', 'cart_products.vendor_id')->orderBy('cart_products.created_at', 'asc')->orderBy('cart_products.vendor_id', 'asc');
    }
}