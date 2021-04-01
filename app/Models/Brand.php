<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'title', 'image', 'position', 'status'
    ];
  	public function translation(){
  		return $this->hasMany('App\Models\BrandTranslation')->join('languages', 'brand_translations.language_id', 'languages.id');
  	}

  	public function english(){
  		return $this->hasMany('App\Models\BrandTranslation')->where('language_id', 1); 
  	}

  	public function bc(){
  		return $this->hasOne('App\Models\BrandCategory');
  	}

    public function getImageAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = env('IMG_URL1');
      $values['image_path'] = env('IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = env('FIT_URl');

      //$values['small'] = url('showImage/small/' . $img);
      return $values;
    }
}
