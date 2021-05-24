<?php

namespace App\Http\Controllers\Front;
use DB;
use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, User, Product, OrderProductAddon, Payment, UserAddress, OrderVendor};
use GuzzleHttp\Client;
class OrderController extends FrontController{
    
    public function getOrderSuccessPage(Request $request){
        $order = Order::with('products')->findOrfail($request->order_id);
        return view('forntend.order.success', compact('order'));
    }
    public function placeOrder(Request $request, $domain = ''){
        if ($request->input("payment-group") == '1') {
            $langId = Session::get('customerLanguage');
            $navCategories = $this->categoryNav($langId);
            return view('forntend/orderPayment')->with(['navCategories' => $navCategories, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'email_address' => $request->email_address, 'phone' => $request->phone , 'total_amount' => $request->total_amount , 'address_id' => $request->address_id]);
        }
        $order = $this->orderSave($request, "1", "2");
        return redirect('order/success/'.$order->id)->with('success', 'your message,here'); 
    }

    public function orderSave($request, $paymentStatus, $paymentMethod){
        try {
           DB::beginTransaction();
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            $order = new Order;
            $order->user_id = $user->id;
            $order->order_number = generateOrderNo();
            $order->payment_method = $paymentMethod;
            $order->address_id = $request->address_id;
            $order->save();
            $cart_products = CartProduct::with('product.pimage', 'product.variants')->where('cart_id', $cart->id)->get();
            foreach ($cart_products as $cart_product) {
                $variant = $cart_product->product->variants->where('id', $cart_product->variant_id)->first();
                $order_product = new OrderProduct;
                $order_product->order_id = $order->id;
                $order_product->price = $variant->price;
                $order_product->quantity = $cart_product->quantity;
                $order_product->vendor_id = $cart_product->vendor_id;
                $order_product->product_id = $cart_product->product_id;
                $order_product->created_by = $cart_product->created_by;
                $order_product->variant_id = $cart_product->variant_id;
                $order_product->product_name = $cart_product->product->sku;
                if($cart_product->product->pimage){
                    $order_product->image = $cart_product->product->pimage->first() ? $cart_product->product->pimage->first()->path : '';
                }
                $order_product->save();
                $cart_addons = CartAddon::where('cart_product_id', $cart_product->id)->get();
                if($cart_addons){
                    foreach ($cart_addons as $cart_addon) {
                        $orderAddon = new OrderProductAddon;
                        $orderAddon->addon_id = $cart_addon->addon_id;
                        $orderAddon->option_id = $cart_addon->option_id;
                        $orderAddon->order_product_id = $order_product->id;
                        $orderAddon->save();
                    }
                    CartAddon::where('cart_product_id', $cart_product->id)->delete();
                }
            }
            CartProduct::where('cart_id', $cart->id)->delete();
          
            

            $order_dispatch = $this->placeRequestToDispatch($order,$cart_products);
          
           DB::commit();
            return $order; 
        } catch (Exception $e) {
            DB::rollback();
        }
    }
    
    public function makePayment(Request $request){
        $token = $request->stripeToken;
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey('sk_test_51IhpwhSFHEA938FwRPiQSAH5xF6DcjO5GCASiud9cGMJ0v8UJyRfCb7IQAMbXbuPMe7JphA1izxZOsIclvmOgqUV00Zpk85xfl');
        $formData = [
            'number' => $request->card_num,
            'description' => $request->first_name,
            'expiryMonth' => $request->exp_month,
            'expiryYear' => $request->exp_year,
            'cvv' => $request->cvc
        ];
        $response = $gateway->purchase(
            [
                'amount' => $request->amount,
                'currency' => 'INR',
                'card' => $formData,
                'token' => $token,
            ]
        )->send();
        if ($response->isSuccessful()) {
            $cart = Cart::where('user_id', Auth::user()->id)->first();
            $payment = new Payment();
            $payment->amount = $request->amount;
            $payment->transaction_id = $response->getData()['id'];
            $payment->balance_transaction = $response->getData()['balance_transaction'];
            $payment->type = "card";
            $payment->cart_id = $cart->id;
            $payment->save();
            $this->orderSave($request, "2", "1");
        } elseif ($response->isRedirect()) {
            $response->redirect();
        } else {
            exit($response->getMessage());
        }
    }




    public function placeRequestToDispatch($order,$cart_products){
        try
        {
           $customer = User::find($order->user_id);
           $cus_address = UserAddress::find($order->address_id);
           $vendor_ids = array_column($cart_products->toArray(),'vendor_id');
           
           $tasks = array();
           dd($vendor_ids);
           foreach($vendors as $key => $vendor){
               $vendor_details = Vendor::find($vendor->id);
               $tasks = array();

               $tasks[] = array('task_type_id' => 1,
                                'latitude' => $vendor_details->latitude??Null,
                                'longitude' => $vendor_details->longitude??Null,
                                'short_name' => Null,
                                'address' => $vendor_details->address??Null,
                                'post_code' => Null,
                                );
                
                $tasks[] = array('task_type_id' => 2,
                                'latitude' => $cus_address->latitude??Null,
                                'longitude' => $cus_address->longitude??Null,
                                'short_name' => Null,
                                'address' => $cus_address->address??Null,
                                'post_code' => $cus_address->pincode??Null,
                                );

           }

           dd($tasks);

           $client = new Client();
           $res = $client->request('POST', 'http://local.dispatcher.com/api/public/task/create', [
               'form_params' => [
                   'customer_name' => $customer->name ?? 'Dummy Customer',
                   'customer_phone_number' => $customer->phone_number ?? '+919041969648',
                   'customer_email' => $customer->email ?? 'dineshk@codebrewinnovations.com',
               ],
               'headers' => [ 'client' => 'userclient' ]
           ]);
           
           
           if ($res->getStatusCode() == 200) { // 200 OK
            $response_data = json_decode($res->getBody()->getContents());
            if($response_data->status == 200)
            {
                dd('success');
            }
            else{
                dd($response_data->message);
            }
            
        }
        
        }
        catch(\Exception $e)
        {
            dd($e->getMessage());
           
        }


       



    }    
      
}
