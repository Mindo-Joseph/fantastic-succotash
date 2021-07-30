<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
	protected $fillable = ['sku','product_id','title','quantity','price','position','compare_at_price','cost_price','barcode','currency_id','tax_category_id','inventory_policy','fulfillment_service','inventory_management','status'];

    public function set(){
	    return $this->hasMany('App\Models\ProductVariantSet')
	    		->join('variant_options as opt', 'opt.id', 'product_variant_sets.variant_option_id')
	    		->join('variants as vari', 'vari.id', 'opt.variant_id')
	    		->select('product_variant_sets.product_variant_id', 'product_variant_sets.variant_option_id', 'opt.title', 'opt.hexacode', 'vari.type', 'vari.id'); 
	}

	public function vimageall(){
	    return $this->hasOne('App\Models\ProductVariantImage', 'product_variant_id', 'id')
	    		->select('product_variant_id', 'product_image_id')->groupBy('product_variant_id');
	}

	public function vimage(){
		return $this->hasOne('App\Models\ProductVariantImage', 'product_variant_id', 'id')
	    		->select('product_variant_id', 'product_image_id')->groupBy('product_variant_id');
	}

	public function media(){
		return $this->hasMany('App\Models\ProductVariantImage', 'product_variant_id', 'id')->select('product_variant_id', 'product_image_id');
	}

	public function vset(){
	    return $this->hasMany('App\Models\ProductVariantSet')->select('product_variant_id','variant_option_id','product_id','variant_type_id'); 
	}

	public function translation($langId = 0){
        return $this->hasMany('App\Models\ProductTranslation', 'product_id', 'product_id');
    }
	public function translation_one($langId = 0){
        return $this->hasOne('App\Models\ProductTranslation', 'product_id', 'product_id'); 
    }
    public function optionData() {
	    return $this->belongsTo('App\Models\VariantOption', 'variant_option_id', 'id');
	}
    public function tax()
    {
        return $this->belongsTo('App\Models\TaxCategory', 'tax_category_id', 'id')->select('id', 'title', 'code');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id')->select('id', 'sku');
    }
    public function wishlist(){
       return $this->hasOne('App\Models\UserWishlist', 'product_id', 'product_id')->select('product_id', 'user_id'); 
    }
}