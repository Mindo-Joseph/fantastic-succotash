<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;

class Category_translation extends Model
{
    protected $table='category_translations';
    //use Searchable;

 //    public function toSearchableArray()
	// {
	//   $array = $this->toArray();
	     
	//   return array('id' => $array['id'], 'name' => $array['name'], 'trans-slug' => $array['trans-slug'], 'meta_description' => $array['meta_description'], 'meta_keywords' => $array['meta_keywords']);
	// }
}
