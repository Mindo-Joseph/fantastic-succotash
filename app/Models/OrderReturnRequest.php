<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturnRequest extends Model
{
    protected $table = 'order_return_requests';

    protected $fillable = [
      'order_vendor_product_id', 'order_id', 'return_by', 'reason', 'coments', 'status'
    ];


    public function returnFiles(){
        return $this->hasMany(OrderReturnRequestFile::class, 'order_vendor_product_id', 'id');
      }
}
