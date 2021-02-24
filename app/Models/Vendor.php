<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    public function serviceArea(){
       return $this->hasMany('App\Models\ServiceArea')->select('vendor_id', 'geo_array', 'name'); 
    }

    public function getLogoAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      //$values = \Storage::disk('s3')->url($img);
      $values['original'] = \Storage::disk('s3')->url($img);
      $values['link'] = $img;
      /*$values['small'] = url('showImage/small/' . $img);
      $values['medium'] = url('showImage/medium/' . $img);
      $values['large'] = url('showImage/large/' . $img);*/

      return $values;
    }

    public function getBannerAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values = \Storage::disk('s3')->url($img);
      //$values = $img;
      /*$values['small'] = url('showImage/small/' . $img);
      $values['medium'] = url('showImage/medium/' . $img);
      $values['large'] = url('showImage/large/' . $img);*/

      return $values;
    }
}
