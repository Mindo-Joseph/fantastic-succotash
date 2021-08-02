<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDineinTable extends Model
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
      $values['image_fit'] = env('FIT_URl');
      $values['image_path'] = env('IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['original'] = \Storage::disk('s3')->url($img);
      $values['logo_db_value'] = $value;
      
      return $values;
    }

    public function translations(){
      $langData = $this->hasMany('App\Models\VendorDineinTableTranslation');
      return $langData;
    }
    public function category(){
      $langData = $this->hasOne('App\Models\VendorDineinCategory', 'id', 'vendor_dinein_category_id');
      return $langData;
    }
}
