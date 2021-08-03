<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageLabel extends Model
{
    use HasFactory;
    public function translations(){
        $langData = $this->hasMany('App\Models\HomePageLabelTranslation');
        return $langData;
    }
}
