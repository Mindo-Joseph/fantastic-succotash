<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use Notifiable;
    protected $guard = 'client';
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain','status', 'code', 'country_id', 'timezone', 'is_deleted', 'is_blocked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime'];

    /**
     * Get Clientpreference
    */
    public function getPreference()
    {
      return $this->hasOne('App\Model\ClientPreference','client_id','code');
    }

    /**
     * Get Allocation Rules
    */
    public function getAllocation()
    {
      return $this->hasOne('App\Model\AllocationRule','client_id','code');
    }

    public function rules($id = ''){
        $rules = array(
            'name' => 'required|string|max:50',
            'phone_number' => 'required',
            'database_path' => 'required',
            'database_username' => 'required|max:50',
            'database_password' => 'required|max:50',
            'company_name' => 'required',
            'company_address' => 'required',
            'custom_domain' => 'required',
        );

        if(empty($id)){
            $rule['email'] = 'required|email|max:60|unique:clients';
            $rule['password'] = 'required|string|max:60';
            $rule['database_name'] = 'required|max:60|unique:clients';
        }

        /*if(!empty($id)){
            $rule['email'] = 'email|max:60|unique:clients,email,'.$id;
            $rule['database_name'] = 'max:60|unique:clients,database_name,'.$id;
        }*/
        return $rules;
    }

}