<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCrossSell extends Model
{
    public function detail(){
       return $this->belongsTo('App\Models\Product', 'id', 'cross_product_id');
    }
}
