<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function products(){
	    return $this->hasMany('App\Models\OrderProduct' , 'order_id', 'id'); 
	}
	public function user(){
	    return $this->hasOne('App\Models\User' , 'id', 'user_id'); 
	}
	public function address(){
	    return $this->hasOne('App\Models\UserAddress' , 'id', 'address_id'); 
	}
}
