<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class Client extends Authenticatable
{
    use Notifiable;
    protected $guard = 'client';
    protected $fillable = [
        'name', 'email', 'password', 'encpass', 'phone_number', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain','status', 'code', 'country_id', 'timezone', 'is_deleted', 'is_blocked'
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
      return $this->hasOne('App\Models\ClientPreference','client_code','code');
    }

    /**
     * Get Clientpreference
    */
    public function preferences()
    {
      return $this->hasOne('App\Models\ClientPreference', 'client_code', 'code')->select('theme_admin', 'client_code', 'distance_unit', 'currency_id', 'date_format', 'time_format', 'fb_login', 'twitter_login', 'google_login', 'apple_login', 'map_provider', 'app_template_id', 'is_hyperlocal', 'verify_email', 'verify_phone', 'primary_color', 'secondary_color', 'map_key');
    }

    public function getEncpassAttribute($value)
    {
      $value1 = $value;
      if(!empty($value)){
        $value1 = Crypt::decryptString($value);
      }
      return $value1;
    }

    public function setEncpassAttribute($value)
    {
        $this->attributes['encpass'] = Crypt::encryptString($value);
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
            //'database_path' => 'required',
            //'database_username' => 'required|max:50',
            //'database_password' => 'required|max:50',
            'company_name' => 'required',
            'company_address' => 'required',
            //'custom_domain' => 'required',
        );

        if(empty($id)){
            $rules['email'] = 'required|email|max:60|unique:clients';
            $rules['encpass'] = 'required|string|max:60|min:6';
            $rules['database_name'] = 'required|max:60|unique:clients';
        }

        /*if(!empty($id)){
            $rule['email'] = 'email|max:60|unique:clients,email,'.$id;
            $rule['database_name'] = 'max:60|unique:clients,database_name,'.$id;
        }*/
        return $rules;
    }

    public function getLogoAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url2'] = env('IMG_URL1');
      $values['image_path'] = env('IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['proxy_url'] = env('FIT_URl');
      $values['image_fit'] = env('FIT_URl');
      $values['original'] = \Storage::disk('s3')->url($img);
      
      return $values;
    }

}