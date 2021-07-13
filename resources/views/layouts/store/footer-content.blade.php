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
            <div class="row footer-theme partition-f align-items-center">
                <div class="col-lg-2 col-md-6 mb-md-0 mb-3">
                    <div class="footer-logo mb-0">
                        <a href="{{ route('userHome') }}"><img class="img-fluid blur-up lazyload" alt="" src="https://imgproxy.royoorders.com/insecure/fit/200/80/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/Clientlogo/60c730778af50.png" ></a>
                    </div>
                </div>
                @if(count($pages))
                <div class="col-lg-3 col-md-6 mb-md-0 mb-3 pl-lg-5">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Links</h4>
                        </div>
                        <div class="footer-contant">
                            <ul>
                                @foreach($pages as $page)
                                    <li><a href="{{route('extrapage',['slug' => $page->slug])}}">{{$page->primary->title}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                @if(count($social_media_details))
                <div class="col-lg-4 col-md-6 mb-md-0 mb-3 pl-lg-5">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Connect</h4>
                        </div>
                    </div>
                    <div class="footer-contant">
                        <div class="footer-social">
                            <ul>
                                @foreach($social_media_details as $social_media_detail)
                                <li>
                                    <a href="{{$social_media_detail->url}}" target="_blank">
                                        <i class="fa fa-{{$social_media_detail->icon}}" aria-hidden="true"></i>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-lg-3 col-md-6 mb-md-0 mb-3">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Contact Us</h4>
                        </div>
                    <div class="footer-contant">
                        <ul class="contact_link">
                            <li>
                                <!-- <span class="lft-icon"><img src="{{asset('front-assets/images/icon/address.png')}}" class="img-fluid mr-1" alt=""></span> -->
                                <i class="fa fa-home"></i>
                                <?=  $client ? $client->company_address : '' ?? 'Level- 26, Dubai World Trade Centre Tower,
                                Sheikh Rashid Tower, Sheikh Zayed Rd, Dubai, UAE' ?>
                            </li>
                            <li>
                                <a href="tel:+234-803-531-4802"><i class="fa fa-phone mr-1" aria-hidden="true"></i><span><?=  $client ? $client->phone_number : '' ?? '+234-803-531-4802' ?></span></a>
                            </li>
                            <li>
                                <a href="mailto:info@pearwheel.com"><i class="fa fa-envelope-o mr-1" aria-hidden="true"></i><span><?=  $client ? $client->email : '' ?? 'info@pearwheel.com' ?></span></a>
                            </li>
                            <li>
                                <a href="#"><span></span></a>
                            </li>
                        </ul>
                    </div>
                    
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
                <div class="col-sm-12 text-center">
                    <div class="footer-end">
                        <p><i class="fa fa-copyright" aria-hidden="true"></i> 2021-2022</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>