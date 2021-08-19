<div class="tap-top top-cls">
    <div>
        <i class="fa fa-angle-double-up"></i>
    </div>
</div>
@php 
    $mapKey = '1234';
    $theme = \App\Models\ClientPreference::where(['id' => 1])->first();
    if($theme && !empty($theme->map_key)){
        $mapKey = $theme->map_key;
    }
    $webColor = '#ff4c3b';
    $darkMode = '';
    if(isset(Session::get('preferences')->theme_admin) && ucwords(session('preferences')->theme_admin) == 'Dark'){
        $darkMode = 'dark';
    }
@endphp
<script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('front-assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
    var is_hyperlocal = 0;
    var selected_address = 0;
    var vendor_type = "delivery";
    @if(Session::has('vendorType'))
        vendor_type = "{{Session::get('vendorType')}}";
    @endif
    var autocomplete_url = "{{ route('autocomplete') }}";
    let stripe_publishable_key = '{{ $stripe_publishable_key }}';
    var login_url = "{{ route('customer.login') }}";
    var home_page_url = "{{ route('userHome') }}";
    let home_page_url2 = home_page_url.concat("/");
    var add_to_whishlist_url = "{{ route('addWishlist') }}";
    var show_cart_url = "{{ route('showCart') }}";
    var home_page_data_url = "{{ route('homePageData') }}";
    var client_preferences_url = "{{ route('getClientPreferences') }}";
    let empty_cart_url = "{{route('emptyCartData')}}";
    var cart_details_url = "{{ route('cartDetails') }}";
    var delete_cart_url = "{{ route('emptyCartData') }}";
    var user_checkout_url= "{{ route('user.checkout') }}";
    var cart_product_url= "{{ route('getCartProducts') }}";
    var delete_cart_product_url= "{{ route('deleteCartProduct') }}";
    var change_primary_data_url = "{{ route('changePrimaryData') }}";
    var url1 = "{{ route('config.update') }}";
    var url2 = "{{ route('config.get') }}";
    var featured_product_language = "{{ __('Featured Product') }}";
    var new_product_language = "{{ __('New Product') }}";
    var on_sale_product_language = "{{ __('On Sale') }}";
    var best_seller_product_language = "{{ __('Best Seller') }}";
    var vendor_language = "{{ __('Vendors') }}";
    var brand_language = "{{ __('Brands') }}";
    // if((home_page_url != window.location.href) && (home_page_url2 != window.location.href)){
    //     $('.vendor_mods').hide();}
    // else{
    //     $('.vendor_mods').show();}
        
    @if(Session::has('selectedAddress'))
        selected_address = 1;
    @endif
    @if( Session::has('preferences') )
        @if( (isset(Session::get('preferences')->is_hyperlocal)) && (Session::get('preferences')->is_hyperlocal == 1) ) 
            is_hyperlocal = 1;
        @endif;
    @endif;
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$mapKey}}&v=3.exp&libraries=places,drawing"></script>
<script src="{{asset('front-assets/js/popper.min.js')}}"></script>
<script src="{{asset('front-assets/js/slick.js')}}"></script>
<script src="{{asset('front-assets/js/menu.js')}}"></script>
<script src="{{asset('front-assets/js/lazysizes.min.js')}}"></script>
<script src="{{asset('front-assets/js/bootstrap.js')}}"></script>
<script src="{{asset('front-assets/js/jquery.elevatezoom.js')}}"></script>
<script src="{{asset('front-assets/js/underscore.min.js')}}"></script>
<script src="{{asset('front-assets/js/script.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script src="{{asset('js/location.js')}}"></script>
<script src="{{asset('js/cab_booking.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>

<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
