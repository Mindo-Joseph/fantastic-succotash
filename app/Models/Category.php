<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['icon', 'image', 'is_visible', 'status', 'position', 'is_core', 'can_add_products', 'parent_id', 'vendor_id', 'client_code', 'display_mode'];

    public function translation(){
       return $this->hasMany('App\Models\Category_translation'); 
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->select('id', 'slug', 'parent_id');
    }
}
