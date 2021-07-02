<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VendorSlot extends Model
{
    public function day(){
        $client = Client::first();
        $mytime = Carbon::now()->setTimezone($client->timezone);
        return $this->hasMany('App\Models\SlotDay', 'slot_id', 'id')->where('day', $mytime->dayOfWeek+1); 
    }
}
