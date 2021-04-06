<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{ClientLanguage, ClientCurrency, User, Country, UserDevice, UserVerification};
use Illuminate\Http\Request;

use Session;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\SocialiteManager;
use Socialite;

class FacebookController extends FrontController
{
    private $conp;

    private function makeFacebookDriver($domain = ''){

        $config['client_id'] = 'aaaaaaaaaa';
        $config['client_secret'] = 'aaaaaaaaaaaaaaaaaa';
        $config['redirect'] = 'http://'.$domain.'/auth/facebook/callback';
        return  Socialite::buildProvider(FacebookProvider::class, $config);
    }
    

    public function redirectToFacebook(Request $request)
    {
        //$driver = Socialite::driver('facebook');
        //return Socialite::driver('facebook')->redirect();
        $fb = $this->makeFacebookDriver('www.facebook.com');

        dd($fb);
        return $fb->redirect();
        
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();

            $customer = User::where('facebook_auth_id', $user->getId())->first();
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