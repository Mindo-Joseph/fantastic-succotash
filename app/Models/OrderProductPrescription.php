<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductPrescription extends Model
{
    use HasFactory;

    public function getPrescriptionAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img).'@webp';
      $values['image_fit'] = \Config::get('app.FIT_URl');

      return $values;
    }
}
