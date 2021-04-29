<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Agent, AgentLog, Client, ClientPreference, Cms, Order, Task, TaskProof};
use Validation;
use DB;
use JWT\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Password;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SocialController extends BaseController
{
	/**
     * Social Keys
     */
    public function getKeys(Request $request)
    {
        if($request->type != 'facebook' && $request->type != 'twitter' && $request->type != 'google' && $request->type != 'apple'){
            return response()->json(['error' => 'Invalid request'], 404);
        }
        $prefer = ClientPreference::where('id', '>', 0);
        if($request->type == 'facebook'){
            $prefer = $prefer->select('fb_client_id', 'fb_client_secret');
        } elseif($request->type == 'twitter'){
            $prefer = $prefer->select('twitter_client_id', 'twitter_client_secret');
        } elseif($request->type == 'google'){
            $prefer = $prefer->select('google_client_id', 'google_client_secret');
        } elseif($request->type == 'apple'){
            $prefer = $prefer->select('apple_client_id', 'apple_client_secret');
        }
        $prefer = $prefer->first();

        return response()->json([
            'data' => $prefer,
        ]);
    }

    public function login(Request $request, $driver = '')
    {
        $customer = User::where('id', '>', 0);
        if($driver == 'facebook'){
            $customer = $customer->where('facebook_auth_id', $request->auth_id);
        } elseif ($driver == 'twitter'){
            $customer = $customer->where('twitter_auth_id', $request->auth_id);

        } elseif ($driver == 'google'){
            $customer = $customer->where('google_auth_id', $request->auth_id);
        }
        $customer = $customer->first();

        if(!$customer){

            $customer = new User();
            $eml = $request->auth_id.'@'.$driver.'-xyz.com';

            $customer->name = $request->name;
            $customer->email = empty($request->email) ? $eml : $request->email;
            $customer->password = Hash::make($user->getId());
            $customer->type = 1;
            
            $customer->role_id = 1;

            if($driver == 'facebook'){
                $customer->facebook_auth_id = $user->getId();
            } elseif ($driver == 'twitter'){
                $customer->twitter_auth_id = $user->getId();

            } elseif ($driver == 'google'){
                $customer->google_auth_id = $user->getId();
            }
        }

        $customer->status = 1;
        $customer->is_email_verified = 1;
        $customer->is_phone_verified = 1;
        $customer->save();

        $token1 = new Token;
        $token = $token1->make([
            'key' => 'royoorders-jwt',
            'issuer' => 'royoorders.com',
            'expiry' => strtotime('+1 month'),
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();
        $token1->setClaim('user_id', $customer->id);

        $customer->auth_token = $token;
        $customer->save();

        if($customer->id > 0){
            $user_device[] = [
                'user_id' => $customer->id,
                'device_type' => $request->device_type,
                'device_token' => $request->device_token,
                'access_token' => ''
            ];
            UserDevice::insert($user_device);

            $response['status'] = 'Success';
            $response['auth_token'] =  $token;
            $response['name'] = $user->name;
            $response['email'] = $user->email;
            $response['phone_number'] = $user->phone_number;
            $verified['is_email_verified'] = 1;
            $verified['is_phone_verified'] = 1;

            $prefer = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 
                        'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
            $preferData['theme_admin'] = $prefer->theme_admin;
            $preferData['distance_unit'] = $prefer->distance_unit;
            $preferData['map_provider'] = $prefer->map_provider;
            $preferData['date_format'] = $prefer->date_format;
            $preferData['time_format'] = $prefer->time_format;
            $preferData['map_key'] = $prefer->map_key;
            $preferData['sms_provider'] = $prefer->sms_provider;
            $preferData['verify_email'] = $prefer->verify_email;
            $preferData['verify_phone'] = $prefer->verify_phone;
            $preferData['app_template_id'] = $prefer->app_template_id;
            $preferData['web_template_id'] = $prefer->web_template_id;

            $response['client_preference'] = $preferData;
            $response['verify_details'] = $verified;

            return response()->json(['data' => $response]);
        }else{
            return response()->json(['error' => 'Something went wrong. Please try again.']);
        }
    }
}