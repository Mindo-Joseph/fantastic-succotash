<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorOrderStatus extends Model
{
    use HasFactory;

    public function OrderStatusOption(){
       return $this->hasOne('App\Models\OrderStatusOption', 'id', 'order_status_option_id'); 
    }
}
