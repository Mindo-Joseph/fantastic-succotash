<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
  protected $fillable = ['title', 'type', 'position', 'status'];

  public function translation(){
    return $this->hasMany('App\Models\VariantTranslation')->join('languages', 'variant_translations.language_id', 'languages.id');
  }

  public function primary(){
    return $this->hasOne('App\Models\VariantTranslation')->join('client_languages as cl', 'cl.language_id', 'variant_translations.language_id')->where('cl.is_primary', 1); 
  }

  public function varcategory(){
    return $this->hasOne('App\Models\VariantCategory'); 
  }

  public function option(){
    return $this->hasMany('App\Models\VariantOption'); 
  }
}
