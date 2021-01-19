<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    //
	public function translation(){
		return $this->hasMany('App\Models\BrandTranslation')->join('languages', 'brand_translations.language_id', 'languages.id'); 
	}

	public function english(){
		return $this->hasMany('App\Models\BrandTranslation')->where('language_id', 1); 
	}

	public function bc(){
		return $this->hasOne('App\Models\BrandCategory'); 
	}
}
