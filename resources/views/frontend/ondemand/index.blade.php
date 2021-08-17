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
                <div class="step-indicator">
                    
                    <div class="step step1 @if(app('request')->input('step') >= '1' || empty(app('request')->input('step'))) active @endif">
                        <div class="step-icon">1</div>
                        <p>Service Details</p>
                    </div>

                    <div class="indicator-line  @if(app('request')->input('step') >= '1' || !empty(app('request')->input('step'))) active @endif"></div>

                    <div class="step step2  @if(app('request')->input('step') >= '2' || !empty(app('request')->input('step'))) active @endif">
                        <div class="step-icon">2</div>
                        <p>Date & Time</p>
                    </div>

                    <div class="indicator-line  @if(app('request')->input('step') >= '2' || !empty(app('request')->input('step'))) active @endif"></div>

                    <div class="step step3   @if(app('request')->input('step') >= '3' || !empty(app('request')->input('step'))) active @endif"">
                        <div class="step-icon">3</div>
                        <p>Payment</p>
                    </div>

                </div>

                <div class="row mt-4">

                    <div class="col-md-8">
                         <!-- Start Main Nav -->
                         <nav id='main-nav'>
                            <ul id='main-nav-list'>
                                <li>
                                    <a href='#main-header'>Apartment</a>
                                </li>
                                <li>
                                    <a href='#section01'>Villa</a>
                                </li>
                                <li>
                                    <a href='#section02'>Office</a>
                                </li>
                                <li>
                                    <a href='#section03'>Add-on</a>
                                </li>
                                <li>
                                    <a href='#section04'>Section 4</a>
                                </li>
                            </ul>
                        </nav>
                        <!-- End Main Nav -->
                        
                        <div class="card-box">
                            <ul>
                                 @if(!empty($category->childs) && count($category->childs) > 0)
                                    @foreach ($category->childs as $childs)
                                        <li><a class="btn btn-solid" href="#">{{ $childs['translation_name'] ?? ''}}</a></li>
                                    @endforeach
                                    
                                 @endif
                            </ul>

                            <!-- static html -->
                               
                                    <!-- Start Conent Wrapper -->
                                    <div id='main-wrapper'>
                                        <!-- Start Header -->
                                        <header class='wrapper' id='main-header'>
                                            <h1>Header</h1>
                                        </header>
                                        
                                        <!-- Start Section 01 -->
                                        <section class='wrapper' id='section01'>
                                            <h1>Section 1</h1>
                                        </section>
                                        
                                        <!-- Start Section 02 -->
                                        <section class='wrapper' id='section02'>
                                            <h1>Section 2</h1>
                                        </section>
                                        
                                        <!-- Start Section 03 -->
                                        <section class='wrapper' id='section03'>
                                            <h1>Section 3</h1>
                                        </section>
                                        
                                        <!-- Start Section 04 -->
                                        <section class='wrapper' id='section04'>
                                            <h1>Section 4</h1>
                                        </section>
                                        
                                    </div>
                                    <!-- End Content Wrapper -->
                            
                            <!-- end statis html -->
                            
                            @if(app('request')->input('step') == '1' || empty(app('request')->input('step')))
                            <div class="service-data-wrapper mb-5" id="step-1-ondemand">
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
                                                @php
                                                    $cartcount = 1;
                                                @endphp
                                                <a class="btn btn-solid add_on_demand" style="display:none;" id="add_button_href{{$data->variant[0]->checkIfInCart['0']['id']}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">Add <i class="fa fa-plus"></i></a>
                                                <div class="number" id="show_plus_minus{{$data->variant[0]->checkIfInCart['0']['id']}}">
                                                    <span class="minus qty-minus-ondemand"  data-parent_div_id="show_plus_minus{{$data->variant[0]->checkIfInCart['0']['id']}}" data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </span>
                                                    <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="{{$data->variant[0]->checkIfInCart['0']['quantity']}}" class="input-number" step="0.01" id="quantity_ondemand_{{$data->variant[0]->checkIfInCart['0']['id']}}" readonly>
                                                    <span class="plus qty-plus-ondemand"  data-id="{{$data->variant[0]->checkIfInCart['0']['id']}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                @else
                                                <a class="btn btn-solid add_on_demand" id="aadd_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">Add <i class="fa fa-plus"></i></a>
                                                <div class="number" style="display:none;" id="ashow_plus_minus{{$data->id}}">
                                                    <span class="minus qty-minus-ondemand"  data-parent_div_id="show_plus_minus{{$data->id}}" readonly data-id="{{$data->id}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
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
                                    

                                  
                                    @endforeach
                                  @else
                                    <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">No Product Found</h5></div>
                                  @endif

                                  

                                </div>
                            </div>
                            <a href="?step=2" id="next-button-ondemand-2" style="display: none;"><span class="btn btn-solid">Next</span></a>
                            @endif
                            

                            
                           
                             <!-- Step Two Html -->
                           
                            @if(app('request')->input('step') == '2')
                            <div id="step-2-ondemand">
                                <h4 class="mb-2"><b>When would you like your service?</b></h4>
                                <div class="date-items radio-btns">
                                    
                                    @foreach ($period as $key => $date)
                                        <div>
                                            <div class="radios">
                                                <p>{{date('D', strtotime($date))}}</p>
                                                <input type="radio" class="check-time-slots" value='{{date('Y-m-d', strtotime($date))}}' name='booking_date' id='radio{{$key}}' @if($key == 0) checked @endif/>
                                                <label for='radio{{$key}}'>
                                                    <span class="customCheckbox" aria-hidden="true">{{date('d', strtotime($date))}}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                   
                                </div>

                                <div class="booking-time-wrapper" id="show-all-time-slots" style="display: none;">
                                    <h4 class="mt-4 mb-2"><b>What time would you like us to start?</b></h4>
                                    <div class="booking-time radio-btns long-radio">
                                        @foreach ($time_slots as $key => $date)
                                        @if($key+1 < count($time_slots))
                                        <div>
                                            <div class="radios">
                                                <input type="radio" value='{{$date}}'  name='booking_time' id='time{{$key+1}}'/>
                                                <label for='time{{$key+1}}'><span class="customCheckbox selected-time" aria-hidden="true">{{$date}} - {{@$time_slots[$key+1]}}</span></label>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    <P id="message_of_time"></P>
                                </div>

                                <div class="booking-time-wrapper">
                                    <h4 class="mt-4 mb-2"><b>When would you like your service?</b></h4>
                                    <textarea class="form-control" name="" id="" cols="30" rows="7"></textarea>
                                </div> 
                            </div>
                            <a href="?step=2"><span class="btn btn-solid"><</span></a>
                            <a href="?step=3" id="next-button-ondemand-3" style="display: none;"><span class="btn btn-solid">Continue</span></a>
                            @endif
                            <!--end step 2 html -->



                            @if(app('request')->input('step') == '3')
                            <!-- step 3 payment page -->
                            <form method="post" action="" id="placeorder_form_ondemand">
                                    @csrf
                                    <div class="card-box">
                                        <div class="row d-flex justify-space-around">
                                            @if(!$guest_user)
                                                <div class="col-lg-8 left_box">
                                                    
                                                </div>
                                            @endif
                                           
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-sm-6 text-md-right">
                                                <button id="order_placed_btn" class="btn btn-solid d-none" type="button" {{$addresses->count() == 0 ? 'disabled': ''}}>{{__('Continue')}}</button>
                                            </div>
                                        </div>
                                    </div>

                                </form>

                                <div class="col-sm-6 text-md-right">
                                    <button id="order_placed_btn" class="btn btn-solid d-none" type="button" {{$addresses->count() == 0 ? 'disabled': ''}}>{{__('Continue')}}</button>
                                </div>
                               
                            @endif    
                            <!-- end step 3 payment page -->
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
                    
                        <div class="footer-card">
                            <a href="#" id="next-button-ondemand-2"><span class="btn btn-solid">Next</span></a> 
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
                                            <li>
                                                <h6 class="d-flex align-items-center justify-content-between"> <%= product.vendor.name %> </h6>
                                            </li>

                                            <% if( (product.isDeliverable != undefined) && (product.isDeliverable == 0) ) { %>
                                                <li class="border_0">
                                                    <th colspan="7">
                                                        <div class="text-danger">
                                                            Products for this vendor are not deliverable at your area. Please change address or remove product.
                                                        </div>
                                                    </th>
                                                </li>
                                                <% } %>
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

                                        <h5 class="d-flex align-items-center justify-content-between pb-2">{{__('DATE & TIME')}} </h5>
                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Date')}}</span>
                                                    <span id="show_date">--</span>
                                                </h6>
                                            </div>
                                        </li>
                                        <li>
                                            <div class='media-body'>                                                                
                                                <h6 class="d-flex align-items-center justify-content-between">
                                                    <span class="ellips">{{__('Start Time')}}</span>
                                                    <span id="show_time">--</span>
                                                </h6>
                                            </div>
                                        </li>


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

