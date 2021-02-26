<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['icon', 'image', 'is_visible', 'status', 'position', 'is_core', 'can_add_products', 'parent_id', 'vendor_id', 'client_code', 'display_mode', 'type_id'];

    public function translation(){
      return $this->hasMany('App\Models\Category_translation')->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')->join('languages', 'category_translations.language_id', 'languages.id')->select('category_translations.*', 'languages.id as langId', 'languages.name as langName', 'cl.is_primary')->orderBy('cl.is_primary', 'desc'); 
    }

    public function english(){
       return $this->hasOne('App\Models\Category_translation')->select('category_id', 'name')->where('language_id', 1); 
    }

    public function tags()
    {
        return $this->hasMany(CategoryTag::class)->select('category_id', 'tag');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->select('id', 'slug', 'parent_id');
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

      //$values['small'] = url('showImage/small/' . $img);
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

      //$values['small'] = url('showImage/small/' . $img);
      return $values;
    }
}
