<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonOption extends Model
{
    public function translation(){
       return $this->hasMany('App\Models\AddonOptionTranslation', 'addon_opt_id', 'id')->join('languages', 'addon_option_translations.language_id', 'languages.id'); 
    }
}
