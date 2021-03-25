<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<!-- Your customer chat code -->
<div class="fb-customerchat" attribution=setup_tool page_id="2123438804574660" theme_color="#0084ff"
    logged_in_greeting="Hi! Welcome to PixelStrap Themes  How can we help you?"
    logged_out_greeting="Hi! Welcome to PixelStrap Themes  How can we help you?">
</div>
<!-- facebook chat section end -->


<!-- cart start -->
<div class="addcart_btm_popup" id="fixed_cart_icon">
    <a href="#" class="fixed_cart">
        <i class="ti-shopping-cart"></i>
    </a>
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
@endphp

<script src="https://maps.googleapis.com/maps/api/js?key={{$mapKey}}&v=3.exp&libraries=places,drawing"></script>
<!-- exitintent jquery-->

<script src="{{asset('front-assets/js/popper.min.js')}}"></script>
<script src="{{asset('front-assets/js/slick.js')}}"></script>
<script src="{{asset('front-assets/js/menu.js')}}"></script>
<script src="{{asset('front-assets/js/lazysizes.min.js')}}"></script>
<script src="{{asset('front-assets/js/bootstrap.js')}}"></script>
<script src="{{asset('front-assets/js/bootstrap-notify.min.js')}}"></script>

<script src="{{asset('front-assets/js/jquery.elevatezoom.js')}}"></script>
<script src="{{asset('front-assets/js/script.js')}}"></script>
<script>
    /*$(window).on('load', function () {
        setTimeout(function () {
            $('#exampleModal').modal('show');
        }, 2500);
    });*/

    $('.customerLang').click(function(){
        var changLang = $(this).attr('langId');
        //$('#cliLang').val(changLang);
        //$('#filterData').submit();
        settingData('language', changLang);
    });

    $('.customerCurr').click(function(){
        var changcurrId = $(this).attr('currId');
        var changSymbol = $(this).attr('currSymbol');
        settingData('currency', changcurrId, changSymbol);
        //$('#cliCur').val(changcurrId);
        //$('#filterData').submit();
    });

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

    function openSearch() {
        document.getElementById("search-overlay").style.display = "block";
    }

    function closeSearch() {
        document.getElementById("search-overlay").style.display = "none";
    }
</script>