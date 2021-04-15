<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Client, Category, Brand, Product, ClientLanguage, User, ClientCurrency, ClientPreference, UserVerification};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
//use Illuminate\Support\Facades\Redis;
use Auth;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\Storage;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Mail;


class UserController extends FrontController
{
    private $field_status = 2;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyAccount(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $preference = ClientPreference::select('verify_email', 'verify_phone')->where('id', '>', 0)->first();

        $verify = UserVerification::select('is_email_verified', 'is_phone_verified')
                    ->where('user_id', Auth::user()->id)->first();

        if($preference->verify_email == 0 && $preference->verify_phone == 0){

            return redirect()->route('userHome');

        } elseif ($verify->is_email_verified == 1 && $verify->is_phone_verified == 1){

            return redirect()->route('userHome');

        } elseif ($preference->verify_email == 1 && $preference->verify_phone == 0){

            if($verify->is_email_verified == 1){
                return redirect()->route('userHome');
            }
        } elseif ($preference->verify_email == 0 && $preference->verify_phone == 1){

            if($verify->is_phone_verified == 1){
                return redirect()->route('userHome');
            }
        }
        $navCategories = $this->categoryNav($langId);

        /**     * Display resetPassword Form     */
        return view('forntend/account/verifyAccount')->with(['preference' => $preference, 'verify' => $verify, 'navCategories' => $navCategories]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendToken(Request $request, $domain = '', $uid = 0)
    {
        $user = User::where('id', Auth::user()->id)->firstOrFail();
        if($request->has('type')){

            $verify = UserVerification::where('user_id', $user->id)->first();

            if($request->type == 'phone'){
                $phoneCode = mt_rand(100000, 999999);
                $verify->phone_token = $phoneCode;

                //$user->notify(new VerifyEmail());
            }

            if($request->type == 'email'){

                $mailCode = mt_rand(100000, 999999);
                $verify->email_token = $mailCode;
                $newDateTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();

                $client = Client::select('id', 'name', 'email', 'phone_number')->where('id', '>', 0)->first();
                $this->setMailDetail($client);

                $phoneCode = substr(md5(microtime()), 0, 6);
                $verify->phone_token = $phoneCode;

                $client_details = $client->name;
                $mail = "gargpuneet217@gmail.com";
                $sendto = "editerzframes@gmail.com";

                Mail::send('email.verify',[
                                'customer_name' => 'Puneet',
                                'code_text' => 'Enter below code to verify yoour account',
                                'code' => $fourRandomDigit,
                                'logo' => 'Enter below code to verify yoour account',
                                'link'=>"link"
                            ],
                            function ($message) use($sendto, $client_details, $mail) {
                            $message->from($mail,$client_details);
                            $message->to($sendto)->subject('Order Update | '.$client_details);
                });

            }
            $verify->save();
        }
            
    }
}