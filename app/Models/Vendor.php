<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    public function serviceArea(){
       return $this->hasMany('App\Models\ServiceArea')->select('vendor_id', 'geo_array', 'name'); 
    }

    public function products(){
       return $this->hasMany('App\Models\Product', 'vendor_id', 'id'); 
    }

    public function getLogoAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = env('IMG_URL1');
      $values['image_path'] = env('IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = env('FIT_URl');
      return $values;
    }

    public function getBannerAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = env('IMG_URL1');
      $values['image_path'] = env('IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = env('FIT_URl');
      return $values;
    }
}
