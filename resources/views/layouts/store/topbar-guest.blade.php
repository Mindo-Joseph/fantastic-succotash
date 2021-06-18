@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();

$urlImg = $clientData->logo['proxy_url'].'200/80'.$clientData->logo['image_path'];
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp
<div class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="header-contact">
                    <ul>
                        <li>Welcome to Our store {{session('client_config') ? session('client_config')->company_name : ''}}</li>
                        <li><i class="fa fa-phone" aria-hidden="true"></i>Call Us: {{session('client_config') ? session('client_config')->phone_number : ''}}</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-5 text-right">
                <ul class="header-dropdown">
                    <li class="onhover-dropdown change-language">
                        <a href="#"><i class="fa fa-language" aria-hidden="true"></i><!--span>Change Language</span--> </a>
                        <ul class="onhover-show-div">
                            @foreach($languageList as $key => $listl)
                            <li><a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="onhover-dropdown change-currency">
                        <a href="#"><i class="fa fa-money" aria-hidden="true"></i><!--span>Change Currency</span--></a>
                        <ul class="onhover-show-div">
                            @foreach($currencyList as $key => $listc)
                            <li><a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr" currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a></li>
                            @endforeach
                        </ul>
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