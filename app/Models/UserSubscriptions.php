<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscriptions extends Model
{
    use HasFactory;

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
      $values['original'] = $value;

      return $values;
    }
}