<!-- payment section ----->

<script type="text/template" id="payment_method_template">
    <% _.each(payment_options, function(payment_option, k){%>
        <a class="nav-link <%= payment_option.slug == 'cash_on_delivery' ? 'active': ''%>" id="v-pills-<%= payment_option.slug %>-tab" data-toggle="pill" href="#v-pills-<%= payment_option.slug %>" role="tab" aria-controls="v-pills-wallet" aria-selected="true" data-payment_option_id="<%= payment_option.id %>"><%= payment_option.title %></a>
    <% }); %>
</script>
<script type="text/template" id="payment_method_tab_pane_template">
    <% _.each(payment_options, function(payment_option, k){%>
        <div class="tab-pane fade <%= payment_option.slug == 'cash_on_delivery' ? 'active show': ''%>" id="v-pills-<%= payment_option.slug %>" role="tabpanel" aria-labelledby="v-pills-<%= payment_option.slug %>-tab">
            <form method="POST" id="<%= payment_option.slug %>-payment-form">
            @csrf
            @method('POST')
                <div class="payment_response mb-3">
                    <div class="alert p-0" role="alert"></div>
                </div>
                <div class="form_fields">
                    <div class="row">
                        <div class="col-md-12">
                            <% if(payment_option.slug == 'stripe') { %>
                                <div class="form-control">
                                    <label class="d-flex flex-row pt-1 pb-1 mb-0">
                                        <div id="stripe-card-element"></div>
                                    </label>
                                </div>
                                <span class="error text-danger" id="stripe_card_error"></span>
                            <% } %>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-md-right">
                            <button type="button" class="btn btn-solid" data-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="button" class="btn btn-solid ml-1 proceed_to_pay">{{__('Place Order')}}</button>
                            <!-- <button type="button" class="btn btn-solid ml-1 proceed_to_pay">Scheduled Now</button> -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <% }); %>
