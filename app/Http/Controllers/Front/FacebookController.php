<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{ClientLanguage, ClientCurrency, User, Country, UserDevice, UserVerification};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\SocialiteManager;
use Socialite;

class FacebookController extends FrontController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected function createFacebookDriver()
    {
        $config = $this->config->get('services.facebook');
        dd($config);

        return $this->buildProvider(
            FacebookProvider::class, $config
        );
    }

    public function redirectToFacebook()
    {
        /*echo $a = env('FACEBOOK_CLIENT_ID'); echo ' - ';
        echo $a = env('FACEBOOK_CLIENT_ID'); echo ' - ';
        echo $a = env('FACEBOOK_CLIENT_ID'); echo ' - ';
        echo $a = Config::get('FACEBOOK_CLIENT_ID'); echo ' - ';
        echo $a = Config::get('FACEBOOK_CLIENT_ID'); echo ' - ';
        echo $a = Config::get('FACEBOOK_CLIENT_ID');die;*/
        /*$facebook2 = [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'redirect' => env('FACEBOOK_CALLBACK_URL'),
        ];*/
        
        $config = $this->config->get('services.facebook');
        dd($config);


        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        /*$facebook2 = [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'redirect' => env('FACEBOOK_CALLBACK_URL'),
        ];*/
        try {
            $user = Socialite::driver('facebook')->user();
            $user_name = $user->getName();
            $user_email = $user->getEmail();
            $facebook_auth_id = $user->getId();
            /* other code */

            $user = User::where('facebook_auth_id', $user->getId())->first();
            if($user){
                Auth::login($user);
                return redirect()->route('userHome');
            }

            $user = new User();

            $user->name = $user->getName();
            $user->email = $user->getEmail();
            $user->facebook_auth_id = $user->getId();
            $user->password = Hash::make($user->getId());
            $user->type = 1;
            $user->status = 1;
            $user->role_id = 1;
            $user->save();

            if($user->id > 0){
                $user_device[] = [
                    'user_id' => $user->id,
                    'device_type' => 'web',
                    'device_token' => 'web',
                    'access_token' => ''
                ];
                UserDevice::insert($user_device);

                $user_verify[] = [
                    'user_id' => $user->id,
                    'is_email_verified' => 0,
                    'is_phone_verified' => 0
                ];
                UserVerification::insert($user_verify);
                Auth::login($user);
                return redirect()->route('userHome');
            }
        } catch (Exception $e) {
            return redirect()->route('userHome')->with(['error' => "facebook login failed."]);
        }
    }
}