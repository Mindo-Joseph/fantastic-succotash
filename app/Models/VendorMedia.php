<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorMedia extends Model
{
	protected $table = 'vendor_media';
	protected $appends = ['image_path'];

    public function getImagePathAttribute(){

      $values = array();
  		$img = 'default/default_image.png';
  		$value = $this->path;
  		if(!empty($value)){
  			$img = $value;
  		}
  		$values = \Storage::disk('s3')->url($img);
        /*$values['small'] = url('showImage/small/' . $img);
        $values['medium'] = url('showImage/medium/' . $img);
        $values['large'] = url('showImage/large/' . $img);*/

  		return $values;

    }

    public function getPathAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values = \Storage::disk('s3')->url($img);
      /*$values['small'] = url('showImage/small/' . $img);
      $values['medium'] = url('showImage/medium/' . $img);
      $values['large'] = url('showImage/large/' . $img);*/

      return $values;
    }
}
