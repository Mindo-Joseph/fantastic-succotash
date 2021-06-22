<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProductRatingFile extends Model
{
    protected $table = 'order_product_rating_files';

    protected $fillable = [
       'order_product_rating_id','file'
    ];
}
