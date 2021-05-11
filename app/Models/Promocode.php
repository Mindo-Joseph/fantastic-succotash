<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;

    protected $table = 'promocodes';

    protected $fillable = ['name', 'amount', 'expiry_date', 'promo_type_id', 'allow_free_delivery', 'minimum_spend', 'maximum_spend', 'first_order_only', 'limit_per_user', 'limit_total', 'paid_by_vendor_admin','restriction_on'];

    public function restriction()
    {
        return $this->hasMany(PromocodeRestriction::class);
    }
    public function details()
    {
        return $this->hasMany(PromoCodeDetail::class);
    }


    public function type()
    {
        return $this->belongsTo(PromoType::class, 'promo_type_id', 'id')->select('id', 'title')->where('status', 1);
    }
}

