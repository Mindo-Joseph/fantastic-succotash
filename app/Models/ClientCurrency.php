<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCurrency extends Model
{
	protected $fillable = ['client_code', 'currency_id'];

    public function currency()
    {
      return $this->belongsTo('App\Models\Currency','currency_id','id')->select('id', 'name', 'iso_code', 'symbol', 'subunit');
    }
}
