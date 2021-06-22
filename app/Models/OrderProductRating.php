<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProductRating extends Model
{
    protected $table = 'order_product_ratings';

    protected $fillable = [
       'order_vendor_product_id','order_id','product_id','user_id','rating','review','file',
    ];


    public function reviewFiles(){
        return $this->hasMany(OrderProductRatingFile::class, 'order_product_rating_id', 'id');
      }
}
