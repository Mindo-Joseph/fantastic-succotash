<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model{

    use HasFactory;
    protected $casts = ['total_amount' => 'float'];

    public function products(){
	    return $this->hasMany('App\Models\OrderProduct' , 'order_id', 'id'); 
	}
	public function vendors(){
	    return $this->hasMany('App\Models\OrderVendor' , 'order_id', 'id'); 
	}
	public function user(){
	    return $this->hasOne('App\Models\User' , 'id', 'user_id'); 
	}
	public function address(){
	    return $this->hasOne('App\Models\UserAddress' , 'id', 'address_id'); 
	}
	public function paymentOption(){
	    return $this->hasOne('App\Models\PaymentOption' , 'id', 'payment_option_id'); 
	}
	public function orderStatusVendor(){
        return $this->hasMany('App\Models\VendorOrderStatus', 'order_id', 'id');
    }
	
	public function payment(){
	    return $this->hasOne('App\Models\Payment' , 'order_id', 'id'); 
	}
}