</script>
<div class="modal fade" id="proceed_to_pay_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="pay-billLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row no-gutters">
                    <div class="col-4">
                        <div class="nav flex-column nav-pills" id="v_pills_tab" role="tablist" aria-orientation="vertical"></div>
                    </div>
                    <div class="col-8">
                        <div class="tab-content-box px-3">
                            <div class="d-flex align-items-center justify-content-between pt-3">
                                <h5 class="modal-title" id="pay-billLabel">{{__('Total Amount')}}: <span id="total_amt"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="tab-content h-100" id="v_pills_tabContent">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!----- end payment section ------------->
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
    var getTimeSlotsForOndemand = "{{route('getTimeSlotsForOndemand')}}";

    $(document).on('click', '.showMapHeader', function(){
        var lats = document.getElementById('latitude').value;
        var lngs = document.getElementById('longitude').value;

        var myLatlng = new google.maps.LatLng(lats, lngs);
        var mapProp = {
            center:myLatlng,
            zoom:13,
            mapTypeId:google.maps.MapTypeId.ROADMAP
            
        };
        var map=new google.maps.Map(document.getElementById("pick-address-map"), mapProp);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            draggable:true  
        });
        // marker drag event
        google.maps.event.addListener(marker,'drag',function(event) {
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });
        //marker drag event end
        google.maps.event.addListener(marker,'dragend',function(event) {
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });
        $('#pick_address').modal('show');
    });
    
</script>

