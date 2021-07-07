<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorSubscriptions extends Model
{
    use HasFactory;

    public function features(){
      return $this->hasMany('App\Models\VendorSubscriptionFeatures', 'subscription_id', 'id')->select('id','subscription_id', 'feature_id'); 
    }

    public function validity(){
      return $this->belongsTo('App\Models\SubscriptionValidities', 'validity_id', 'id'); 
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
      $values['original'] = $value;

      return $values;
    }
}
