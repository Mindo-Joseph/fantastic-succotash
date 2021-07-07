<div class="left-side-menu">
    <div class="logo-box m-hide d-lg-block">
        @php
            $urlImg = URL::to('/').'/assets/images/users/user-1.jpg';
            $clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
            if($clientData){
                $urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
            }
        @endphp
        <a href="{{route('client.dashboard')}}" class="logo logo-dark text-center">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="20">
            </span>
        </a>
        
        <a href="{{route('client.dashboard')}}" class="logo logo-light text-center">
            <span class="logo-sm">
                <img src="{{$urlImg}}"
                    alt="" height="30" style="padding-top: 4px;">
            </span>
            <span class="logo-lg">
                <img src="{{$urlImg}}"
                    alt="" height="50" style="padding-top: 4px;">
            </span>
        </a>
    </div>
    <div class="h-100" data-simplebar>
        <div class="user-box text-center">
            <img src="{{asset('assets/images/users/user-1.jpg')}}" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block" data-toggle="dropdown">User</a>
                <div class="dropdown-menu user-pro-dropdown">
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user mr-1"></i>
                        <span>My Account</span>
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out mr-1"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div>
        <div id="sidebar-menu">
            <?php
            $allowed = [];
            if (Auth::user()->is_superadmin == 0) {
                foreach (Auth::user()->getAllPermissions as $key => $value) {
                    array_push($allowed, $value->permission->name);
                }
            } else {
                array_push($allowed, '99999');
            }
            ?>
            <ul id="side-menu">
                <li>
                    <a href="#sidebarorders" data-toggle="collapse">
                        <span class="icon-dashboard_icon"></span>
                        <span>ORDERS</span>
                    </a>
                    <div class="collapse" id="sidebarorders">
                        <ul class="nav-second-level">
                            @if(in_array('DASHBOARD',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('client.dashboard')}}">
                                        <span class="icon-dashboard_icon"></span>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('ORDERS',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('order.index')}}">
                                        <span class="icon-order_icon"></span>
                                        <span> Orders </span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('VENDORS',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('vendor.index')}}">
                                        <span class="icon-vendor_icon"></span>
                                        <span> Vendors </span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#sidebaraccounting" data-toggle="collapse">
                                    <span class="icon-accounting-icon size-20"></span>
                                    <span> Accounting </span>
                                </a>
                                <div class="collapse" id="sidebaraccounting">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{route('account.orders')}}">Orders</a>
                                        </li>
                                        <li>
                                            <a href="{{route('account.loyalty')}}">Loyality</a>
                                        </li>
                                        <li>
                                            <a href="{{route('account.promo.code')}}">Promo Codes</a>
                                        </li>
                                        <li>
                                            <a href="{{route('account.tax')}}">Taxes</a>
                                        </li>
                                        <li>
                                            <a href="{{route('account.vendor')}}">Vendors</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#sidebarsubscriptions" data-toggle="collapse">
                                    <span class="icon-payment_icon size-22"></span>
                                    <span> Subscriptions </span>
                                </a>
                                <div class="collapse" id="sidebarsubscriptions">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{route('subscriptions.users')}}">Customers</a>
                                        </li>
                                        <li>
                                            <a href="{{route('subscriptions.vendors')}}">Vendors</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @if(in_array('CUSTOMERS',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('customer.index')}}">
                                        <span class="icon-customer_icon"></span>
                                        <span> Customers </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
                <li>
                   <a href="#sidebasettings" data-toggle="collapse">
                        <span class="icon-dashboard_icon"></span>
                        <span>SETTINGS</span>
                    </a>
                    <div class="collapse" id="sidebasettings">
                        <ul class="nav-second-level">
                            @if(in_array('Profile',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('client.profile')}}">
                                        <span class="icon-profile_icon"></span>
                                        <span> Profile </span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('CUSTOMIZE',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('configure.customize')}}">
                                        <span class="icon-customize-icon size-20"></span>
                                        <span> Customize </span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#sidebarstyling" data-toggle="collapse">
                                    <span class="icon-theme-icon size-20"></span>
                                    <span> Styling </span>
                                </a>
                                <div class="collapse" id="sidebarstyling">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="{{route('appStyling.index')}}">App Styling</a>
                                        </li>
                                        <li>
                                            <a href="{{route('webStyling.index')}}">Web Styling</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @if(in_array('CATALOG',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('category.index')}}">
                                        <span class="icon-catalog_icon"></span>
                                        <span> Catalog </span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('CONFIGURATIONS',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('configure.index')}}">
                                        <span class="icon-configure_icon"></span>
                                        <span> Configurations </span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('TAX',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('tax.index')}}">
                                        <span class="icon-tax_icon"></span>
                                        <span> Tax </span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('PAYMENT',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('payoption.index')}}">
                                        <span class="icon-payment_icon"></span>
                                        <span> Payment Options </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div> 
                </li>
                <li>
                    <a href="#sidebarmarketing" data-toggle="collapse">
                        <span class="icon-dashboard_icon"></span>
                        <span>MARKETING</span>
                    </a>
                    <div class="collapse" id="sidebarmarketing">
                        <ul class="nav-second-level">
                            @if(in_array('BANNER',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('banner.index')}}">
                                        <span class="icon-banner_icon"></span>
                                        <span> Banner </span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('PROMOCODE',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('promocode.index')}}">
                                        <span class="icon-promocode_icon"></span>
                                        <span> Promocode </span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('LOYALTY CARDS',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('loyalty.index')}}">
                                        <span class="icon-loyality_icon"></span>
                                        <span> Loyalty Cards </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarextra" data-toggle="collapse">
                        <span class="icon-dashboard_icon"></span>
                        <span>EXTRA</span>
                    </a>
                    <div class="collapse" id="sidebarextra">
                        <ul class="nav-second-level">
                            @php
                            $brity = \App\Models\ClientPreference::where(['id' => 1])->first('celebrity_check');
                            @endphp
                            @if(!empty($brity) && $brity->celebrity_check == 1)
                                @if(in_array('CELEBRITY',$allowed) || Auth::user()->is_superadmin == 1)
                                    <li>
                                        <a href="{{route('celebrity.index')}}">
                                            <span class="icon-celebrities_icon"></span>
                                            <span> Celebrities </span>
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>