<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    protected $fillable = ['brand_id', 'category_id'];

    public function cate(){
       return $this->belongsTo('App\Models\Category', 'category_id', 'id')->select('id', 'slug', 'type'); 
    }
}
