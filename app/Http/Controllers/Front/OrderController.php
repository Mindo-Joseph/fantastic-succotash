<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use Illuminate\Http\Request;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, Client, Product, OrderProductAddon};
use Auth;

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
        $name = $request->first_name;
        if(!$request->last_name == null){
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
        foreach($cartProducts as $cartpro){
            
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
            $orderProducts->tax_rate_id = $cartpro['tax_rate_id'];
            $orderProducts->save();

            $cartAddon = CartAddon::where('cart_product_id', $cartpro['id'])->get()->toArray();
            foreach($cartAddon as $cartadd){
               $orderAddon = new OrderProductAddon;
               $orderAddon->order_product_id = $orderProducts->id;
               $orderAddon->addon_id = $cartadd['addon_id'];
               $orderAddon->option_id = $cartadd['option_id'];
               $orderAddon->save();
            }
        }
    
        dd("saved");
    }
}