<script>
      $(document).ready(function() {
        
        $('#main-nav-list').onePageNav({
          scrollThreshold: 0.2, // Adjust if Navigation highlights too early or too late
          scrollOffset: 75 //Height of Navigation Bar
        });
        
        // Sticky Header - http://jqueryfordesigners.com/fixed-floating-elements/         
        var top = $('#main-nav').offset().top - parseFloat($('#main-nav').css('margin-top').replace(/auto/, 0));
        
        $(window).scroll(function (event) {
          // what the y position of the scroll is
          var y = $(this).scrollTop();
          
          // whether that's below the form
          if (y >= top) {
            // if so, ad the fixed class
            $('#main-nav').addClass('fixed');
          } else {
            // otherwise remove it
            $('#main-nav').removeClass('fixed');
          }
        });
        
      });
      
      ;(function($, window, document, undefined){
      
        // our plugin constructor
        var OnePageNav = function(elem, options){
          this.elem = elem;
          this.$elem = $(elem);
          this.options = options;
          this.metadata = this.$elem.data('plugin-options');
          this.$nav = this.$elem.find('a');
          this.$win = $(window);
          this.sections = {};
          this.didScroll = false;
          this.$doc = $(document);
          this.docHeight = this.$doc.height();
        };
      
        // the plugin prototype
        OnePageNav.prototype = {
          defaults: {
            currentClass: 'current',
            changeHash: false,
            easing: 'swing',
            filter: '',
            scrollSpeed: 250,
            scrollOffset: 0,
            scrollThreshold: 0.5,
            begin: false,
            end: false,
            scrollChange: false
          },
      
          init: function() {
            var self = this;
            
            // Introduce defaults that can be extended either
            // globally or using an object literal.
            self.config = $.extend({}, self.defaults, self.options, self.metadata);
            
            //Filter any links out of the nav
            if(self.config.filter !== '') {
              self.$nav = self.$nav.filter(self.config.filter);
            }
            
            //Handle clicks on the nav
            self.$nav.on('click.onePageNav', $.proxy(self.handleClick, self));
      
            //Get the section positions
            self.getPositions();
            
            //Handle scroll changes
            self.bindInterval();
            
            //Update the positions on resize too
            self.$win.on('resize.onePageNav', $.proxy(self.getPositions, self));
      
            return this;
          },
          
          adjustNav: function(self, $parent) {
            self.$elem.find('.' + self.config.currentClass).removeClass(self.config.currentClass);
            $parent.addClass(self.config.currentClass);
          },
          
          bindInterval: function() {
            var self = this;
            var docHeight;
            
            self.$win.on('scroll.onePageNav', function() {
              self.didScroll = true;
            });
            
            self.t = setInterval(function() {
              docHeight = self.$doc.height();
              
              //If it was scrolled
              if(self.didScroll) {
                self.didScroll = false;
                self.scrollChange();
              }
              
              //If the document height changes
              if(docHeight !== self.docHeight) {
                self.docHeight = docHeight;
                self.getPositions();
              }
            }, 250);
          },
          
          getHash: function($link) {
            return $link.attr('href').split('#')[1];
          },
          
          getPositions: function() {
            var self = this;
            var linkHref;
            var topPos;
            var $target;
            
            self.$nav.each(function() {
              linkHref = self.getHash($(this));
              $target = $('#' + linkHref);
      
              if($target.length) {
                topPos = $target.offset().top;
                self.sections[linkHref] = Math.round(topPos) - self.config.scrollOffset;
              }
            });
          },
          
          getSection: function(windowPos) {
            var returnValue = null;
            var windowHeight = Math.round(this.$win.height() * this.config.scrollThreshold);
      
            for(var section in this.sections) {
              if((this.sections[section] - windowHeight) < windowPos) {
                returnValue = section;
              }
            }
            
            return returnValue;
          },
          
          handleClick: function(e) {
            var self = this;
            var $link = $(e.currentTarget);
            var $parent = $link.parent();
            var newLoc = '#' + self.getHash($link);
            
            if(!$parent.hasClass(self.config.currentClass)) {
              //Start callback
              if(self.config.begin) {
                self.config.begin();
              }
              
              //Change the highlighted nav item
              self.adjustNav(self, $parent);
              
              //Removing the auto-adjust on scroll
              self.unbindInterval();
              
              //Scroll to the correct position
              $.scrollTo(newLoc, self.config.scrollSpeed, {
                axis: 'y',
                easing: self.config.easing,
                offset: {
                  top: -self.config.scrollOffset
                },
                onAfter: function() {
                  //Do we need to change the hash?
                  if(self.config.changeHash) {
                    window.location.hash = newLoc;
                  }
                  
                  //Add the auto-adjust on scroll back in
                  self.bindInterval();
                  
                  //End callback
                  if(self.config.end) {
                    self.config.end();
                  }
                }
              });
            }
      
            e.preventDefault();
          },
          
          scrollChange: function() {
            var windowTop = this.$win.scrollTop();
            var position = this.getSection(windowTop);
            var $parent;
            
            //If the position is set
            if(position !== null) {
              $parent = this.$elem.find('a[href$="#' + position + '"]').parent();
              
              //If it's not already the current section
              if(!$parent.hasClass(this.config.currentClass)) {
                //Change the highlighted nav item
                this.adjustNav(this, $parent);
                
                //If there is a scrollChange callback
                if(this.config.scrollChange) {
                  this.config.scrollChange($parent);
                }
              }
            }
          },
          
          unbindInterval: function() {
            clearInterval(this.t);
            this.$win.unbind('scroll.onePageNav');
          }
        };
      
        OnePageNav.defaults = OnePageNav.prototype.defaults;
      
        $.fn.onePageNav = function(options) {
          return this.each(function() {
            new OnePageNav(this, options).init();
          });
        };
        
      })( jQuery, window , document );
    </script>
@endsection