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
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\SocialiteManager;
use Socialite;

class FacebookController extends FrontController
{
    private $conp;
    public $scopeSeparator = ',';
    public $parameters = [];
    /**
     * Get the GET parameters for the code request.
     *
     * @param  string|null  $state
     * @return array
     */
    protected function getCodeFields($state = null)
    {
        echo "asdasdasd";die;
        $fields = [
            'client_id' => 'qewqweqeweeeqwewq',
            'redirect_uri' => 'wqeeeeeeeeeeeeeeeeeeeqewqewqe',
            'scope' => $this->formatScopes(AbstractProvider::getScopes(), $this->scopeSeparator),
            'response_type' => 'code',
        ];

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        if ($this->usesPKCE()) {
            $fields['code_challenge'] = $this->getCodeChallenge();
            $fields['code_challenge_method'] = $this->getCodeChallengeMethod();
        }

        return array_merge($fields, $this->parameters);
    }

    /**
     * Generates the PKCE code challenge based on the PKCE code verifier in the session.
     *
     * @return string
     */
    protected function getCodeChallenge()
    {
        $hashed = hash('sha256', $this->request->session()->get('code_verifier'), true);

        return rtrim(strtr(base64_encode($hashed), '+/', '-_'), '=');
    }

    /**
     * Returns the hash method used to calculate the PKCE code challenge.
     *
     * @return string
     */
    protected function getCodeChallengeMethod()
    {
        return 'S256';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $driver = Socialite::driver('facebook');
  
        //dd($driver->clientId); echo "  --   ";
        
        //echo '<pre>';print_r($driver->toArray()); echo '</pre>';
        dd($driver);die;
        return Socialite::driver('facebook')->redirect();
        /*dd($aaa);
        echo '1';
        dd($this->conp);
        return $aaa;*/
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