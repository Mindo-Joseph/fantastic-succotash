<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
  use SoftDeletes;
  
    protected $fillable = ['icon', 'image', 'is_visible', 'status', 'position', 'is_core', 'can_add_products', 'parent_id', 'vendor_id', 'client_code', 'display_mode', 'type_id','warning_page_id', 'template_type_id', 'warning_page_design'];
    public $timestamps = true;

    public function translation(){
      return $this->hasMany('App\Models\Category_translation')->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')->join('languages', 'category_translations.language_id', 'languages.id')->select('category_translations.*', 'languages.id as langId', 'languages.name as langName', 'cl.is_primary')->orderBy('cl.is_primary', 'desc')->where('cl.is_active', 1); 
    }

    public function english(){
       return $this->hasOne('App\Models\Category_translation')->select('category_id', 'name')->where('language_id', 1); 
    }

    public function primary(){

      $langData = $this->hasOne('App\Models\Category_translation')->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')->select('category_translations.category_id', 'category_translations.name', 'category_translations.language_id')->where('cl.is_primary', 1);

      if(!$langData){
        $langData = $this->hasOne('App\Models\Category_translation')->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')->select('category_translations.category_id', 'category_translations.name', 'category_translations.language_id')->limit(1);
      }
      return $langData;
    }

    public function tags()
    {
        return $this->hasMany(CategoryTag::class)->select('category_id', 'tag');
    }

    public function brands()
    {
        return $this->hasMany(BrandCategory::class)->join('brands', 'brands.id', 'brand_categories.brand_id')
                ->select('brand_categories.category_id', 'brand_categories.brand_id', 'brands.id', 'brands.image');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->select('id', 'slug', 'parent_id', 'icon');
    }

    public function type(){
      return $this->belongsTo('App\Models\Type')->select('id', 'title'); 
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
      return $values;
    }

    public function getIconAttribute($value)
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
}
