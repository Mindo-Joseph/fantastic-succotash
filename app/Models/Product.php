<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  
    //protected $fillables = ['sku', 'title', 'url_slug', 'description', 'body_html', 'vendor_id', 'category_id', 'type_id', 'country_origin_id', 'is_new', 'is_featured', 'is_live', 'is_physical', 'weight', 'weight_unit', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'publish_at'];

    public function addOn(){
       return $this->hasMany('App\Models\ProductAddon')->select('product_id', 'addon_id'); 
    }

    public function sets(){
      return $this->hasMany('App\Models\ProductAddon')->join('addon_set_translations as ast', 'ast.addon_id', 'product_addons.addon_id')->select('product_addons.product_id', 'ast.title', 'product_addons.addon_id');
    }

    public function brand(){
       return $this->belongsTo('App\Models\Brand')->select('id', 'title', 'image'); 
    }

    public function vendor(){
       return $this->belongsTo('App\Models\Vendor')->select('id', 'name', 'desc', 'logo'); 
    }

    public function related(){
       return $this->hasMany('App\Models\ProductRelated')->select('product_id', 'related_product_id'); 
    }

    public function upSell(){
       return $this->hasMany('App\Models\ProductUpSell')->select('product_id', 'upsell_product_id'); 
    }

    public function crossSell(){
       return $this->hasMany('App\Models\ProductCrossSell')->select('product_id', 'cross_product_id'); 
    }

    public function variant(){
      return $this->hasMany('App\Models\ProductVariant')->select('id', 'sku', 'product_id', 'title', 'quantity', 'price', 'position', 'compare_at_price', 'barcode', 'cost_price', 'currency_id', 'tax_category_id'); 
    }

    public function translation($langId = 0){
      if($langId > 0){
        return $this->hasMany('App\Models\ProductTranslation')->where('language_id', $langId); 
      }else{
        return $this->hasMany('App\Models\ProductTranslation'); 
      }
    }

  	/*public function english(){
  	    return $this->hasOne('App\Models\ProductTranslation')->select('title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description', 'product_id', 'language_id')->where('language_id', 1); 
  	}*/

    public function primary(){

      $langData = $this->hasOne('App\Models\ProductTranslation')->join('client_languages as cl', 'cl.language_id', 'product_translations.language_id')->select('product_translations.product_id', 'product_translations.title', 'product_translations.language_id', 'product_translations.body_html', 'product_translations.meta_title', 'product_translations.meta_keyword', 'product_translations.meta_description')->where('cl.is_primary', 1);

      return $langData;
 
    }

  	public function category(){
  	    return $this->hasOne('App\Models\ProductCategory')->select('product_id', 'category_id'); 
  	}

  	public function variantSet(){
  	    return $this->hasMany('App\Models\ProductVariantSet')->select('product_id', 'product_variant_id', 'variant_type_id', 'variant_option_id')->groupBy('variant_type_id');
  	}

    public function vatoptions(){
        return $this->hasMany('App\Models\ProductVariantSet')->select('product_id', 'product_variant_id', 'variant_option_id')->groupBy('variant_option_id');
    }

    public function variantSets(){
        return $this->hasMany('App\Models\ProductVariantSet'); 
    }

    public function media(){
        return $this->hasMany('App\Models\ProductImage')->select('product_id', 'media_id', 'is_default');
    }

    public function pimage(){
        return $this->hasMany('App\Models\ProductImage')->select('product_images.product_id', 'product_images.media_id', 'product_images.is_default', 'vendor_media.media_type', 'vendor_media.path')->join('vendor_media', 'vendor_media.id', 'product_images.media_id')->limit(1);
    }

    public function baseprice(){
       return $this->hasMany('App\Models\ProductVariant')->select('id', 'product_id', 'price')->groupBy('product_id'); 
    }

    /* for app */

    public function variants(){
      return $this->hasMany('App\Models\ProductVariant')->select('id', 'sku', 'product_id', 'quantity', 'price', 'barcode'); 
    }

    public function variant_list(){
       return $this->hasMany('App\Models\ProductVariantSet')
       ->join('variants as pv', 'pv.id', 'product_variant_sets.variant_type_id')
       ->select('product_id', 'title', 'type', 'position', 'status')
       ->groupBy('product_variant_sets.variant_type_id')
       ->orderBy('pv.position', 'asc');
    }

    public function variant1(){
      return $this->hasMany('App\Models\ProductVariant', 'product_id', 'pro_id')->select('id', 'sku', 'product_id'); 
    }

    public function inwishlist(){
       return $this->hasOne('App\Models\UserWishlist')->select('product_id'); 
    }


    /*public function allvariants(){
        return $this->hasMany('App\Models\ProductVariantSet')
        ->join('variants', 'variants.id', 'product_variant_sets.variant_type_id')
        ->join('variant_options', 'product_variant_sets.product_id', 'variant_options.id')
        ->select('product_variant_sets.product_id', 'variants.position', 'product_variant_sets.variant_type_id', 'variants.title as variant_title', 'variants.type', 'product_variant_sets.variant_option_id', 'variant_options.title as option_title', 'variant_options.variant_id', 'variant_options.hexacode', 'variant_options.position')
        ->groupBy('product_variant_sets.product_variant_id')
        ->groupBy('product_variant_sets.variant_type_id')
        ->groupBy('product_variant_sets.variant_option_id');
    }*/
}