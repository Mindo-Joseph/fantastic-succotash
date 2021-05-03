<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
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
	    /*return $this->hasOne('App\Models\ProductVariantImage', 'product_variant_id', 'id')
	    		->join('vendor_media as vm', 'vm.id', 'product_variant_images.product_image_id')
	    		->select('vm.media_type', 'vm.path', 'product_variant_images.product_variant_id')->groupBy('product_variant_images.product_variant_id'); */
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

}
