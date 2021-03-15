<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantImage extends Model
{
    public function pimage(){
	    return $this->belongsTo('App\Models\ProductImage','product_image_id','id')->select('id', 'media_id'); 
	}
}
