<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    //
    public function variantImage(){
       return $this->hasMany('App\Models\ProductVariantImage')->select('product_variant_id', 'product_image_id'); 
    }
}
