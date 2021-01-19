<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantTranslation extends Model
{
    protected $fillables = ['title', 'variant_id', 'language_id'];
}
