<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Model{
    use HasFactory;

    protected $table = 'order_vendor_products';
    protected $casts = ['price' => 'double'];
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->select('id', 'name', 'desc', 'logo', 'banner', 'order_pre_time', 'auto_reject_time', 'order_min_amount');
    }
    public function coupon(){
      return $this->hasOne('App\Models\CartCoupon', 'vendor_id', 'vendor_id')->select("cart_id", "coupon_id", 'vendor_id');
    }
    public function addon(){
       return $this->hasMany('App\Models\OrderProductAddon', 'order_product_id', 'id'); 
    }
    public function product(){
      return $this->belongsTo('App\Models\Product')->select('id', 'sku', 'url_slug', 'is_live', 'weight', 'weight_unit', 'averageRating', 'brand_id', 'tax_category_id');
    }
    public function pvariant(){
      return $this->belongsTo('App\Models\ProductVariant', 'variant_id', 'id')->select('id', 'sku', 'product_id', 'title', 'price', 'tax_category_id', 'barcode');
    }
    public function vendorProducts(){
      return $this->hasMany(OrderProduct::class, 'vendor_id', 'vendor_id')->orderBy('order_vendor_products.created_at', 'asc')->orderBy('order_vendor_products.vendor_id', 'asc');
    }
    public function translation($langId = 0){
      return $this->hasOne('App\Models\ProductTranslation','product_id', 'product_id')->where('language_id', $langId); 
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
