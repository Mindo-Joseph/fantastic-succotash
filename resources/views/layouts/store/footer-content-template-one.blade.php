@php
$clientData = \App\Models\Client::where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
$pages = \App\Models\Page::with(['translations' => function($q) {$q->where('language_id', session()->get('customerLanguage') ??1);}])->whereHas('translations', function($q) {$q->where(['is_published' => 1, 'language_id' => session()->get('customerLanguage') ??1]);})->get();
@endphp
<footer class="footer-light">
    <section class="section-b-space light-layout py-4">
        <div class="container">
            <div class="row footer-theme partition-f">
                <!-- <div class="col-lg-3 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start"> -->
                <div class="col-lg-3 col-md-6">
                    <div class="sub-title">
                        <div class="footer-title mt-0">
                            <h4 class="mt-0">Social Links</h4>
                        </div>
                        <div class="footer-contant">                    
                            <ul class="social-links">
                                @foreach($social_media_details as $social_media_detail)
                                    <li>
                                        <a href="{{http_check($social_media_detail->url)}}" target="_blank">
                                            <i class="fa fa-{{$social_media_detail->icon}}" aria-hidden="true"></i>
                                            <span>{{$social_media_detail->icon ? ucfirst($social_media_detail->icon) : "Facebook"}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                     <!-- <div class="footer-logo mb-0">
                            <a href="{{ route('userHome') }}">
                                <img class="img-fluid blur-up lazyload" src="{{$urlImg}}">
                            </a>
                        </div> -->
                </div>
                @if(count($pages))
                    <div class="col-lg-3 col-md-6 pl-lg-4">
                        <div class="sub-title">
                            <div class="footer-title mt-0">
                                <h4 class="mt-0">{{ __('Links') }}</h4>
                            </div>
                            <div class="footer-contant">
                                <ul>
                                    @foreach($pages as $page)
                                        <li>
                                            <a href="{{route('extrapage',['slug' => $page->slug])}}">{{$page->translations->first() ? $page->translations->first()->title : $page->primary->title}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($social_media_details))
                    <div class="col-lg-4 col-md-6 pl-lg-5  d-none">
                        <div class="sub-title">
                            <div class="footer-title mt-0">
                                <h4 class="mt-0">{{ __('Connect') }}</h4>
                            </div>
                            <div class="footer-contant">
                                <div class="footer-social">
                                    <ul>
                                        @foreach($social_media_details as $social_media_detail)
                                            <li class="d-block">
                                                <a href="{{http_check($social_media_detail->url)}}" target="_blank">
                                                    <i class="fa fa-{{$social_media_detail->icon}}" aria-hidden="true"></i>
                                                    <span>{{$social_media_detail->icon ? ucfirst($social_media_detail->icon) : "Facebook"}}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>  
                            </div>   
                        </div>   
                    </div>
                @endif
                @if($client_preference_detail->show_contact_us == 1)
                    <div class="col-lg-3 col-md-6 mb-md-0 mb-3 pl-xl-3">
                        <div class="sub-title">
                            <div class="footer-title mt-0">
                                <h4 class="mt-0">{{ __('Contact Us') }}</h4>
                            </div>
                            <div class="footer-contant">
                                <ul class="contact-list">
                                    <li class="pl-0"><i class="icon-location"></i> <span>{{$clientData ? $clientData->company_address : 'Demo Store, 345-659'}}</span></li>
                                    <li class="pl-0"><i class="icon-ic_call"></i> <a href="tel: {{$clientData ? $clientData->phone_number : '123-456-7898'}}"><span>{{$clientData ? $clientData->phone_number : '123-456-7898'}}</span></a></li>
                                    <li class="pl-0"><i class="icon-ic_mail"></i> <a href="mailto: {{$clientData ? $clientData->email : 'Support@Fiot.com'}}"><span>{{$clientData ? $clientData->email : 'Support@Fiot.com'}}</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif  
                <div class="col-lg-3 col-md-6 mb-md-0 mb-3 text-lg-right text-center">
                    <div class="store-btn">
                        <a href="#"><img src="{{asset('front-assets/images/app-store.png')}}" alt=""></a>
                        <a class="ml-2" href="#"><img src="{{asset('front-assets/images/google-play.png')}}" alt=""></a>
                    </div>
                </div>                
            </div>
        </div>
    </section>
    <div class="sub-footer">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="footer-end">
                        <p><i class="fa fa-copyright" aria-hidden="true"></i> 2020-21</p>
                    </div>
                </div>
                @if($client_preference_detail->show_payment_icons == 1)
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="payment-card-bottom">
                        <ul>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/visa.png')}}"></a>
                            </li>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/mastercard.png')}}"></a>
                            </li>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/paypal.png')}}"></a>
                            </li>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/american-express.png')}}"></a>
                            </li>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/discover.png')}}"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</footer>

<!-- <div class="sticky-bottom-cart d-sm-block d-none">
    <div class="container">
        <div class="cart-content">
            <div class="product-image">
                <img src="../assets/images/pro3/1.jpg" class="img-fluid" alt="">
                <div class="content d-lg-block d-none">
                    <h5>WOMEN PINK SHIRT</h5>
                    <h6>$32.96<del>$459.00</del><span>55% off</span></h6>
                </div>
            </div>
            <div class="selection-section">
                <div class="form-group mb-0">
                    <select id="inputState" class="form-control">
                        <option selected="">Choose color...</option>
                        <option>pink</option>
                        <option>blue</option>
                        <option>grey</option>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <select id="inputState" class="form-control">
                        <option selected="">Choose size...</option>
                        <option>small</option>
                        <option>medium</option>
                        <option>large</option>
                        <option>extra large</option>
                    </select>
                </div>
            </div>
            <div class="add-btn">
                <a data-bs-toggle="modal" data-bs-target="#addtocart" href="" class="btn btn-solid btn-sm">add to
                    cart</a>
            </div>
        </div>
    </div>
</div> -->


<div class="modal fade single-vendor-order-modal" id="single_vendor_order_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="s_vendor_remove_cartLabel" style="background-color: rgba(0,0,0,0.8);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="s_vendor_remove_cartLabel">{{__('Remove Cart')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="m-0">{{__('You can only buy products for single vendor. Do you want to remove all your cart products to continue?')}}</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
        <button type="button" class="btn btn-solid" id="single_vendor_remove_cart_btn" data-cart_id="">{{__('Remove')}}</button>
      </div>
    </div>
  </div>
</div>