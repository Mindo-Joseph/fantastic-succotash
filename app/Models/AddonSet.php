<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonSet extends Model
{
    public function translation(){
	    return $this->hasMany('App\Models\AddonSetTranslation' , 'addon_id', 'id')->join('languages', 'addon_set_translations.language_id', 'languages.id')->select('title', 'addon_id', 'language_id'); 
	  }

	  public function english(){
	    return $this->hasOne('App\Models\AddonSetTranslation' , 'addon_id', 'id')->select('title', 'addon_id', 'language_id'); 
	  }

	  public function option(){
	    return $this->hasMany('App\Models\AddonOption', 'addon_id', 'id')->select('id', 'title', 'addon_id', 'position', 'price'); 
	  }
}
