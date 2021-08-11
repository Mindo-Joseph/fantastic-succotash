@extends('layouts.store', ['title' => (!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->name : $category->slug])
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>

<section class="home-serivces">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-8 offset-md-2">
                {{-- <div class="step-indicator">
                    <div class="step step1 active">
                        <div class="step-icon">1</div>
                        <p>Service Details</p>
                    </div>
                    <div class="indicator-line active"></div>
                    <div class="step step2">
                        <div class="step-icon">2</div>
                        <p>Date & Time</p>
                    </div>
                    <div class="indicator-line"></div>
                    <div class="step step3">
                        <div class="step-icon">3</div>
                        <p>Payment</p>
                    </div>
                </div> --}}

                <div class="row mt-4">

                    <div class="col-md-8">
                        <div class="card-box">
                            <ul>

                                @if(!empty($category->childs) && count($category->childs) > 0)
                                    <li><a class="btn btn-solid" href="#">{{$cate['translation_name']}}</a></li>

                                @endif

                               
                            </ul>
                            
                            <div class="service-data-wrapper mb-5">
                               
                                <div class="service-data mt-4">
                                    <h4><b>{{ $category->translation_name }}</b></h4>

                                   
                                    @if(!empty($category->image))
                                    <div class="service-img mb-3">
                                        <img class="img-fluid" src="{{$category->image['proxy_url'] . '1000/200' . $category->image['image_path']}}" alt="">
                                    </div>
                                    @endif

                                    @if($listData->isNotEmpty())
                                    @foreach($listData as $key => $data)
                                    {{-- new product design  --}}
                                          <div class="row classes_wrapper no-gutters" href="#">                                       
                                        <div class="col-md-9 col-sm-8 pr-md-2">
                                            <h5 class="mb-1"><b>{!! $data->translation_title !!}</b></h5>
                                            <p class="mb-1">{!! $data->translation_description !!}</p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="my-sm-0 my-3">@if($data->inquiry_only == 0)
                                                    {{Session::get('currencySymbol').(number_format($data->variant_price * $data->variant_multiplier,2))}}
                                                @endif</h5>
                                                

                                                @if(isset($data->variant[0]->checkIfInCart) && count($data->variant[0]->checkIfInCart) > 0)
                                                <div class="number" id="show_plus_minus{{$data->id}}">
                                                    <span class="minus qty-minus-ondemand"  data-parent_div_id="show_plus_minus{{$data->id}}" data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </span>
                                                    <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="{{$data->variant[0]->checkIfInCart['0']['quantity']}}" class="input-number" step="0.01" id="quantity_ondemand_{{$data->variant[0]->checkIfInCart['0']['id']}}" readonly>
                                                    <span class="plus qty-plus-ondemand"  data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <a class="btn btn-solid add_on_demand" style="display:none;" id="add_button_href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">Add <i class="fa fa-plus"></i></a>
                                               
                                                @else
                                                <a class="btn btn-solid add_on_demand" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">Add <i class="fa fa-plus"></i></a>
                                                <div class="number" style="display:none;" id="show_plus_minus{{$data->id}}">
                                                    <span class="minus qty-minus-ondemand"  readonly data-id="" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </span>
                                                    <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d{{$data->id}}" readonly placeholder="1" type="text" value="1" class="input-number input_qty" step="0.01">
                                                    <span class="plus qty-plus-ondemand"  data-id="" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                
                                                @endif

                                                

                                              
                                                

                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-4 mb-sm-0 mb-3">
                                            <?php $imagePath = $imagePath2 = '';
                                                $mediaCount = count($data->media);
                                                for ($i = 0; $i < $mediaCount && $i < 2; $i++) { 
                                                    if($i == 0){
                                                        $imagePath = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                    }
                                                    $imagePath2 = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                } ?>
                                            <div class="class_img">
                                                <img src="{{$imagePath}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    

                                    {{-- <-- end new product design --> --}}
                                    @endforeach
                                  @else
                                    <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">No Product Found</h5></div>
                                  @endif

                                  

                                </div>
                               

                            </div>

                            <!-- Step Two Html -->
                            {{-- <h4 class="mb-2"><b>When would you like your service?</b></h4>
                            <div class="date-items radio-btns">
                                <div>
                                    <div class="radios">
                                        <p>Mon</p>
                                        <input type="radio" value='1' name='date-time' id='radio1'/>
                                        <label for='radio1'>
                                            <span class="customCheckbox" aria-hidden="true">9</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <div class="radios">
                                        <p>Tus</p>
                                        <input type="radio" value='1' name='date-time' id='radio2'/>
                                        <label for='radio2'><span class="customCheckbox" aria-hidden="true">10</span></label>
                                    </div>
                                </div>
                                <div>
                                    <div class="radios">
                                        <p>Wed</p>
                                        <input type="radio" value='1' name='date-time' id='radio3'/>
                                        <label for='radio3'><span class="customCheckbox" aria-hidden="true">11</span></label>
                                    </div>
                                </div>
                                <div>
                                    <div class="radios">
                                        <p>Thu</p>
                                        <input type="radio" value='1' name='date-time' id='radio4'/>
                                        <label for='radio4'><span class="customCheckbox" aria-hidden="true">12</span></label>
                                    </div>
                                </div>
                                <div>
                                    <div class="radios">
                                        <p>Fri</p>
                                        <input type="radio" value='1' name='date-time' id='radio5'/>
                                        <label for='radio5'><span class="customCheckbox" aria-hidden="true">13</span></label>
                                    </div>
                                </div>
                                <div>
                                    <div class="radios">
                                        <p>Sat</p>
                                        <input type="radio" value='1' name='date-time' id='radio6'/>
                                        <label for='radio6'><span class="customCheckbox" aria-hidden="true">14</span></label>
                                    </div>
                                </div>
                                <div>
                                    <div class="radios">
                                        <p>Sun</p>
                                        <input type="radio" value='1' name='date-time' id='radio8'/>
                                        <label for='radio8'><span class="customCheckbox" aria-hidden="true">15</span></label>
                                    </div>
                                </div>
                                <div>
                                    <div class="radios">
                                        <p>Sun</p>
                                        <input type="radio" value='1' name='date-time' id='radio9'/>
                                        <label for='radio9'><span class="customCheckbox" aria-hidden="true">15</span></label>
                                    </div>
                                </div>
                                <div>
                                    <div class="radios">
                                        <p>Sun</p>
                                        <input type="radio" value='1' name='date-time' id='radio10'/>
                                        <label for='radio10'><span class="customCheckbox" aria-hidden="true">15</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="booking-time-wrapper">
                                <h4 class="mt-4 mb-2"><b>When would you like your service?</b></h4>
                                <div class="booking-time radio-btns long-radio">
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='booking-radio' id='time1'/>
                                            <label for='time1'><span class="customCheckbox" aria-hidden="true">09:00 - 10:00</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='booking-radio' id='time2'/>
                                            <label for='time2'><span class="customCheckbox" aria-hidden="true">10:00 - 11:00</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='booking-radio' id='time3'/>
                                            <label for='time3'><span class="customCheckbox" aria-hidden="true">11:00 - 12:00</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='booking-radio' id='time4'/>
                                            <label for='time4'><span class="customCheckbox" aria-hidden="true">12:00 - 01:00</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='booking-radio' id='time5'/>
                                            <label for='time5'><span class="customCheckbox" aria-hidden="true">02:00 - 03:00</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='booking-radio' id='time6'/>
                                            <label for='time6'><span class="customCheckbox" aria-hidden="true">04:00 - 05:00</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='booking-radio' id='time7'/>
                                            <label for='time7'><span class="customCheckbox" aria-hidden="true">05:00 - 06:00</span></label>
                                        </div>
                                    </div>
                                </div>
                                <P>Your service will start between 09:00-10:00</P>
                            </div>

                            <div class="booking-time-wrapper">
                                <h4 class="mt-4 mb-2"><b>When would you like your service?</b></h4>
                                <textarea class="form-control" name="" id="" cols="30" rows="7"></textarea>
                            </div> --}}


                            <!-- Step Three Start From Here -->
                            {{-- <div class="step-three">
                                <h4 class="mt-4 mb-2"><b>How many hours do you need your professional to stay? <i class="fa fa-info-circle" aria-hidden="true"></i></b></h4>
                                <div class="hours-slot radio-btns">
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='hours-radio' id='h-1'/>
                                            <label for='h-1'><span class="customCheckbox" aria-hidden="true">1</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='hours-radio' id='h-2'/>
                                            <label for='h-2'><span class="customCheckbox" aria-hidden="true">2</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='hours-radio' id='h-3'/>
                                            <label for='h-3'><span class="customCheckbox" aria-hidden="true">3</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='hours-radio' id='h-4'/>
                                            <label for='h-4'><span class="customCheckbox" aria-hidden="true">4</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='hours-radio' id='h-5'/>
                                            <label for='h-5'><span class="customCheckbox" aria-hidden="true">5</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='hours-radio' id='h-6'/>
                                            <label for='h-6'><span class="customCheckbox" aria-hidden="true">6</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='hours-radio' id='h-7'/>
                                            <label for='h-7'><span class="customCheckbox" aria-hidden="true">7</span></label>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-2"><b>How many professionals do you need?</b></h4>
                                <div class="hours-slot radio-btns">
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='pro-radio' id='p-1'/>
                                            <label for='p-1'><span class="customCheckbox" aria-hidden="true">1</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='pro-radio' id='p-2'/>
                                            <label for='p-2'><span class="customCheckbox" aria-hidden="true">2</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='pro-radio' id='p-3'/>
                                            <label for='p-3'><span class="customCheckbox" aria-hidden="true">3</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='pro-radio' id='p-4'/>
                                            <label for='p-4'><span class="customCheckbox" aria-hidden="true">4</span></label>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="mt-4 mb-2"><b>Do you require cleaning materials? <i class="fa fa-info-circle" aria-hidden="true"></i></b></h4>
                                <div class="materials-slide radio-btns long-radio">
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='materials-radio' id='mat1'/>
                                            <label for='mat1'><span class="customCheckbox" aria-hidden="true">No, I have them</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="radios">
                                            <input type="radio" value='1' name='materials-radio' id='mat2'/>
                                            <label for='mat2'><span class="customCheckbox" aria-hidden="true">Yes, please</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="booking-time-wrapper">
                                <h4 class="mt-4 mb-2"><b>When would you like your service?</b> </h4>
                                <textarea class="form-control" name="" id="" cols="30" rows="7"></textarea>
                            </div>

                            <hr>
                            <div class="card-footer bg-transparent px-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="#"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                                    <button class="btn btn-solid">Next</button>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card-box p-2">
                            <div class="product-order">
                                <div class="total-sec border-0 py-0 my-0">
                                    <h5 class="d-flex align-items-center justify-content-between pb-2 border-bottom"><b>City</b><b>Dubai</b></h5>
                                    <h5 class="d-flex align-items-center justify-content-between pb-2">{{__('SERVICE DETAILS')}} </h5>
                                </div>
                                <div class="spinner-box">
                                    <div class="circle-border">
                                        <div class="circle-core"></div>
                                    </div>
                                </div>
                            
                                <script type="text/template" id="header_cart_template_ondemand">
                                        <% _.each(cart_details.products, function(product, key){%>
                                        <% _.each(product.vendor_products, function(vendor_product, vp){%>
                                            <li id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                                                <a class='media' href='<%= show_cart_url %>'>
                                                     <div class='media-body'>                                                                
                                                        <h6 class="d-flex align-items-center justify-content-between">
                                                            <span class="ellips"><%= vendor_product.quantity %>x <%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></span>
                                                            <span>{{Session::get('currencySymbol')}}<%= vendor_product.pvariant.price %></span>
                                                        </h6>
                                                    </div>
                                                </a>
                                                <div class='close-circle'>
                                                    <a  class="action-icon d-block mb-3 remove_product_via_cart" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </li>
                                        <% }); %>
                                        <% }); %>

                                        <h5 class="d-flex align-items-center justify-content-between pb-2">{{__('PRICE DETAILS')}} </h5>
                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Price')}}</span>
                                                    <span>{{Session::get('currencySymbol')}}<%= cart_details.gross_amount %></span>
                                                </h6>
                                            </div>
                                        </li>

                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Tax')}}</span>
                                                    <span>{{Session::get('currencySymbol')}}<%= cart_details.total_taxable_amount %></span>
                                                </h6>
                                            </div>
                                        </li>

                                        <% if(cart_details.loyalty_amount > 0) { %>
                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Loyalty Amount')}} </span>
                                                    <span>{{Session::get('currencySymbol')}}<%= cart_details.loyalty_amount %></span>
                                                </h6>
                                            </div>
                                        </li>
                                        <% } %>

                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Total')}}</span>
                                                    <span>{{Session::get('currencySymbol')}}<%= cart_details.total_payable_amount %></span>
                                                </h6>
                                            </div>
                                        </li>

                                       
                                        
                                 </script>
                                 <ul class="show-div shopping-cart" id="header_cart_main_ul_ondemand">
                                 </ul>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</section>

<!-- remove_item_modal -->
<div class="modal fade remove-item-modal" id="remove_item_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_itemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title" id="remove_itemLabel">{{__('Remove Item')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="vendor_id" value="">
                <input type="hidden" id="cartproduct_id" value="">
                <h6 class="m-0">{{__('Are You Sure You Want To Remove This Item?')}}</h6>
            </div>
            <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
                <button type="button" class="btn btn-solid" id="remove_product_button">{{__('Remove')}}</button>
            </div>
        </div>
    </div>
</div>
<!-- end remove_item_modal -->
@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var guest_cart = {{ $guest_user ? 1 : 0 }};
    var base_url = "{{url('/')}}";
    var place_order_url = "{{route('user.placeorder')}}";
    var payment_stripe_url = "{{route('payment.stripe')}}";
    var user_store_address_url = "{{route('address.store')}}";
    var promo_code_remove_url = "{{ route('remove.promocode') }}";
    var payment_paypal_url = "{{route('payment.paypalPurchase')}}";
    var update_qty_url = "{{ url('product/updateCartQuantity') }}";
    var promocode_list_url = "{{ route('verify.promocode.list') }}";
    var payment_option_list_url = "{{route('payment.option.list')}}";
    var apply_promocode_coupon_url = "{{ route('verify.promocode') }}";
    var payment_success_paypal_url = "{{route('payment.paypalCompletePurchase')}}";
</script>
@endsection