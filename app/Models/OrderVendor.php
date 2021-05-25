<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderVendor extends Model{
    use HasFactory;
    public function vendor(){
	    return $this->hasOne('App\Models\Vendor' , 'id', 'vendor_id'); 
	}
    public function products(){
	    return $this->hasMany('App\Models\OrderProduct' , 'vendor_id', 'id'); 
	}
	public function coupon(){
	    return $this->hasOne('App\Models\Promocode' , 'id', 'coupon_id'); 
	}
}
