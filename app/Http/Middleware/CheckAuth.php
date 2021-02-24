<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use App\Models\{BlockedToken, User};
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

        if (isset($header['authorization']) && Token::check($header['authorization'][0], 'codebrewInd'))
        {
            $token = $header['authorization'][0];
            //$tokenBlock = BlockedToken::where('token', $token)->first();

            if($tokenBlock)
            {
                return response()->json(['error' => 'Invalid Session', 'message' => 'Session Expired'], 401);
                abort(404);
            }

            $user = User::where('auth_token', $token)->first();

            if(!$agent)
            {
                return response()->json(['error' => 'Invalid Session', 'message' => 'Invalid Token or session has been expired.'], 401);
                abort(404);
            }
            Auth::login($user);
            //return response()->json(['error' => 'Invalid Token', 'message' => 'Session Expired'], 401);
            //abort(404);
        }

        $language_id = 1;
        $currency = 'USD';

        if(isset($header['language'])){
            $language_id = $header['language'][0];
            $currency = $header['currency'][0];
        }

        $user->language = $language_id;
        $user->currency = $currency;
        
        Auth::login($user);

        return $next($request);
        
    }
}