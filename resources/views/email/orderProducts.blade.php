 <tr>
     <td>
        <table  border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px; margin-top: 10px">
             <tr>
                <td colspan="3" style="border-bottom: 1px solid rgb(151 151 151 / 23%);padding: 3px 0 10px;"></td>
             </tr>    
            @foreach($cartData->products as $product)
            
            <tr class="pad-left-right-space">
                <td colspan="4"><h4 class="main-bg-light" style="padding: 5px 10px; margin-top: 5px; margin-bottom: 5px">{{$product['vendor']['name']}}</h4></td>
            </tr>
            @php
                $total_products = 0;
            @endphp
                @foreach($product['vendor_products'] as $vendor_product)
                    <tr style="vertical-align: bottom;">
                        <td style="width: 45%;padding: 15px 0 10px; ">
                           <div style="display: flex;align-items: center;">
                              <div style=" height: 60px;width: 60px;background-color: #D8D8D8;">
                                 <img style="width: 100%;height: 100%;border-radius: 3px;object-fit: cover;" src="{{$vendor_product['product']['media'][0]['image']['path']['image_fit']}}100/100{{$vendor_product['product']['media'][0]['image']['path']['image_path']}}" alt="">
                              </div>
                              <div class="flex-set" style="display:flex; justify-content:space-between; flex-direction:column; height:60px; padding: 0 0 0 15px;">
                                 <h3 style="color: #000000;font-size: 15px;letter-spacing: 0;line-height: 19px;">{{$vendor_product['product']['translation_one']['title']}}</h3>
                                 <p style="color: #000000;font-size: 15px;letter-spacing: 0;line-height: 18px;margin: 0;"> <span style="color: #777777;">{{__('Item price')}} : </span> {{ $currencySymbol . number_format(($vendor_product['pvariant']['price']*$vendor_product['quantity']), 2, '.', '')}}</p>
                              </div>
                           </div>
                        </td>
                        <td style="width: 20%;padding: 10px; text-align: center;">x {{$vendor_product['quantity']}}</td>
                        <td style="width: 35%;padding: 10px 0;  text-align: right;">
                           <div class="flex-set-scd"  style="display:flex; justify-content:end; align-items:end; flex-direction:column; height:60px;">
                              {{-- <h3 style="color: #000000;font-size: 15px;letter-spacing: 0;line-height: 19px;"># 231</h3> --}}
                              <p style="color: #000000;font-size: 15px;letter-spacing: 0;line-height: 18px;margin: 0;"> <span style="color: #777777;">{{__('Item price')}} : </span> {{ $currencySymbol . number_format($vendor_product['pvariant']['price'], 2, '.', '')}}</p>
                           </div>
                           @php
                           $total_products += $vendor_product['pvariant']['price'];
                           @endphp
                        </td>
                     </tr>

                     <tr>
                        <td colspan="3" style="border-bottom: 1px solid rgb(151 151 151 / 23%);padding: 5px 0;"></td>
                     </tr> 



                @endforeach
            @endforeach
         
            <tr>
                <td style="text-align: left;"><b>{{__('Subtotal')}}:</b></td>
                <td></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($order->total_amount, 2, '.', '')}}</td>
             </tr>
             <tr>
                <td style="text-align: left;"><b>{{__('Tax')}}:</b></td>
                <td></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($order->taxable_amount, 2, '.', '')}}</td>
             </tr>
            
            @if($order->total_delivery_fee > 0)

            <tr>
                <td style="text-align: left;"><b>{{__('Delivery Charge')}}:</b></td>
                <td></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($order->total_delivery_fee, 2, '.', '')}}</td>
             </tr>

            @endif
            @if($order->tip_amount > 0)

            <tr>
                <td style="text-align: left;"><b>{{__('Tip')}}:</b></td>
                <td></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($order->tip_amount, 2, '.', '')}}</td>
             </tr>

           
            @endif
            @if($order->subscription_discount > 0)

            <tr>
                <td style="text-align: left;"><b>{{__('Subscription Discount')}}:</b></td>
                <td></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($order->subscription_discount, 2, '.', '')}}</td>
             </tr>

            @endif
            @if($order->loyalty_amount_saved > 0)

            <tr>
                <td style="text-align: left;"><b>{{__('Loyalty Amount Used')}}:</b></td>
                <td></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($order->loyalty_amount_saved, 2, '.', '')}}</td>
             </tr>

            @endif
            @if($order->wallet_amount_used > 0)

            <tr>
                <td style="text-align: left;"><b>{{__('Wallet Amount Used')}}:</b></td>
                <td></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($order->wallet_amount_used, 2, '.', '')}}</td>
            </tr>

            @endif
            @if($order->total_discount > 0)
            <tr>
                <td style="text-align: left;"><b>{{__('Total Discount')}}:</b></td>
                <td></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($order->total_discount, 2, '.', '')}}</td>
            </tr>
            @endif

            <tr>
                <td style="text-align: left;"><b>{{__('Total')}}:</b></td>
                <td></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($order->payable_amount, 2, '.', '')}}</td>
            </tr>

        </table>
     </td>
 </tr>
