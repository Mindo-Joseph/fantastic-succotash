<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUpSell extends Model
{
    public function detail(){
       return $this->belongsTo('App\Models\Product', 'id', 'upsell_product_id');
    }
}
