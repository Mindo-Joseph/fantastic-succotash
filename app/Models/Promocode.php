<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;

    protected $table = 'promocodes';

  protected $fillable = ['name', 'amount', 'expiry_date', 'promo_type_id', 'allow_free_delivery', 'minimum_spend', 'maximum_spend', 'first_order_only', 'limit_per_user', 'limit_total', 'paid_by_vendor_admin'];

    public function promocoderestriction()
    {
        return $this->hasOne(PromocodeRestriction::class);
    }


    public function PromoTypes()
    {
        return $this->belongsTo(PromoTypes::class);
    }
}
