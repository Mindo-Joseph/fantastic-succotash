<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    
    public function set(){
	    return $this->hasMany('App\Models\ProductVariantSet')
	    		->select('product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'product_variant_sets.variant_option_id', 'product_variant_sets.media_id', 'product_images.id', 'product_images.path', 'variant_options.title')
	    		->join('product_images', 'product_images.id', 'product_variant_sets.media_id')
	    		->join('variant_options', 'variant_options.id', 'product_variant_sets.variant_option_id'); 
	}
}
