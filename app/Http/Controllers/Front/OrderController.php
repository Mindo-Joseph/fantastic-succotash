<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use Illuminate\Http\Request;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, User, Product, OrderProductAddon, Payment};
use Auth;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class OrderController extends FrontController
{
    /**
     * Place order of user
     *
     * @return \Illuminate\Http\Response
     */
    public function placeOrder(Request $request, $domain = '')
    {
        // dd($request->all());
        if ($request->input("payment-group") == '1') {
            $langId = Session::get('customerLanguage');
            $navCategories = $this->categoryNav($langId);
            return view('forntend/orderPayment')->with(['navCategories' => $navCategories, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'email_address' => $request->email_address, 'phone' => $request->phone , 'total_amount' => $request->total_amount , 'address_id' => $request->address_id]);
        }
        dd($request->all());
        $this->orderSave($request);
    }

    public function orderSave($request)
    {
        // dd($request->first_name);
        $name = $request->first_name;
        if (!$request->last_name == null) {
            $name = $name . " " . $request->last_name;
        }
        $cart = Cart::where('user_id', Auth::user()->id)->first();
        $cartProduct = CartProduct::where('cart_id', $cart->id)->count();
        $order = new Order;
        $order->user_id = Auth::user()->id;
        $order->address_id = $request->address_id;
        $order->recipient_name = $name;
        $order->recipient_email = $request->email_address;
        $order->recipient_number = $request->phone;
        $order->item_count = $cartProduct;
        $order->save();

        $cartProducts = CartProduct::where('cart_id', $cart->id)->get()->toArray();
        foreach ($cartProducts as $cartpro) {

            $productName = Product::where('id', $cartpro['product_id'])->first()->toArray();
            $orderProducts = new OrderProduct;
            $orderProducts->order_id = $order->id;
            $orderProducts->product_id = $cartpro['product_id'];
            $orderProducts->quantity = $cartpro['quantity'];
            $orderProducts->product_name = $productName['sku'];
            $orderProducts->vendor_id = $cartpro['vendor_id'];
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

        dd("order successfully saved");

    }

    public function makePayment(Request $request)
    {
        // dd($request->all());

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
            // mark order as complete
            // dd($response->getData());
            $cart = Cart::where('user_id', Auth::user()->id)->first();
            $payment = new Payment();
            $payment->amount = $request->amount;
            $payment->transaction_id = $response->getData()['id'];
            $payment->balance_transaction = $response->getData()['balance_transaction'];
            $payment->type = "card";
            $payment->cart_id = $cart->id;
            $payment->save();

            $this->orderSave($request);

        } elseif ($response->isRedirect()) {
            $response->redirect();
        } else {
            // display error to customer
            exit($response->getMessage());
        }
    }
}
