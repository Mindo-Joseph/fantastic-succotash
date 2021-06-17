<footer class="footer-light">
    <div class="light-layout bg-orange d-none">
        <div class="container">
            <section class="small-section border-section border-top-0">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="subscribe">
                            <div>
                                <h4 class="text-white">KNOW IT ALL FIRST!</h4>
                                <p class="text-white">Never Miss Anything From Us By Signing Up To Our Newsletter.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <form class="form-inline subscribe-form auth-form needs-validation"
                            action="https://pixelstrap.us19.list-manage.com/subscribe/post?u=5a128856334b598b395f1fc9b&amp;id=082f74cbda" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank">
                            <div class="form-group mx-sm-3">
                                <input type="text" class="form-control" name="EMAIL" id="mce-EMAIL"
                                    placeholder="Enter your email" required="required">
                            </div>
                            <button type="submit" class="btn btn-solid" id="mc-submit">subscribe</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <section class="section-b-space light-layout py-4">
        <div class="container">
            <div class="row footer-theme partition-f">
                <div class="col-lg-2 col-md-6 mb-md-0 mb-3">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>my account</h4>
                        </div>
                        <div class="footer-contant">
                            <ul>
                                <li><a href="#">mens</a></li>
                                <li><a href="#">womens</a></li>
                                <li><a href="#">clothing</a></li>
                                <li><a href="#">accessories</a></li>
                                <li><a href="#">featured</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-md-0 mb-3 pl-lg-5">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>why we choose</h4>
                        </div>
                        <div class="footer-contant">
                            <ul>
                                <li><a href="#">shipping & return</a></li>
                                <li><a href="#">secure shopping</a></li>
                                <li><a href="#">gallary</a></li>
                                <li><a href="#">affiliates</a></li>
                                <li><a href="#">contacts</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-md-0 mb-3 pl-lg-5">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Connect</h4>
                        </div>
                    </div>
                    <div class="footer-contant">
                        <div class="footer-social">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-md-0 mb-3">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Subscribe to our Newsletter</h4>
                        </div>
                    </div>
                    <div class="footer-contant">
                        <ul>
                            <li>
                                <a href="#"><span></span></a>
                            </li>
                            <li>
                                <a href="#"><span></span></a>
                            </li>
                            <li>
                                <a href="#"><span></span></a>
                            </li>
                            <li>
                                <a href="#"><span></span></a>
                            </li>
                        </ul>
                    </div>
                    <!-- <form class="card bg-transparent">
                        <div class="card-body row no-gutters align-items-center p-0">
                            <div class="col">
                                <input class="form-control bg-transparent border-0" type="search" placeholder="Your Emaill">
                            </div>
                            <div class="col-auto">
                                <button class="btn submit_btn" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                    <h6 class="mt-3"><a class="" href="blog.html">Check Out Our Blog</a></h6> -->
                </div>
            </div>
        </div>
    </section>
    <div class="sub-footer">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="footer-end">
                        <p><i class="fa fa-copyright" aria-hidden="true"></i> 2021-2022 Royoorders</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<script type="text/javascript">
    var show_cart_url = "{{ route('showCart') }}";
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