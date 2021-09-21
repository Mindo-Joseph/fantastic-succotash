<?php

namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Models\{Product,OrderProductRating,ClientPreference};
trait ApiResponser{

    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status'=> 'Success', 
			'message' => $message, 
			'data' => $data
		], $code);
	}

	protected function successMail()
	{
		$data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 
        'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
      

        $mail_from = $data->mail_from;
		Mail::send('frontend.paypalmail', compact('response'), function ($message) use ($request,$mail_from) {
			$message->from($mail_from);
			$message->to(Auth::user()->email);
			$message->subject('Payment Succesful Notification');
		});
	}

	protected function failMail()
	{
		$data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 
        'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
      

        $mail_from = $data->mail_from;
		Mail::send('frontend.paypalmailfail', compact('response'), function ($message) use ($request,$mail_from) {
			$message->from($mail_from);
			$message->to(Auth::user()->email);
			$message->subject('Payment Failure Notification');
		});
	}



	protected function errorResponse($message = null, $code, $data = null)
	{
		return response()->json([
			'status'=>'Error',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function updateaverageRating($product_id, $message = null, $code = 200)
	{	
		$ava_rating = OrderProductRating::where(['status' => '1','product_id' => $product_id])->avg('rating');
		$up_rat = Product::where('id',$product_id)->update(['averageRating' => $ava_rating]);
		return response()->json([
			'status'=>'Success',
			'message' => $message,
			'data' => $up_rat
		], $code);
	}



	  # check if last mile delivery on 
	  public function checkIfPickupDeliveryOnCommon(){
        $preference = ClientPreference::select('id','need_dispacher_ride','pickup_delivery_service_key','pickup_delivery_service_key_code','pickup_delivery_service_key_url')->first();
        if($preference->need_dispacher_ride == 1 && !empty($preference->pickup_delivery_service_key) && !empty($preference->pickup_delivery_service_key_code) && !empty($preference->pickup_delivery_service_key_url))
            return $preference;
        else
            return false;
    }



	 # check if on demand service  on 
	 public function checkIfOnDemandOnCommon(){
        $preference = ClientPreference::select('id','need_dispacher_home_other_service','dispacher_home_other_service_key','dispacher_home_other_service_key_code','dispacher_home_other_service_key_url')->first();
        if($preference->need_dispacher_home_other_service == 1 && !empty($preference->dispacher_home_other_service_key) && !empty($preference->dispacher_home_other_service_key_code) && !empty($preference->dispacher_home_other_service_key_url))
            return $preference;
        else
            return false;
    }
	

}