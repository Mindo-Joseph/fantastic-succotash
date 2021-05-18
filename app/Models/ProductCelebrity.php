<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCelebrity extends Model
{
    public function celebrity(){
	    return $this->belongsTo('App\Models\Celebrity', 'celebrity_id', 'id'); 
	}

	public function product(){
	    return $this->belongsTo('App\Models\Product', 'product_id', 'id'); 
	}
}
