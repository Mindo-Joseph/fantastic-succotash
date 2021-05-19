<?php

namespace App\Http\Controllers\Front;

use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, User, Product, OrderProductAddon, Payment};

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
        $name = $request->first_name;
        if (!$request->last_name == null) {
            $name = $name . " " . $request->last_name;
        }
        $cart = Cart::where('user_id', Auth::user()->id)->first();
        $cartProduct = CartProduct::where('cart_id', $cart->id)->count();
        $latestOrder = Order::orderBy('created_at','DESC')->first();
        $order = new Order;
        $order->recipient_name = $name;
        $order->user_id = Auth::user()->id;
        $order->payment_method = $paymentMethod;
        $order->payment_status = $paymentStatus;
        $order->address_id = $request->address_id;
        $order->recipient_number = $request->phone;
        $order->recipient_email = $request->email_address;
        $order->order_no = str_pad($latest_order->id + 1, 8, "0", STR_PAD_LEFT);
        $order->save();
        $cartProducts = CartProduct::where('cart_id', $cart->id)->get()->toArray();
        foreach ($cartProducts as $cartpro) {
            $productName = Product::where('id', $cartpro['product_id'])->first()->toArray();
            $orderProducts = new OrderProduct;
            $orderProducts->order_id = $order->id;
            $orderProducts->quantity = $cartpro['quantity'];
            $orderProducts->vendor_id = $cartpro['vendor_id'];
            $orderProducts->product_name = $productName['sku'];
            $orderProducts->product_id = $cartpro['product_id'];
            $orderProducts->created_by = $cartpro['created_by'];
            $orderProducts->variant_id = $cartpro['variant_id'];
            $orderProducts->is_tax_applied = $cartpro['is_tax_applied'];
            $orderProducts->save();
            $cartAddon = CartAddon::where('cart_product_id', $cartpro['id'])->get()->toArray();
            foreach ($cartAddon as $cartadd) {
                $orderAddon = new OrderProductAddon;
                $orderAddon->order_product_id = $orderProducts->id;
                $orderAddon->addon_id = $cartadd['addon_id'];
                $orderAddon->option_id = $cartadd['option_id'];
                $orderAddon->save();
            }
        }
        return $order;
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
}
