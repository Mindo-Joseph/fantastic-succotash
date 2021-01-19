<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    protected $fillable = ['title', 'variant_id', 'hexacode', 'position'];

	public function translation(){
       return $this->hasMany('App\Models\VariantOptionTranslation')->join('languages', 'variant_option_translations.language_id', 'languages.id'); 
    }
}
