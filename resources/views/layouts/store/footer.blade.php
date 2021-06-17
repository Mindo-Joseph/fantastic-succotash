<div class="tap-top top-cls">
    <div>
        <i class="fa fa-angle-double-up"></i>
    </div>
</div>
@php 
    $mapKey = '1234';
    if(isset(Session::get('preferences')->map_key) && !empty(Session::get('preferences')->map_key)){
        $mapKey = session('preferences')->map_key;
    }
    $webColor = '#ff4c3b';
    $darkMode = '';
    if(isset(Session::get('preferences')->theme_admin) && ucwords(session('preferences')->theme_admin) == 'Dark'){
        $darkMode = 'dark';
    }
@endphp
<script src="https://maps.googleapis.com/maps/api/js?key={{$mapKey}}&v=3.exp&libraries=places,drawing"></script>
<script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('front-assets/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('front-assets/js/popper.min.js')}}"></script>
<script src="{{asset('front-assets/js/slick.js')}}"></script>
<script src="{{asset('front-assets/js/menu.js')}}"></script>
<script src="{{asset('front-assets/js/lazysizes.min.js')}}"></script>
<script src="{{asset('front-assets/js/bootstrap.js')}}"></script>
<script src="{{asset('front-assets/js/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('front-assets/js/jquery.elevatezoom.js')}}"></script>
<script src="{{asset('front-assets/js/underscore.min.js')}}"></script>
<script src="{{asset('front-assets/js/sweetalert2.min.js')}}"></script>
<script src="{{asset('front-assets/js/script.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script src="{{asset('js/location.js')}}"></script>
<script type="text/javascript">
    var show_cart_url = "{{ route('showCart') }}";
    var home_page_url = "{{ route('userHome') }}";
    var cart_details_url = "{{ route('cartDetails') }}";
    var user_checkout_url= "{{ route('user.checkout') }}";
    var cart_product_url= "{{ route('getCartProducts') }}";
    var delete_cart_product_url= "{{ route('deleteCartProduct') }}";
    var delete_cart_url = "{{ route('emptyCartData') }}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
</script>
<script>
    var is_hyperlocal = 0;
    @if(Session::has('deliveryAddress'))
        let delivery_address = 1;
    @else
        let delivery_address = 0;
    @endif;
    @if( (Session::get('preferences')))
        @if(Session::get('preferences')->is_hyperlocal == 1) 
            let is_hyperlocal = 1;
        @else
            let is_hyperlocal = 0;
        @endif;
    @endif;
    let empty_cart_url = "{{route('emptyCartData')}}";
</script>