<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppStyling extends Model
{
    use HasFactory;

    public function styleOption()
    {
        return $this->hasOne('App\Models\AppStylingOption')->where('is_selected', 1);
    }

    public static function getSelectedData()
    {
        $app_styles = AppStyling::select('id','name')->with('styleOption')->get();
        foreach ($app_styles as $app_style) {
            $key_name = str_replace(" ","_",strtolower($app_style->name));;
            if($app_style->name == "Tab Bar Style" || $app_style->name == "Home Page Style"){
                $app_style->$key_name = $app_style->styleOption->template_id;
            }else {
                $app_style->$key_name = $app_style->styleOption->name;
            }
            unset($app_style->id);
            unset($app_style->name);
            unset($app_style->styleOption);
        }
        return $app_styles;
    }
}
