<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;

    protected $table = 'promocodes';

    public function promocoderestriction()
    {
        return $this->hasOne(PromocodeRestriction::class);
    }
}
