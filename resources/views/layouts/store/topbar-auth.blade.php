<div class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="header-contact">
                    <ul>
                        <li>Welcome to Our store {{session('client_config')->company_name}}</li>
                        <li><i class="fa fa-phone" aria-hidden="true"></i>Call Us: {{session('client_config')->phone_number}}</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-5 text-right">
                <ul class="header-dropdown">
                    <li class="onhover-dropdown change-language">
                        <a href="#"><i class="fa fa-language" aria-hidden="true"></i><!--span>Change Language</span--> </a>
                        <ul class="onhover-show-div">                            
                        </ul>
                    </li>
                    <li class="onhover-dropdown change-currency">
                        <a href="#"><i class="fa fa-money" aria-hidden="true"></i><!--span>Change Currency</span--></a>
                        <ul class="onhover-show-div">
                        </ul>
                    </li>
                    <li class="onhover-dropdown mobile-account"> <i class="fa fa-user" aria-hidden="true"></i>
                        My Account
                        <ul class="onhover-show-div">
                            @if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1)
                            <li><a href="{{route('client.dashboard')}}" data-lng="en">Dashboard</a></li>
                            @endif
                            <li><a href="{{route('user.profile')}}" data-lng="en">Profile</a></li>
                            <li><a href="{{route('user.logout')}}" data-lng="es">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>