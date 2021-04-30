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

    public function pvariant()
    {
    	return $this->belongsTo('App\Models\ProductVariant', 'variant_id', 'id')->select('id', 'sku', 'product_id', 'title', 'price', 'barcode');

    	// return $this->belongsTo('App\Models\ProductVariant', 'variant_id', 'id')
    	// 		->join('products as pro', 'pro.id', 'product_variants.product_id')
    	// 		->select('product_variants.id', 'product_variants.sku as variant_sku', 'product_variants.product_id', 'product_variants.title', 'product_variants.price', 'product_variants.barcode', 'pro.sku as product_sku', 'pro.url_slug', 'pro.is_live', 'pro.weight', 'pro.weight_unit', 'pro.averageRating', 'pro.brand_id', 'pro.tax_category_id');
    }
}