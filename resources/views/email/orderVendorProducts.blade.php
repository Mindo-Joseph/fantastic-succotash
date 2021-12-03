 <tr>
     <td>
         @foreach($cartData->products as $product)
         @if($product['vendor_id'] == $id)
         <h4>Vendor: {{$product['vendor']['name']}}</h4>
         <table  border="0" cellpadding="0" cellspacing="0" align="left" style="width: 100%;margin-bottom: 50px;">
            <tr>
                <td colspan="3" style="border-bottom: 1px solid rgb(151 151 151 / 23%);padding: 3px 0 10px;"></td>
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
                      <div style="display: flex;justify-content: space-between;flex-direction: column;height: 60px;padding: 0 0 0 15px;">
                         <h3 style="color: #000000;font-size: 15px;letter-spacing: 0;line-height: 19px;">{{$vendor_product['product']['translation_one']['title']}}</h3>
                         <p style="color: #000000;font-size: 15px;letter-spacing: 0;line-height: 18px;margin: 0;"> <span style="color: #777777;">Item price : </span> $90.00</p>
                      </div>
                   </div>
                </td>
                <td style="width: 20%;padding: 10px; text-align: center;">x {{$vendor_product['quantity']}}</td>
                <td style="width: 35%;padding: 10px 0;  text-align: right;">
                   <div style="display: flex;justify-content: space-between;flex-direction: column;height: 60px;">
                      {{-- <h3 style="color: #000000;font-size: 15px;letter-spacing: 0;line-height: 19px;"># 231</h3> --}}
                      <p style="color: #000000;font-size: 15px;letter-spacing: 0;line-height: 18px;margin: 0;"> <span style="color: #777777;">Item price : </span> {{ $currencySymbol . number_format($vendor_product['pvariant']['price'], 2, '.', '')}}</p>
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
             <tr>
                <td style="text-align: left;"><b>{{__('Subtotal')}}:</b></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($total_products, 2, '.', '')}}</td>
             </tr>

             <tr>
                <td style="text-align: left;"><b>{{__('TAX')}}:</b></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($product['taxable_amount'], 2, '.', '')}}</td>
             </tr>

             <tr>
                <td style="text-align: left;"><b>{{__('SHIPPING Charge')}}:</b></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($product['delivery_fee_charges'], 2, '.', '')}}</td>
             </tr>

             <tr>
                <td style="text-align: left;"><b>{{__('Discount')}}:</b></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($product['discount_amount'], 2, '.', '')}}</td>
             </tr>
             
             <tr>
                <td style="text-align: left;"><b>{{__('Total')}}:</b></td>
                <td style="text-align: right;">{{$currencySymbol . number_format($product['payable_amount'], 2, '.', '')}}</td>
             </tr>
            
            
         </table>
         @endif
         @endforeach
     </td>
 </tr>