 <tr>
     <td>
         @foreach($cartData->products as $product)
         <h4>Vendor: {{$product['vendor']['name']}}</h4>
         <table class="order-detail" border="0" cellpadding="0" cellspacing="0" align="left" style="width: 100%;    margin-bottom: 20px;">
             <tr align="left">
                 <th>PRODUCT</th>
                 <th style="padding-left: 15px;">DESCRIPTION</th>
                 <th>QUANTITY</th>
                 <th>PRICE </th>
             </tr>
             @php
             $total_products = 0;
             @endphp
             @foreach($product['vendor_products'] as $vendor_product)
             <tr>
                 <td>
                    <div style="padding:5px"><img src="{{$vendor_product['product']['media'][0]['image']['path']['image_fit']}}100/100{{$vendor_product['product']['media'][0]['image']['path']['image_path']}}" alt="" width="80"></div>
                 </td>
                 <td valign="top" style="padding-left: 15px;">
                     <h5 style="margin-top: 15px;">{{$vendor_product['product']['translation_one']['title']}}</h5>
                 </td>
                 <td valign="top" style="padding-left: 15px;">
                     <h5 style="font-size: 14px; color:#444;margin-top: 10px;">QTY : <span>{{$vendor_product['quantity']}}</span></h5>
                 </td>
                 <td valign="top" align="right" style="padding-left: 15px;">
                     <h5 style="font-size: 14px; color:#444;margin-top:15px"><b>${{number_format($vendor_product['pvariant']['price'], 2, '.', '')}}</b></h5>
                     @php
                     $total_products += number_format($vendor_product['pvariant']['price'], 2, '.', '');
                     @endphp
                 </td>
             </tr>
             @endforeach
             <tr class="pad-left-right-space ">
                 <td class="m-t-5" colspan="2" align="left">
                     <p style="font-size: 14px;">Subtotal : </p>
                 </td>
                 <td class="m-t-5" colspan="2" align="right">
                     <b style>${{$total_products}}</b>
                 </td>
             </tr>
             <tr class="pad-left-right-space">
                 <td colspan="2" align="left">
                     <p style="font-size: 14px;">TAX :</p>
                 </td>
                 <td colspan="2" align="right">
                     <b>${{$product['taxable_amount']}}</b>
                 </td>
             </tr>
             <tr class="pad-left-right-space">
                 <td colspan="2" align="left">
                     <p style="font-size: 14px;">SHIPPING Charge :</p>
                 </td>
                 <td colspan="2" align="right">
                     <b>${{$product['delivery_fee_charges']}}</b>
                 </td>
             </tr>
             <tr class="pad-left-right-space">
                 <td colspan="2" align="left">
                     <p style="font-size: 14px;">Discount :</p>
                 </td>
                 <td colspan="2" align="right">
                     <b> ${{$product['discount_amount']}}</b>
                 </td>
             </tr>
             <tr class="pad-left-right-space ">
                 <td class="m-b-5" colspan="2" align="left">
                     <p style="font-size: 14px;">Total :</p>
                 </td>
                 <td class="m-b-5" colspan="2" align="right">
                     <b>${{$product['payable_amount']}}</b>
                 </td>
             </tr>
         </table>
         @endforeach
     </td>
 </tr>