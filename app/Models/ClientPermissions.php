<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPermissions extends Model
{
    protected $fillable = ['client_id','permission_id'];


    public function permission(){
        return $this->belongsTo('App\Model\Permissions', 'permission_id', 'id');
    }
}
