<?php

namespace App\Http\Controllers\Front;
use DB;
use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, User, Product, OrderProductAddon, Payment, ClientCurrency,OrderVendor};

class OrderController extends FrontController{
    
    public function getOrderSuccessPage(Request $request){
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $order = Order::with(['products', 'address'])->findOrfail($request->order_id);
        return view('forntend.order.success', compact('order','navCategories'));
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
            $currency_id = Session::get('customerCurrency');
            $language_id = Session::get('customerLanguage');
            $cart = Cart::where('user_id', $user->id)->first();
            $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
            $order = new Order;
            $order->user_id = $user->id;
            $order->order_number = generateOrderNo();
            $order->payment_method = $paymentMethod;
            $order->address_id = $request->address_id;
            $order->save();
            $cart_products = CartProduct::select('*')->with('product.pimage', 'product.variants', 'product.taxCategory.taxRate','coupon.promo')->where('cart_id', $cart->id)->where('status', [0,1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
            $total_amount = 0;
            $total_discount = 0;
            $taxable_amount = 0;
            $payable_amount = 0;
            foreach ($cart_products->groupBy('vendor_id') as $vendor_id => $vendor_cart_products) {
                $vendor_payable_amount = 0;
                $vendor_discount_amount = 0;
                $product_taxable_amount = 0;
                $product_payable_amount = 0;
                foreach ($vendor_cart_products as $vendor_cart_product) {
                    $variant = $vendor_cart_product->product->variants->where('id', $vendor_cart_product->variant_id)->first();
                    $quantity_price = 0;
                    $divider = (empty($vendor_cart_product->doller_compare) || $vendor_cart_product->doller_compare < 0) ? 1 : $vendor_cart_product->doller_compare;
                    $price_in_currency = $variant->price / $divider;
                    $price_in_dollar_compare = $price_in_currency * $clientCurrency->doller_compare;
                    $quantity_price = $price_in_dollar_compare * $vendor_cart_product->quantity;
                    $payable_amount = $payable_amount + $quantity_price;
                    $vendor_payable_amount = $vendor_payable_amount + $quantity_price;
                    
                    foreach ($vendor_cart_product->product['taxCategory']['taxRate'] as $tax_rate_detail) {
                        $rate = round($tax_rate_detail->tax_rate);
                        $tax_amount = ($price_in_dollar_compare * $rate) / 100;
                        $product_tax = $quantity_price * $rate / 100;
                        $product_taxable_amount += $taxable_amount + $product_tax;
                        $payable_amount = $payable_amount + $product_tax;
                        $vendor_payable_amount = $vendor_payable_amount + $product_tax;
                    }
                    $total_amount += $variant->price;
                    $order_product = new OrderProduct;
                    $order_product->order_id = $order->id;
                    $order_product->price = $variant->price;
                    $taxable_amount += $product_taxable_amount;
                    $order_product->taxable_amount = $product_taxable_amount;
                    $order_product->quantity = $vendor_cart_product->quantity;
                    $order_product->vendor_id = $vendor_cart_product->vendor_id;
                    $order_product->product_id = $vendor_cart_product->product_id;
                    $order_product->created_by = $vendor_cart_product->created_by;
                    $order_product->variant_id = $vendor_cart_product->variant_id;
                    $order_product->product_name = $vendor_cart_product->product->sku;
                    if($vendor_cart_product->product->pimage){
                        $order_product->image = $vendor_cart_product->product->pimage->first() ? $vendor_cart_product->product->pimage->first()->path : '';
                    }
                    $order_product->save();
                    $cart_addons = CartAddon::where('cart_product_id', $vendor_cart_product->id)->get();
                    if($cart_addons){
                        foreach ($cart_addons as $cart_addon) {
                            $orderAddon = new OrderProductAddon;
                            $orderAddon->addon_id = $cart_addon->addon_id;
                            $orderAddon->option_id = $cart_addon->option_id;
                            $orderAddon->order_product_id = $order_product->id;
                            $orderAddon->save();
                        }
                        CartAddon::where('cart_product_id', $vendor_cart_product->id)->delete();
                    }
                }
                $OrderVendor = new OrderVendor();
                $OrderVendor->status = 0;
                $OrderVendor->order_id= $order->id;
                $OrderVendor->vendor_id= $vendor_id;
                $OrderVendor->payable_amount= $vendor_payable_amount;
                $OrderVendor->discount_amount= $vendor_discount_amount;
                $OrderVendor->save();
            }
            $order->total_amount = $total_amount;
            $order->total_discount = $total_discount;
            $order->taxable_amount = $taxable_amount;
            $order->payable_amount = $payable_amount;
            $order->save();
            CartProduct::where('cart_id', $cart->id)->delete();
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
}
