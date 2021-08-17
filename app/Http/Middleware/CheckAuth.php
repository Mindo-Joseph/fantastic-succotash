<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use App\Models\{BlockedToken, User, ClientLanguage, ClientCurrency};
use Illuminate\Support\Facades\Cache;
use Request;
use Config;
use Illuminate\Support\Facades\DB;
use JWT\Token;
use Auth;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header();
        
        $user = new User();
        if (isset($header['authorization']) && Token::check($header['authorization'][0], 'royoorders-jwt')){
            $token = $header['authorization'][0];
            $tokenBlock = BlockedToken::where('token', $token)->first();
            if($tokenBlock){
                return response()->json(['error' => 'Invalid Session', 'message' => 'Session Expired'], 401);
                abort(404);
            }
            $user = User::whereHas('device', function($qu) use ($token){
                $qu->where('access_token', $token);
            })->first();

           
            if(!$user){
                return response()->json(['error' => 'Invalid Session', 'message' => 'Invalid Token or session has been expired.'], 401);
                abort(404);
            }
        }
        if(isset($header['systemuser'])){
            $systemUser = $header['systemuser'][0];
            $user->system_user = $systemUser;
        }

        $languages = ClientLanguage::where('is_primary', 1)->first();
        $primary_cur = ClientCurrency::where('is_primary', 1)->first();

        $language_id = $languages->language_id;
        $currency_id = $primary_cur->currency_id;

        if(isset($header['language'][0]) && !empty($header['language'][0])){
            $checkLang = ClientLanguage::where('language_id', $header['language'][0])->first();
            if($checkLang){
                $language_id = $checkLang->language_id;
            }
        }

        if(isset($header['currency'][0]) && !empty($header['currency'][0])){
            $checkCur = ClientCurrency::where('currency_id', $header['currency'][0])->first();
            if($checkCur){
                $currency_id = $checkCur->currency_id;
            }
        }

        $user->language = $language_id;
        $user->currency = $currency_id;
        
        Auth::login($user);

        return $next($request);
        
    }
}