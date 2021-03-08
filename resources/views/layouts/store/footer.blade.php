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

<!-- exitintent jquery-->
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/exit.js')}}"></script>
<script src="{{asset('front-assets/js/popper.min.js')}}"></script>
<script src="{{asset('front-assets/js/slick.js')}}"></script>
<script src="{{asset('front-assets/js/menu.js')}}"></script>
<script src="{{asset('front-assets/js/lazysizes.min.js')}}"></script>
<script src="{{asset('front-assets/js/bootstrap.js')}}"></script>
<script src="{{asset('front-assets/js/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script src="{{asset('front-assets/js/script.js')}}"></script>

<script>
    $(window).on('load', function () {
        setTimeout(function () {
            $('#exampleModal').modal('show');
        }, 2500);
    });
    function openSearch() {
        document.getElementById("search-overlay").style.display = "block";
    }

    function closeSearch() {
        document.getElementById("search-overlay").style.display = "none";
    }
</script>