@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
$pages = \App\Models\Page::with(['translations' => function($q) {$q->where('language_id', session()->get('customerLanguage'));}])->get();
@endphp
<footer class="footer-light">
    <section class="section-b-space light-layout py-4">
        <div class="container">
            <div class="row footer-theme partition-f">
                <div class="col-lg-2 col-md-6 mb-md-0 mb-3 d-flex align-items-center">
                    <div class="footer-logo mb-0">
                        <a href="{{ route('userHome') }}">
                            <img class="img-fluid blur-up lazyload" src="{{$urlImg}}">
                        </a>
                    </div>
                </div>
                @if(count($pages))
                    <div class="col-lg-3 col-md-6 mb-md-0 mb-3 pl-lg-5">
                        <div class="sub-title">
                            <div class="footer-title">
                                <h4>{{ __('Links') }}</h4>
                            </div>
                            <div class="footer-contant">
                                <ul>
                                    @foreach($pages as $page)
                                        <li><a href="{{route('extrapage',['slug' => $page->slug])}}">{{$page->translations->first() ? $page->translations->first()->title : $page->primary->title}}</a></li>
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
                                <h4>{{ __('Connect') }}</h4>
                            </div>
                        </div>
                        <div class="footer-contant">
                            <div class="footer-social">
                                <ul>
                                    @foreach($social_media_details as $social_media_detail)
                                        <li>
                                            <a href="{{http_check($social_media_detail->url)}}" target="_blank">
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
                            <h4>{{ __('Contact Us') }}</h4>
                        </div>
                    <div class="footer-contant">
                        <ul class="contact_link">
                            <li>
                                <i class="fa fa-home"></i>
                                <?=  $client_head ? $client_head->company_address : '' ?? 'Level- 26, Dubai World Trade Centre Tower,
                                Sheikh Rashid Tower, Sheikh Zayed Rd, Dubai, UAE' ?>
                            </li>
                            <li>
                                <a href="tel:+234-803-531-4802">
                                    <i class="fa fa-phone mr-1" aria-hidden="true"></i>
                                    <span><?=  $client_head ? $client_head->phone_number : '' ?? '+234-803-531-4802' ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="mailto:info@pearwheel.com">
                                    <i class="fa fa-envelope-o mr-1" aria-hidden="true"></i>
                                    <span><?=  $client_head ? $client_head->email : '' ?? 'info@pearwheel.com' ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="#"><span></span></a>
                            </li>
                        </ul>
                    </div>
                    </div>
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