<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRelated extends Model
{
    public function detail(){
       return $this->belongsTo('App\Models\Product', 'id', 'related_product_id');
    }
}
