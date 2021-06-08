<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Model{
    use HasFactory;

    protected $table = 'order_vendor_products';
    protected $casts = ['price' => 'double'];

    public function addon(){
       return $this->hasMany('App\Models\OrderProductAddon', 'order_product_id', 'id'); 
    }
    public function pvariant(){
      return $this->belongsTo('App\Models\ProductVariant', 'variant_id', 'id')->select('id', 'sku', 'product_id', 'title', 'price', 'tax_category_id', 'barcode');
    }
    public function getImageAttribute($value){
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
