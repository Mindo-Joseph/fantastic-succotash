<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ProductTranslation extends Model
{
    use Searchable;

    public function toSearchableArray()
	{
	  $array = $this->toArray();
	     
	  return array('id' => $array['id'], 'product_id' => $array['product_id'], 'title' => $array['title'], 'body_html' => $array['body_html'], 'meta_description' => $array['meta_description'], 'meta_keywords' => $array['meta_keywords']);
	}
}