<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
	protected $fillable = ['category_id','product_id'];

	public function product(){
	    return $this->hasOne('App\Models\Product', 'id', 'product_id'); 
	}
    public function cat(){
	    return $this->belongsTo('App\Models\Category', 'category_id', 'id')->select('id', 'slug'); 
	}
	public function categoryDetail(){
	    return $this->belongsTo('App\Models\Category', 'category_id', 'id'); 
	}
}
