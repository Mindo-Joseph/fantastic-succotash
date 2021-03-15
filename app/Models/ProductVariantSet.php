<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductVariantSet extends Model
{
    /*function options(){
        return $this->hasMany('App\Models\ProductVariantSet', 'variant_option_id', 'variant_type_id')->merge($this->relatedUserRelations);
    }

    public function variantOption() {
	    return $this->hasMany('App\ProductVariantSet', 'variant_option_id', 'variant_type_id');
	}

	public function allUserRelations() {
	    return $this->userRelations->merge($this->relatedUserRelations);
	}*/

	public function options() {
	    return $this->hasMany('App\Models\VariantOption', 'variant_id', 'variant_type_id')
	    		->join('product_variant_sets as pvs', 'pvs.variant_option_id', 'variant_options.id')
	    		->groupBy('pvs.variant_option_id');
	}
}
