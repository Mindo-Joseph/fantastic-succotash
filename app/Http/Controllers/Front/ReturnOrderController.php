<?php

namespace App\Http\Controllers\Front;

use DB;
use Config;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\Web\OrderProductRatingRequest;
use App\Http\Requests\Web\OrderProductReturnRequest;
use App\Models\{Client, ClientPreference, EmailTemplate, Order,OrderProductRating,VendorOrderStatus,OrderProduct,OrderProductRatingFile,ReturnReason,OrderReturnRequest,OrderReturnRequestFile, OrderVendor, OrderVendorProduct, User};
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Session;

class ReturnOrderController extends FrontController{
	
    use ApiResponser;
    
    /**
     * order details in modal
    */
    public function getOrderDatainModel(Request $request){
        try { 
            $order_details = Order::with(['vendors.products.productReturn','products.productRating', 'user', 'address',
            'vendors'=>function($qw)use($request){
                $qw->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
            },'vendors.products'=>function($qw)use($request){
                $qw->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
            },'products'=>function($qw)use($request){
                $qw->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
            }])->whereHas('vendors',function($q)use($request){
                $q->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
            })
            ->where('orders.user_id', Auth::user()->id)->where('orders.id', $request->id)->orderBy('orders.id', 'DESC')->first();
           
            if(isset($order_details)){
              
                if ($request->ajax()) {
                 return \Response::json(\View::make('frontend.modals.return-product-order', array('order' => $order_details))->render());
                }
            }
            return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    /**
     * order details in for return order
    */
    public function getReturnProducts(Request $request, $domain = ''){
        try {
            $langId = Session::get('customerLanguage');
            $navCategories = $this->categoryNav($langId);
            $reasons = ReturnReason::where('status','Active')->orderBy('order','asc')->get();
            $order_details = Order::with(['vendors.products' => function ($q1)use($request){
                $q1->where('id', $request->return_ids);
            },'products' => function ($q1)use($request){
                $q1->where('id', $request->return_ids);
            },'products.productRating', 'user', 'address'])
            ->whereHas('vendors.products',function($q)use($request){
                $q->where('id', $request->return_ids);
            })->where('orders.user_id', Auth::user()->id)->orderBy('orders.id', 'DESC')->first();
            
            if(isset($order_details)){
              return view('frontend.account.return-order')->with(['order' => $order_details,'navCategories' => $navCategories,'reasons' => $reasons]);
            }
            return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    /**
     * return  order product 
    */
    public function updateProductReturn(OrderProductReturnRequest $request){
        try {
            $user = Auth::user();
            $order_deliver = 0;
            $order_details = OrderProduct::where('id',$request->order_vendor_product_id)->whereHas('order',function($q){$q->where('user_id',Auth::id());})->first();
            if($order_details)
            $order_deliver = VendorOrderStatus::where(['order_id' => $order_details->order_id,'vendor_id' => $order_details->vendor_id,'order_status_option_id' => 5])->count();
            
            if($order_deliver > 0){
                $returns = OrderReturnRequest::updateOrCreate(['order_vendor_product_id' => $request->order_vendor_product_id,
                'order_id' => $order_details->order_id,
                'return_by' => Auth::id()],['reason' => $request->reason??null,'coments' => $request->coments??null]);

            //    if ($image = $request->file('images')) { 
            //         foreach ($image as $files) {
            //         $file =  substr(md5(microtime()), 0, 15).'_'.$files->getClientOriginalName();
            //         $storage = Storage::disk('s3')->put('/return', $files, 'public');
            //         $img = new OrderReturnRequestFile();
            //         $img->order_return_request_id = $returns->id;
            //         $img->file = $storage;
            //         $img->save();
                   
            //         }
            //     }

            if(isset($request->add_files) && is_array($request->add_files))    # send  array of insert images 
                {
                    foreach ($request->add_files as $storage) {
                        $img = new OrderReturnRequestFile();
                        $img->order_return_request_id = $returns->id;
                        $img->file = $storage;
                        $img->save();
                       
                    }
                }  
               
              if(isset($request->remove_files) && is_array($request->remove_files))    # send index array of deleted images 
                $removefiles = OrderReturnRequestFile::where('order_return_request_id',$returns->id)->whereIn('id',$request->remove_files)->delete();
       
            }
            if(isset($returns)) {
                $this->sendSuccessEmail($request);
                return $this->successResponse($returns,'Return Submitted.');
            }
            return $this->errorResponse('Invalid order', 200);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function sendSuccessEmail($request){
        if( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
            $user = User::where('auth_token', $request->auth_token)->first();
        }else{
            $user = Auth::user();
        }
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        $message = __('An otp has been sent to your email. Please check.');
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $sendto =  $user->email;
            $client_name = 'Sales';
            $mail_from = $data->mail_from;
            try {
                $order_vendor_product = OrderVendorProduct::where('id', $request->order_vendor_product_id)->first();
                $email_template_content = '';
                $email_template = EmailTemplate::where('id', 4)->first();
                if($email_template){
                    $email_template_content = $email_template->content;
                    $email_template_content = str_ireplace("{product_image}", $order_vendor_product->image['image_fit'].'200/200'.$order_vendor_product->image['image_path'], $email_template_content);
                    $email_template_content = str_ireplace("{product_name}", $order_vendor_product->product->title, $email_template_content);
                    $email_template_content = str_ireplace("{price}", $order_vendor_product->price, $email_template_content);
                }
                $data = [
                    'link' => "link",
                    'email' => $sendto,
                    'mail_from' => $mail_from,
                    'client_name' => $client_name,
                    'logo' => $client->logo['original'],
                    'subject' => $email_template->subject,
                    'customer_name' => ucwords($user->name),
                    'email_template_content' => $email_template_content,
                ];
                dispatch(new \App\Jobs\SendOrderSuccessEmailJob($data))->onQueue('verify_email');
                $notified = 1;
            } catch (\Exception $e) {
            }
        }
    }
}
