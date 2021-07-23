<div class="left-side-menu">
    <div class="logo-box m-hide d-lg-block">
        @php
            $urlImg = URL::to('/').'/assets/images/users/user-1.jpg';
            $clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
            if($clientData){
                $urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
            }
            $marketing_permissions = array("banner", "promocode", "loyalty_cards");
            $subscription_permissions = array("subscription_plans_customers", "subscription_plans_vendors");
            $extra_permissions = array("celebrity", "inquiries");
            $setting_permissions = array("profile", "customize", "app_styling", "web_styling", "catalog", "configurations", "tax", "payment");
            $styling_permissions = array("app_styling", "web_styling");
            $order_permissions = array("dashboard", "orders", "vendors", "accounting_orders","accounting_loyality", "accounting_promo_codes", "accounting_taxes","accounting_vendors", "subscriptions_customers", "subscriptions_vendors", "customers");
            $accounting_permissions = array("accounting_orders", "accounting_loyality", "accounting_promo_codes", "accounting_taxes", "accounting_vendors");
        @endphp
        <a href="{{route('client.dashboard')}}" class="logo logo-dark text-center">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="50">
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
                foreach (Auth::user()->getAllPermissions as $value) {
                    array_push($allowed, $value->permission->slug);
                }
            } else {
                array_push($allowed, '99999');
            }
            ?>
            <ul id="side-menu">
                @php
                    $client_preference = \App\Models\ClientPreference::where(['id' => 1])->first();
                @endphp
                 @if(count(array_intersect($order_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                <li>
                    <a class="menu-title pl-1" href="#">
                        <!-- <span class="icon-orders"></span> -->
                        <span>ORDERS</span>
                    </a>
                    <ul class="nav-second-level">
                            @if(in_array('dashboard',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('client.dashboard')}}">
                                        <span class="icon-dash"></span>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('orders',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('order.index')}}">
                                        <span class="icon-orders"></span>
                                        <span> Orders </span>
                                    </a>
                                </li>
                            @endif
                            @if(in_array('vendors',$allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('vendor.index')}}">
                                    <span class="icon-vendor"></span>
                                        <span>{{getNomenclatureName('Vendors', true)}}</span>
                                    </a>
                                </li>
                            @endif
                            @if(count(array_intersect($accounting_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="#sidebaraccounting" data-toggle="collapse">
                                    <span class="icon-accounting"></span>
                                        <span> Accounting </span>
                                    </a>
                                    <div class="collapse" id="sidebaraccounting">
                                        <ul class="nav-second-level">
                                            @if(in_array('accounting_orders',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.orders')}}">Orders</a>
                                                </li>
                                            @endif
                                            @if(in_array('accounting_loyality',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.loyalty')}}">{{getNomenclatureName('Loyalty Cards', true)}}</a>
                                                </li>
                                            @endif
                                            @if(in_array('accounting_promo_codes',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.promo.code')}}">Promo Codes</a>
                                                </li>
                                            @endif
                                            @if(in_array('accounting_taxes',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.tax')}}">Taxes</a>
                                                </li>
                                            @endif
                                            @if(in_array('accounting_vendors',$allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{route('account.vendor')}}">{{getNomenclatureName('Vendors', true)}}</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if(Auth::user()->is_superadmin == 1)
                            {{-- @if(count(array_intersect($subscription_permissions, $allowed)) || Auth::user()->is_superadmin == 1) --}}
                                @if($client_preference->subscription_mode == 1)
                                    <li>
                                        <a href="#sidebarsubscriptions" data-toggle="collapse">
                                            <span class="icon-subscribe"></span>
                                            <span> Subscriptions </span>
                                        </a>
                                        <div class="collapse" id="sidebarsubscriptions">
                                            <ul class="nav-second-level">
                                                @if(in_array('subscription_plans_customers',$allowed) || Auth::user()->is_superadmin == 1)
                                                    <li>
                                                        <a href="{{route('subscription.plans.user')}}">Customers</a>
                                                    </li>
                                                @endif
                                                @if(in_array('subscription_plans_vendors',$allowed) || Auth::user()->is_superadmin == 1)
                                                    <li>
                                                        <a href="{{route('subscription.plans.vendor')}}">{{getNomenclatureName('Vendors', true)}}</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                            @endif
                            {{-- @if(in_array('customers',$allowed) || Auth::user()->is_superadmin == 1) --}}
                            @if(Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{route('customer.index')}}">
                                        <span class="icon-customer-2"></span>
                                        <span> Customers </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                </li>
                @endif
                @if(Auth::user()->is_superadmin == 1)
                {{-- @if(count(array_intersect($setting_permissions, $allowed)) || Auth::user()->is_superadmin == 1) --}}
                <li>
                   <a class="menu-title pl-1" href="#">
                        <!-- <span class="icon-settings-1-1"></span> -->
                        <span>SETTINGS</span>
                    </a>
                    <ul class="nav-second-level">
                        @if(in_array('profile',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('client.profile')}}">
                                    <span class="icon-profile"></span>
                                    <span> Profile </span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('customize',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('configure.customize')}}">
                                    <span class="icon-customzie"></span>
                                    <span> Customize </span>
                                </a>
                            </li>
                        @endif
                        @if(count(array_intersect($styling_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="#sidebarstyling" data-toggle="collapse">
                                    <span class="icon-styling"></span>
                                    <span> Styling </span>
                                </a>
                                <div class="collapse" id="sidebarstyling">
                                    <ul class="nav-second-level">
                                        @if(in_array('app_styling',$allowed) || Auth::user()->is_superadmin == 1)
                                            <li>
                                                <a href="{{route('appStyling.index')}}">App Styling</a>
                                            </li>
                                        @endif
                                        @if(in_array('web_styling',$allowed) || Auth::user()->is_superadmin == 1)
                                            <li>
                                                <a href="{{route('webStyling.index')}}">Web Styling</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif
                        <li>
                            <a href="#sidebarcms" data-toggle="collapse">
                                <span class="icon-cms"></span>
                                <span>CMS</span>
                            </a>
                            <div class="collapse" id="sidebarcms">
                                <ul class="nav-second-level">
                                    @if(in_array('cms_pages',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{route('cms.pages')}}">Pages</a>
                                        </li>
                                    @endif
                                    @if(in_array('cms_emails',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{route('cms.emails')}}">Emails</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @if(in_array('catalog',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('category.index')}}">
                                    <span class="icon-catalogue"></span>
                                    <span> Catalog </span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('configurations',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('configure.index')}}">
                                    <span class="icon-configuration"></span>
                                    <span> Configurations </span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('tax',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('tax.index')}}">
                                    <span class="icon-tax"></span>
                                    <span> Tax </span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('payment',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('payoption.index')}}">
                                    <span class="icon-payment-options"></span>
                                    <span> Payment Options </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(count(array_intersect($marketing_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                <li>
                    <a class="menu-title pl-1" href="#">
                        <!-- <span class="icon-marketing"></span> -->
                        <span>MARKETING</span>
                    </a>
                    <ul class="nav-second-level">
                        {{-- @if(in_array('banner',$allowed) || Auth::user()->is_superadmin == 1) --}}
                        @if(Auth::user()->is_superadmin == 1)    
                        <li>
                                <a href="{{route('banner.index')}}">
                                    <span class="icon-banners"></span>
                                    <span> Banner </span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('promocode',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('promocode.index')}}">
                                    <span class="icon-discount-voucher"></span>
                                    <span> Promocode </span>
                                </a>
                            </li>
                        @endif
                        @if(in_array('loyalty_cards',$allowed) || Auth::user()->is_superadmin == 1)
                            <li>
                                <a href="{{route('loyalty.index')}}">
                                    <span class="icon-loyaltycard"></span>
                                    <span> {{getNomenclatureName('Loyalty Cards', true)}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif
                
                @if(count(array_intersect($extra_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                    @if($client_preference->celebrity_check == 1 || $client_preference->enquire_mode == 1)
                        <li>
                            <a class="menu-title pl-1">
                                <!-- <span class="icon-extra"></span> -->
                                <span>EXTRA</span>
                            </a>
                            <ul class="nav-second-level">
                                {{-- @if(!empty($client_preference) && $client_preference->celebrity_check == 1) --}}
                                @if(Auth::user()->is_superadmin == 1)   
                                    @if(in_array('celebrity',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{ route('celebrity.index') }}">
                                                <span class="icon-celebrity"></span>
                                                <span> Celebrities </span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if(!empty($client_preference) && $client_preference->enquire_mode == 1)
                                    @if(in_array('inquiries',$allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{ route('inquiry.index') }}">
                                                <span class="icon-question"></span>
                                                <span> Inquiries </span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
</div>