<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\{Client, ClientPreference, ClientLanguage, ClientCurrency,Permissions};
use Request;
use Config;
use Session;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Redirect;
class ClientAuth
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
        if(auth()->check()) {
            if(Auth::user()->status == 2){
                Auth::logout();
                return redirect('login')->with(['account_blocked' => 'Your account has been blocked by admin. Please contact administration.']);
            }
            if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1){

                                if (Auth::user()->is_superadmin == 0) {
                                    $currentPath = \Request::path();
                                    if ($currentPath == "client/profile") {
                                    } else {
                                        $sub_admin_per = false;
                                        $permission_exist = false;
                                        $check_url = Request::path();
                                        $per_url = explode('/', $check_url);
                                        $url_path = $per_url[1];
                                        $check_if_under_permision = Permissions::get()->pluck('slug')->toArray();
                                        if (in_array($url_path,$check_if_under_permision)){
                                            $permission_exist = true;
                                        }
                                        if ($permission_exist == true) {
                                            foreach (Auth::user()->getAllPermissions as $key => $value) {
                                                if ($value->permission->slug == $url_path) {
                                                    $sub_admin_per = true;
                                                }
                                            }
                                            }
                                        else {
                                            $sub_admin_per = true;
                                        }
                                            
                    
                                        if ($sub_admin_per == false) {
                                            return Redirect::route('client.profile');
                                        }
                                    }
                                
                            }

                return $next($request);
             }
             else{
                 Auth::logout();
                 return redirect('login')->with(['account_blocked' => 'You are unauthorized user.']);
             }

            
        }
        return redirect('user/login');
        
    }
}