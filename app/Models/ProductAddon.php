<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAddon extends Model
{
    public function addOn(){
       return $this->belongsTo('App\Models\AddonSet', 'id', 'addon_id')->select('id', 'title', 'min_select', 'max_select', 'position'); 
    }

}
