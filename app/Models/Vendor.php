<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;

class Vendor extends Model{
  //use Searchable;
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
    public static function getNameById($vendor_id){
      $result = Vendor::where('id', $vendor_id)->first();
      return $result->name;
    }

    public function orders(){
       return $this->hasMany('App\Models\OrderVendor', 'vendor_id', 'id')->select('id', 'vendor_id'); 
    }

    public function activeOrders(){
       return $this->hasMany('App\Models\OrderVendor', 'vendor_id', 'id')->select('id', 'vendor_id')
              ->where('status', '!=', 3); 
    }

    public function permissionToUser(){
      return $this->hasMany('App\Models\UserVendor');
  }
}
