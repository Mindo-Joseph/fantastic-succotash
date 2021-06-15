<div class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="header-contact">
                    <ul>
                        <li>Welcome to Our store {{session('client_config')->company_name}}</li>
                        <li><i class="fa fa-phone" aria-hidden="true"></i>Call Us: {{session('client_config')->phone_number}}</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 header-contact text-center">
                <!-- <ul>
                  <li class="text-center"><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i></a></li>
                </ul> -->
            </div>
            <div class="col-lg-5 text-right">
                <ul class="header-dropdown">
                    <li class="mobile-wishlist"><a href="#"><i class="fa fa-heart" aria-hidden="true"></i></a>
                    </li>
                    <li class="onhover-dropdown mobile-account"> <i class="fa fa-user" aria-hidden="true"></i>
                        Account
                        <ul class="onhover-show-div">
                            <li><a href="{{route('customer.login')}}" data-lng="en">Login</a></li>
                            <li><a href="{{route('customer.register')}}" data-lng="es">Register</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>