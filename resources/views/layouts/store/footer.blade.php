<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-customerchat" attribution=setup_tool page_id="2123438804574660" theme_color="#0084ff"
    logged_in_greeting="Hi! Welcome to PixelStrap Themes  How can we help you?"
    logged_out_greeting="Hi! Welcome to PixelStrap Themes  How can we help you?">
</div>
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
<style type="text/css">
    .dark-light-btn, #setting-icon{
        display: none;
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key={{$mapKey}}&v=3.exp&libraries=places,drawing"></script>
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
<script>
    /*$(window).on('load', function () {
        setTimeout(function () {
            $('#exampleModal').modal('show');
        }, 2500);
    });*/
    @if(Session::has('deliveryAddress'))
        let delivery_address = 1;
    @else
        let delivery_address = 0;
    @endif;

    @if( (Session::has('preferences')) && (Session::get('preferences')->is_hyperlocal == 1) )
        let is_hyperlocal = 1;
    @else
        let is_hyperlocal = 0;
    @endif;
    
    let empty_cart_url = "{{route('emptyCartData')}}";

    $('.customerLang').click(function(){
        var changLang = $(this).attr('langId');
        settingData('language', changLang);
    });

    $('.customerCurr').click(function(){
        var changcurrId = $(this).attr('currId');
        var changSymbol = $(this).attr('currSymbol');
        settingData('currency', changcurrId, changSymbol);
    });

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        console.log(charCode);
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
        }
        return true;
    }

    function settingData(type = '', v1 = '', v2 = '') {
        
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('changePrimaryData') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "type": type,
                "value1": v1, 
                "value2": v2
            },
            success: function(response) {
                location.reload();
            },
            error: function (data) {
                location.reload();
            },
        });
    }

    $('.customerPaginate').change(function(){
        var perPage = $('.customerPaginate option:selected').val();
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('changePaginate') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "itemPerPage": perPage,
            },
            success: function(response) {
                location.reload();
            },
            error: function (data) {
                location.reload();
            },
        });
    });

    $('.addWishList').click(function(){
        var sku = $(this).attr('proSku');
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('addWishlist') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "sku": sku
            },
            success: function(response) {

            },
            error: function (data) {
                //location.reload();
            },
        });
    });
    function openSearch() {
        document.getElementById("search-overlay").style.display = "block";
    }
    function closeSearch() {
        document.getElementById("search-overlay").style.display = "none";
    }
    $('document').ready(function(){
        var color_picker1 = '{{$webColor}}';
        document.documentElement.style.setProperty('--theme-deafult', color_picker1);
        $('body').addClass("{{$darkMode}}");
    })

    function emptyCart(){
        
    }

</script>