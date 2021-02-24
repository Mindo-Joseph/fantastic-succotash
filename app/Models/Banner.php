<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['name', 'link', 'image', 'validity_on', 'sorting', 'status', 'start_date_time', 'end_date_time', 'redirect_category_id', 'redirect_vendor_id' ];

    public function getImageAttribute($value)
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
}
