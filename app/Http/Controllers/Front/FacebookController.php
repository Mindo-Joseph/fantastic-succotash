<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{ClientLanguage, ClientCurrency, User, Country, UserDevice, UserVerification, ClientPreference};
use Illuminate\Http\Request;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use InvalidArgumentException;

use Session;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\One\TwitterProvider;
use Laravel\Socialite\SocialiteManager;
use League\OAuth1\Client\Server\Twitter as TwitterServer;
use Socialite;

class FacebookController extends Manager
{
    private function configDriver($domain = '', $driver = 'facebook'){
        $ClientPreferences = ClientPreference::select('fb_login', 'fb_client_id', 'fb_client_secret', 'fb_client_url', 'twitter_login', 'twitter_client_id', 'twitter_client_secret', 'twitter_client_url', 'google_login', 'google_client_id', 'google_client_secret', 'google_client_url', 'apple_login', 'apple_client_id', 'apple_client_secret', 'apple_client_url')->first();

       
        if($driver == 'facebook'){
            $config['client_id'] = $ClientPreferences->fb_client_id;
            $config['client_secret'] = $ClientPreferences->fb_client_secret;
            $config['redirect'] = 'https://'.$domain.'/auth/callback/facebook';

            return Socialite::buildProvider(FacebookProvider::class, $config);

        } elseif ($driver == 'google'){
            $config['client_id'] = $ClientPreferences->google_client_id;
            $config['client_secret'] = $ClientPreferences->google_client_secret;
            $config['redirect'] = 'https://'.$domain.'/auth/callback/google';

            return Socialite::buildProvider(GoogleProvider::class, $config);
            
        }elseif ($driver == 'twitter'){
            $config['client_id'] = $ClientPreferences->twitter_client_id;
            $config['client_secret'] = $ClientPreferences->twitter_client_secret;
            $config['redirect'] = 'https://'.$domain.'/auth/callback/twitter';

            $array_merge = array_merge([
                'identifier' => $config['client_id'],
                'secret' => $config['client_secret'],
                'callback_uri' => $this->formatRedirectUrl($config),
            ], $config);

            return new TwitterProvider(
                $this->container->make('request'), new TwitterServer($array_merge)
            );            
        }
    }

    private function formatRedirectUrl(array $config)
    {
        $redirect = value($config['redirect']);

        return Str::startsWith($redirect, '/')
                    ? $this->container->make('url')->to($redirect)
                    : $redirect;
    }

    public function redirectToSocial($domain = '', $redirecting = 'facebook')
    {
        $fb = $this->configDriver($domain, $redirecting);

        return $fb->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleSocialCallback($domain = '', $driver = 'facebook')
    {
        try {
            $usr = $this->makeSocialDriver($domain, $driver);
            $user = $usr->user();

            $customer = User::where('status', '!=', 2);
            if($driver == 'facebook'){
                $customer = $customer->where('facebook_auth_id', $user->getId());
            } elseif ($driver == 'twitter'){
                $customer = $customer->where('twitter_auth_id', $user->getId());

            } elseif ($driver == 'google'){
                $customer = $customer->where('google_auth_id', $user->getId());
            }
            $customer = $customer->first();

            if($customer){
                Auth::login($customer);
                return redirect()->route('userHome');
            }

            $customer = new User();

            $customer->name = $user->getName();
            $customer->email = $user->getEmail();
            $customer->facebook_auth_id = $user->getId();
            $customer->password = Hash::make($user->getId());
            $customer->type = 1;
            $customer->status = 1;
            $customer->role_id = 1;
            $customer->save();

            if($customer->id > 0){
                $user_device[] = [
                    'user_id' => $customer->id,
                    'device_type' => 'web',
                    'device_token' => 'web',
                    'access_token' => ''
                ];
                UserDevice::insert($user_device);

                $user_verify[] = [
                    'user_id' => $customer->id,
                    'is_email_verified' => 0,
                    'is_phone_verified' => 0
                ];
                UserVerification::insert($user_verify);
                Auth::login($customer);
                return redirect()->route('userHome');
            }
        } catch (Exception $e) {
            return redirect()->route('userHome')->with(['error' => "facebook login failed."]);
        }
    }
}