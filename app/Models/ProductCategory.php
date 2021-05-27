<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    public function cat(){
	    return $this->belongsTo('App\Models\Category', 'category_id', 'id')->select('id', 'slug'); 
	}
	public function categoryDetail(){
	    return $this->belongsTo('App\Models\Category', 'category_id', 'id'); 
	}
}
