<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    
    public function set(){
	    return $this->hasMany('App\Models\ProductVariantSet')->select('product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'product_variant_sets.variant_option_id', 'variant_options.title')
	    		->join('variant_options', 'variant_options.id', 'product_variant_sets.variant_option_id'); 
	}
}
