<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
  protected $fillable = ['title', 'type', 'position', 'status'];

  public function translation(){
    return $this->hasMany('App\Models\VariantTranslation')->join('languages', 'variant_translations.language_id', 'languages.id'); 
  }

  public function english(){
    return $this->hasOne('App\Models\VariantTranslation')->where('language_id', 1); 
  }

  public function varcategory(){
    return $this->hasOne('App\Models\VariantCategory'); 
  }

  public function option(){
    return $this->hasMany('App\Models\VariantOption'); 
  }
}
