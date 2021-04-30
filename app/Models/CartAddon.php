<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartAddon extends Model
{
    use HasFactory;

    public function option(){
       return $this->belongsTo('App\Models\AddonOption', 'option_id', 'id')
       			->join('addon_option_translations as aot', 'addon_options.id', 'aot.addon_opt_id')
				->select('addon_options.id', 'addon_options.addon_id', 'addon_options.price', 'aot.title', 'aot.language_id')
				->orderBy('addon_options.position', 'asc'); 
    }
}

