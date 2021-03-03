<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Validator;
use App\Models\Client;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function clientLogin(Request $request)
    {
        $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'        => 'required',
        ]);

        $guard = Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password]);

        if ($guard) {
            $client = Client::with('preferences')->where('email', $request->email)->first();
            if($client->is_blocked == 1 || $client->is_deleted == 1){
                return redirect()->back()->with('Error', 'Your account has been blocked by admin. Please contact administration.');
            }

            Auth::login($client);
           
            return redirect()->route('client.dashboard');
        }

        return redirect()->back()->with('Error', 'Invalid Credentials');
    }

    public function Logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function wrongurl()
    {
        return redirect()->route('wrong.client');
    }

    public function showLoginForm()
    {
        return redirect()->route('admin.login');
    }
}
