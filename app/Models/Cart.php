<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    public function cartProducts()
    {
      return $this->hasMany('App\Models\CartProduct');
    }
}
