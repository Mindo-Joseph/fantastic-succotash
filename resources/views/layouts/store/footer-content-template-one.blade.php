@php
$clientData = \App\Models\Client::where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
$pages = \App\Models\Page::with(['translations' => function($q) {$q->where('language_id', session()->get('customerLanguage') ??1);}])->whereHas('translations', function($q) {$q->where(['is_published' => 1, 'language_id' => session()->get('customerLanguage') ??1]);})->orderBy('order_by','ASC')->get();
@endphp
<footer class="footer-light">
   <section class="section-b-space light-layout pt-3 pb-0">
      <div class="container">
         @if(count($pages))
         <div class="row footer-theme partition-f py-lg-4 pb-1">
            @if($client_preference_detail->show_contact_us == 1)
            <div class="col-12 d-block d-md-none">
               <div class="footer-contant">
                  <div class="store-btn mb-3 d-flex align-items-center">
                     <a class="mt-2 mr-2" href="{{$client_preference_detail->ios_link??'#'}}"  target="_blank"><img class="blur-up lazyload" data-src="{{ getImageUrl(asset('front-assets/images/app-store.svg'),'135/24') }}" alt=""></a>
                     <a class="mt-2" href="{{$client_preference_detail->android_app_link??'#'}}"  target="_blank"><img class="blur-up lazyload" data-src="{{ getImageUrl(asset('front-assets/images/google-play.svg'),'135/24') }}" alt=""></a>
                  </div>
               </div>
            </div>
            @endif
            <div class="col-lg-3 col-md-6 col-5 pt-md-4 pt-lg-0">
               <div class="sub-title">
                  <div class="footer-title mt-0">
                     <h4 class="mt-0">{{ __('Quick Links') }}</h4>
                  </div>
                  <div class="footer-contant">
                     <ul>
                        @foreach($pages as $page)
                        @if(isset($page->primary->type_of_form) && ($page->primary->type_of_form == 2))
                        @if(isset($last_mile_common_set) && $last_mile_common_set != false)
                        <li>
                           <a href="{{route('extrapage',['slug' => $page->slug])}}">
                           @if(isset($page->translations) && $page->translations->first()->title != null)
                           {{ $page->translations->first()->title ?? ''}}
                           @else
                           {{ $page->primary->title ?? ''}}
                           @endif
                           </a>
                        </li>
                        @endif
                        @else
                        <li>
                           <a href="{{route('extrapage',['slug' => $page->slug])}}" target="_blank">
                           @if(isset($page->translations) && $page->translations->first()->title != null)
                           {{ $page->translations->first()->title ?? ''}}
                           @else
                           {{ $page->primary->title ?? ''}}
                           @endif
                           </a>
                        </li>
                        @endif
                        @endforeach
                     </ul>
                  </div>
               </div>
            </div>
            {{-- Contact Us details --}}
            @if($client_preference_detail->show_contact_us == 1)
            <div class="col-lg-3 col-md-6 col-7 pl-lg-4">
               <div class="sub-title">
                  <div class="footer-title mt-0">
                     <h4 class="mt-0">{{ __('Contact Us') }}</h4>
                  </div>
                  <div class="footer-contant">
                     <ul class="contact-list">
                        <li class="pl-0"><i class="icon-location"></i> <span>{{$clientData ? ($clientData->contact_address ?? $clientData->company_address)  : 'Demo Store, 345-659'}}</span></li>
                        <li class="pl-0"><i class="icon-ic_call"></i> <a href="tel: {{$clientData ? ($clientData->contact_phone_number ?? $clientData->phone_number) : '123-456-7898'}}"><span>{{$clientData ?  ($clientData->contact_phone_number ?? $clientData->phone_number) : '123-456-7898'}}</span></a></li>
                        <li class="pl-0"><i class="icon-ic_mail"></i> <a href="mailto: {{$clientData ? ($clientData->contact_email ??$clientData->email) : 'Support@Fiot.com'}}"><span>{{$clientData ? ($clientData->contact_email ??$clientData->email) : 'Support@Fiot.com'}}</span></a></li>
                     </ul>
                  </div>
               </div>
            </div>
            @endif
            @endif
            @if(count($social_media_details))
            <div class="col-lg-4 col-md-6 pl-lg-5  d-none">
               <div class="sub-title">
                  <div class="footer-title mt-0">
                     <h4 class="mt-0">{{ __('Connect') }}</h4>
                  </div>
                  <div class="footer-contant">
                     <div class="footer-social">
                        <ul>
                           @foreach($social_media_details as $social_media_detail)
                           <li class="d-block">
                              <a href="{{http_check($social_media_detail->url)}}" target="_blank">
                              <i class="fa fa-{{$social_media_detail->icon}}" aria-hidden="true"></i>
                              <span>{{$social_media_detail->icon ? ucfirst($social_media_detail->icon) : "Facebook"}}</span>
                              </a>
                           </li>
                           @endforeach
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            @endif
            <div class="col-lg-3 col-md-6 col-5 pl-xl-3 mobile-border">
               <div class="sub-title">
                  <div class="footer-title mt-0 d-none d-md-block">
                     <h4 class="mt-0">{{ __('Find Our App On Mobile') }}</h4>
                  </div>
                  <div class="footer-contant">
                     <div class="store-btn mb-3 d-none d-md-block">
                        <a href="{{$client_preference_detail->ios_link??'#'}}" target="_blank">
                           <!-- <img class="blur-up lazyload" data-src="{{ getImageUrl(asset('front-assets/images/app-store.svg'),'270/48') }}" alt=""> -->
                         <svg style="height:40px;" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 135.88 39.13"><defs><style>.cls-1{fill:#fff;}</style></defs><path d="M74.28,5.58q31.66,0,63.32,0a4.35,4.35,0,0,1,4.62,4.65c-.07,10,0,19.92,0,29.89,0,3-1.56,4.59-4.55,4.59H10.79c-2,0-3.52-.85-4.13-2.51a5.36,5.36,0,0,1-.3-1.83q0-15.19,0-30.39c0-2.78,1.62-4.36,4.4-4.36ZM39,19.9c-.15-.19-.29-.38-.45-.56a6,6,0,0,0-6.85-1.52,5.62,5.62,0,0,1-4.87,0,5.09,5.09,0,0,0-2.74-.28,6.52,6.52,0,0,0-5.38,5.81c-.63,5,1.08,9.15,4.44,12.73a3.1,3.1,0,0,0,3.9.58,5.75,5.75,0,0,1,5.07,0,3.38,3.38,0,0,0,4.45-1,15.73,15.73,0,0,0,2.92-4.84c.07-.2.12-.4.17-.59A5.83,5.83,0,0,1,39,19.9ZM75.3,28.59l-.17-1.47H73.32V40.8h2.17V36a9.35,9.35,0,0,0,2.14,1,4,4,0,0,0,4.58-2.63,6.64,6.64,0,0,0,.31-3.89A4,4,0,0,0,79.64,27,3.82,3.82,0,0,0,75.3,28.59ZM62.07,27.1V40.8h2.19V36a9.93,9.93,0,0,0,2,1,3.92,3.92,0,0,0,4.47-2.11,6.3,6.3,0,0,0,.15-5.69,3.75,3.75,0,0,0-4-2.28,3.85,3.85,0,0,0-2.77,1.67l-.18-1.47Zm-1.92,9.76c-.08-.31-.15-.53-.22-.76q-1.76-5.46-3.52-10.94c-.61-1.88-.61-1.86-2.59-1.88a.82.82,0,0,0-1,.68C51.58,28,50.26,32.08,49,36.14c-.07.22-.12.44-.2.72.63,0,1.15,0,1.68,0a.59.59,0,0,0,.7-.51c.27-1,.61-2,.88-3,.11-.43.32-.55.75-.54,1.06,0,2.11,0,3.17,0a.71.71,0,0,1,.86.61c.26,1,.6,2,.94,2.94.06.18.27.43.43.44C58.81,36.89,59.45,36.86,60.15,36.86Zm26.7-.7a6.82,6.82,0,0,0,6.88,0,3.55,3.55,0,0,0,1.49-2.75,3.51,3.51,0,0,0-1.81-3.5c-.87-.51-1.81-.88-2.71-1.33-1.19-.6-1.64-1.37-1.39-2.31a2,2,0,0,1,2.3-1.31,23.26,23.26,0,0,1,2.6.41,7.66,7.66,0,0,1,.32-1c.25-.55,0-.77-.48-.92a7.13,7.13,0,0,0-3.91-.18,3.62,3.62,0,0,0-3,2.94A3.47,3.47,0,0,0,89.08,30c.72.42,1.52.71,2.28,1.06,1.37.64,1.91,1.5,1.66,2.61a2.27,2.27,0,0,1-2.62,1.55,26.62,26.62,0,0,1-3.12-.64ZM103.68,32a2.62,2.62,0,0,0,0,.28,4.57,4.57,0,0,0,4,4.72c2.86.24,4.87-1.21,5.38-3.93a8,8,0,0,0,.07-2.07,4.46,4.46,0,0,0-3.24-4C106.51,26.11,103.67,28.37,103.68,32Zm26.26.46a14.45,14.45,0,0,0,0-2,3.75,3.75,0,0,0-2.76-3.5,4.23,4.23,0,0,0-4.66,1.44,5.32,5.32,0,0,0-1.16,3.12c-.12,2,.33,3.74,2.15,4.78s4,.76,6,.09l-.31-1.29a27.7,27.7,0,0,1-3.47.12,2.47,2.47,0,0,1-2.21-2.78Zm-32-3.8v.87c0,1.61,0,3.21,0,4.82A2.49,2.49,0,0,0,100.76,37c1.54,0,1.65-.13,1.5-1.66a1,1,0,0,0-.07-.16c-.25,0-.5.05-.76.05-.74,0-1.27-.33-1.3-1.06-.07-1.81,0-3.62,0-5.53h2.26V27.05H100V24.71c-2,.33-2,.33-2.15,2.16,0,0,0,.08-.09.16l-1.27.08V28.7Zm17.24-1.6v9.7h2.14V35.45c0-1.44,0-2.88.08-4.31a2.2,2.2,0,0,1,2.49-2.21c.13,0,.27,0,.38,0v-2c-1.54-.23-2.45.57-3.18,1.95l-.13-1.78ZM50.07,11.82v6.61c1.85,0,3.77.36,4.92-1.51A3.43,3.43,0,0,0,54.92,13C53.68,11.28,51.83,11.7,50.07,11.82ZM29.09,17c2.73.41,5.77-3.13,5.1-5.87A5.65,5.65,0,0,0,29.09,17ZM94.4,14c-1.35-.7-2.37-.53-3,.43a3,3,0,0,0,.31,3.65c.78.71,1.73.64,2.8-.24,0,.89.51.6.94.57V11.43h-1Zm-31.85-.26c.41,1.42.83,2.79,1.22,4.17.1.37.23.56.66.56s.57-.22.67-.57c.27-.93.57-1.86.89-2.89.31,1.07.56,2,.88,3a1,1,0,0,0,.57.45c.33.1.57,0,.68-.42.26-.95.58-1.88.87-2.82l.46-1.48c-1-.12-1-.12-1.22.72s-.47,1.84-.74,2.86c-.3-1.07-.56-2-.84-2.95-.09-.32-.07-.71-.62-.72s-.55.35-.65.68c-.28,1-.56,1.9-.89,3-.26-1.1-.54-2-.67-2.91S63.32,13.47,62.55,13.71Zm57.57,4.68v-.82c0-.71,0-1.44,0-2.15a1,1,0,0,1,1-1c.67,0,.93.4,1,1,0,.31,0,.62,0,.93v2h1.06c0-1,0-2,0-2.92a1.78,1.78,0,0,0-1-1.78,1.69,1.69,0,0,0-2,.41s-.08,0-.19,0V11.45h-1v6.94ZM59.18,13.52a2.36,2.36,0,0,0-2.43,2.55,2.32,2.32,0,0,0,2.34,2.47A2.36,2.36,0,0,0,61.47,16,2.27,2.27,0,0,0,59.18,13.52ZM84.25,16a2.26,2.26,0,0,0-2.31-2.47,2.34,2.34,0,0,0-2.4,2.5,2.32,2.32,0,0,0,2.29,2.52A2.37,2.37,0,0,0,84.25,16Zm15.68.09a2.29,2.29,0,0,0,2.33,2.46,2.36,2.36,0,0,0,2.36-2.61,2.24,2.24,0,0,0-2.37-2.41A2.33,2.33,0,0,0,99.93,16.08Zm7.35,2.31v-.8c0-.72,0-1.44,0-2.16a1,1,0,0,1,1.07-1c.63,0,.86.44.92,1,0,.34,0,.67,0,1v2h1c0-1.15.07-2.25,0-3.34a1.61,1.61,0,0,0-2.43-1.31,4.92,4.92,0,0,0-.68.47c-.06-.77-.53-.6-1-.52v4.72ZM129,16.27c.21-1.45-.4-2.53-1.49-2.72a2.21,2.21,0,0,0-2.64,1.93,2.35,2.35,0,0,0,2.77,3,8.57,8.57,0,0,0,1-.21c.06,0,.1-.08.19-.15l-.2-.57c-2.05.29-2.75-.05-2.65-1.28ZM89.33,18.52c0-1.22,0-2.25,0-3.27a1.58,1.58,0,0,0-1.85-1.71,7.58,7.58,0,0,0-1.46.3c-.06,0-.11.07-.19.12l.24.59c1.55-.51,2.12-.32,2.16.63a12.51,12.51,0,0,0-2,.6,1.48,1.48,0,0,0-.68,1.86,1.43,1.43,0,0,0,1.71.85,8,8,0,0,0,1.1-.46l-.07.16ZM71.9,18.4V17.26A17.88,17.88,0,0,1,72,15.32a1,1,0,0,1,1.09-.91c.59,0,.82.42.87,1q0,.47,0,.93c0,.69,0,1.37,0,2.08h1a30.53,30.53,0,0,0,0-3.32,1.64,1.64,0,0,0-2.78-1.12l-.4.32c0-.88-.47-.62-.94-.61V18.4Zm45.57,0v-.72c-.54,0-1,0-1.06-.68,0-.82,0-1.66,0-2.51l1.16-.09,0-.71-1.09-.07-.07-1.08c-.72,0-1.17.24-1,1l-.67.14v.68l.68.11c0,.82,0,1.61,0,2.39C115.37,18.29,116.07,18.78,117.47,18.36ZM77,11.43v7h.92v-7Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M39,19.9a5.83,5.83,0,0,0,.66,10.29c0,.19-.1.39-.17.59a15.73,15.73,0,0,1-2.92,4.84,3.38,3.38,0,0,1-4.45,1,5.75,5.75,0,0,0-5.07,0,3.1,3.1,0,0,1-3.9-.58c-3.36-3.58-5.07-7.77-4.44-12.73a6.52,6.52,0,0,1,5.38-5.81,5.09,5.09,0,0,1,2.74.28,5.62,5.62,0,0,0,4.87,0,6,6,0,0,1,6.85,1.52C38.7,19.52,38.84,19.71,39,19.9Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M75.3,28.59A3.82,3.82,0,0,1,79.64,27a4,4,0,0,1,2.88,3.45,6.64,6.64,0,0,1-.31,3.89A4,4,0,0,1,77.63,37a9.35,9.35,0,0,1-2.14-1V40.8H73.32V27.12h1.81Zm5.24,3.49a15.9,15.9,0,0,0-.34-1.7,2.35,2.35,0,0,0-4.61.32,11.69,11.69,0,0,0,0,2.71,2.32,2.32,0,0,0,4.37.66A13.71,13.71,0,0,0,80.54,32.08Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M62.07,27.1h1.8l.18,1.47a3.85,3.85,0,0,1,2.77-1.67,3.75,3.75,0,0,1,4,2.28,6.3,6.3,0,0,1-.15,5.69A3.92,3.92,0,0,1,66.23,37a9.93,9.93,0,0,1-2-1V40.8H62.07Zm2.12,5h.06c0,.31,0,.62,0,.93a2.37,2.37,0,0,0,1.93,2.28,2.3,2.3,0,0,0,2.68-1.55,4.79,4.79,0,0,0,0-3.57,2.26,2.26,0,0,0-2.39-1.58,2.35,2.35,0,0,0-2.13,1.92C64.25,31.05,64.25,31.58,64.19,32.1Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M60.15,36.86c-.7,0-1.34,0-2,0-.16,0-.37-.26-.43-.44-.34-1-.68-1.95-.94-2.94a.71.71,0,0,0-.86-.61c-1.06,0-2.11,0-3.17,0-.43,0-.64.11-.75.54-.27,1-.61,2-.88,3a.59.59,0,0,1-.7.51c-.53,0-1,0-1.68,0,.08-.28.13-.5.2-.72,1.3-4.06,2.62-8.11,3.9-12.18a.82.82,0,0,1,1-.68c2,0,2,0,2.59,1.88q1.76,5.47,3.52,10.94C60,36.33,60.07,36.55,60.15,36.86Zm-5.79-11.6-1.73,5.88h3.54Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M86.85,36.16l.43-1.55a26.62,26.62,0,0,0,3.12.64A2.27,2.27,0,0,0,93,33.7c.25-1.11-.29-2-1.66-2.61-.76-.35-1.56-.64-2.28-1.06a3.47,3.47,0,0,1-1.94-3.83,3.62,3.62,0,0,1,3-2.94,7.13,7.13,0,0,1,3.91.18c.51.15.73.37.48.92a7.66,7.66,0,0,0-.32,1,23.26,23.26,0,0,0-2.6-.41,2,2,0,0,0-2.3,1.31c-.25.94.2,1.71,1.39,2.31.9.45,1.84.82,2.71,1.33a3.51,3.51,0,0,1,1.81,3.5,3.55,3.55,0,0,1-1.49,2.75A6.82,6.82,0,0,1,86.85,36.16Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M103.68,32c0-3.67,2.83-5.93,6.26-5a4.46,4.46,0,0,1,3.24,4,8,8,0,0,1-.07,2.07c-.51,2.72-2.52,4.17-5.38,3.93a4.57,4.57,0,0,1-4-4.72A2.62,2.62,0,0,1,103.68,32Zm2-.05a12.86,12.86,0,0,0,.86,2.4,2.12,2.12,0,0,0,3.73,0,4.47,4.47,0,0,0,.07-4.83,2.17,2.17,0,0,0-3.86,0A13.87,13.87,0,0,0,105.69,32Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M129.94,32.5H123.5a2.47,2.47,0,0,0,2.21,2.78,27.7,27.7,0,0,0,3.47-.12l.31,1.29c-2,.67-4.05,1-6-.09s-2.27-2.81-2.15-4.78a5.32,5.32,0,0,1,1.16-3.12A4.23,4.23,0,0,1,127.17,27a3.75,3.75,0,0,1,2.76,3.5A14.45,14.45,0,0,1,129.94,32.5Zm-6.46-1.6h4.4a2.13,2.13,0,0,0-1.82-2.53C124.67,28.25,123.62,29.25,123.48,30.9Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M97.91,28.7H96.53V27.11L97.8,27c0-.08.09-.12.09-.16C98,25,98,25,100,24.71v2.34h2.33v1.63h-2.26c0,1.91,0,3.72,0,5.53,0,.73.56,1.06,1.3,1.06.26,0,.51,0,.76-.05a1,1,0,0,1,.07.16c.15,1.53,0,1.65-1.5,1.66A2.49,2.49,0,0,1,98,34.39c-.07-1.61,0-3.21,0-4.82Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M115.15,27.1h1.78l.13,1.78c.73-1.38,1.64-2.18,3.18-1.95v2c-.11,0-.25,0-.38,0a2.2,2.2,0,0,0-2.49,2.21c-.12,1.43-.06,2.87-.08,4.31V36.8h-2.14Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M50.07,11.82c1.76-.12,3.61-.54,4.85,1.16A3.43,3.43,0,0,1,55,16.92c-1.15,1.87-3.07,1.54-4.92,1.51Zm1,5.77.74,0a2.38,2.38,0,0,0,2.54-2.77,2.22,2.22,0,0,0-2.72-2.29c-.2,0-.53.28-.53.44C51.08,14.5,51.09,16,51.09,17.59Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M29.09,17a5.65,5.65,0,0,1,5.1-5.87C34.86,13.85,31.82,17.39,29.09,17Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M94.4,14V11.43h1v7c-.43,0-1,.32-.94-.57-1.07.88-2,1-2.8.24a3,3,0,0,1-.31-3.65C92,13.44,93.05,13.27,94.4,14Zm0,2h0c0-.16,0-.33,0-.5a1.07,1.07,0,0,0-.92-1.11,1,1,0,0,0-1.24.69,4.45,4.45,0,0,0,.07,2,1.26,1.26,0,0,0,1.09.54c.37-.08.69-.55.95-.9C94.44,16.55,94.37,16.24,94.39,16Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M62.55,13.71c.77-.24,1.15-.08,1.27.7s.41,1.81.67,2.91c.33-1.14.61-2.09.89-3,.1-.33.12-.68.65-.68s.53.4.62.72c.28.94.54,1.88.84,2.95.27-1,.5-1.94.74-2.86s.22-.84,1.22-.72L69,15.17c-.29.94-.61,1.87-.87,2.82-.11.39-.35.52-.68.42a1,1,0,0,1-.57-.45c-.32-.95-.57-1.91-.88-3-.32,1-.62,2-.89,2.89-.1.35-.22.57-.67.57s-.56-.19-.66-.56C63.38,16.5,63,15.13,62.55,13.71Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M120.12,18.39h-1V11.45h1V14c.11,0,.17.06.19,0a1.69,1.69,0,0,1,2-.41,1.78,1.78,0,0,1,1,1.78c0,1,0,1.92,0,2.92h-1.06v-2c0-.31,0-.62,0-.93-.06-.59-.32-1-1-1a1,1,0,0,0-1,1c0,.71,0,1.44,0,2.15Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M59.18,13.52A2.27,2.27,0,0,1,61.47,16a2.36,2.36,0,0,1-2.38,2.53,2.32,2.32,0,0,1-2.34-2.47A2.36,2.36,0,0,1,59.18,13.52ZM60.38,16c-.05-.28-.07-.52-.13-.75a1.19,1.19,0,0,0-1.1-1,1.08,1.08,0,0,0-1.16,1,4.48,4.48,0,0,0,0,1.55,1.05,1.05,0,0,0,1.06.91,1.12,1.12,0,0,0,1.15-.9A6,6,0,0,0,60.38,16Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M84.25,16a2.37,2.37,0,0,1-2.42,2.55A2.32,2.32,0,0,1,79.54,16a2.34,2.34,0,0,1,2.4-2.5A2.26,2.26,0,0,1,84.25,16Zm-1.09,0a6.31,6.31,0,0,0-.15-.8,1.15,1.15,0,0,0-1.12-.94,1.07,1.07,0,0,0-1.11.94,4.64,4.64,0,0,0,0,1.62,1,1,0,0,0,1.08.87,1.11,1.11,0,0,0,1.1-.87A5.71,5.71,0,0,0,83.16,16Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M99.93,16.08a2.33,2.33,0,0,1,2.32-2.56,2.24,2.24,0,0,1,2.37,2.41,2.36,2.36,0,0,1-2.36,2.61A2.29,2.29,0,0,1,99.93,16.08Zm3.64,0a6.53,6.53,0,0,0-.2-.89,1.12,1.12,0,0,0-1.1-.87,1,1,0,0,0-1.09.88,4,4,0,0,0,.08,1.8,1.31,1.31,0,0,0,1,.7c.32,0,.74-.34,1-.64A3,3,0,0,0,103.57,16.06Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M107.28,18.39h-1V13.67c.44-.08.91-.25,1,.52a4.92,4.92,0,0,1,.68-.47A1.61,1.61,0,0,1,110.31,15c.09,1.09,0,2.19,0,3.34h-1v-2c0-.34,0-.67,0-1-.06-.55-.29-1-.92-1a1,1,0,0,0-1.07,1c0,.72,0,1.44,0,2.16Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M129,16.27h-3c-.1,1.23.6,1.57,2.65,1.28l.2.57c-.09.07-.13.14-.19.15a8.57,8.57,0,0,1-1,.21,2.35,2.35,0,0,1-2.77-3,2.21,2.21,0,0,1,2.64-1.93C128.57,13.74,129.18,14.82,129,16.27Zm-1-.79c-.06-.88-.43-1.28-1.09-1.22a1,1,0,0,0-1,1.22Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M89.33,18.52l-1-.33.07-.16a8,8,0,0,1-1.1.46,1.43,1.43,0,0,1-1.71-.85,1.48,1.48,0,0,1,.68-1.86,12.51,12.51,0,0,1,2-.6c0-.95-.61-1.14-2.16-.63L85.82,14c.08-.05.13-.11.19-.12a7.58,7.58,0,0,1,1.46-.3,1.58,1.58,0,0,1,1.85,1.71C89.37,16.27,89.33,17.3,89.33,18.52Zm-1.11-2.47c-1.2,0-1.73.35-1.66,1,0,.5.36.69.84.64C88.12,17.66,88.4,17.11,88.22,16.05Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M71.9,18.4H70.84V13.65c.47,0,1-.27.94.61l.4-.32A1.64,1.64,0,0,1,75,15.06a30.53,30.53,0,0,1,0,3.32H74c0-.71,0-1.39,0-2.08q0-.46,0-.93c0-.54-.28-.94-.87-1a1,1,0,0,0-1.09.91,17.88,17.88,0,0,0-.06,1.94Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M117.47,18.36c-1.4.42-2.1-.07-2.12-1.5,0-.78,0-1.57,0-2.39l-.68-.11v-.68l.67-.14c-.16-.8.29-1,1-1l.07,1.08,1.09.07,0,.71-1.16.09c0,.85,0,1.69,0,2.51,0,.66.52.71,1.06.68Z" transform="translate(-6.34 -5.54)"/><path class="cls-1" d="M77,11.43h.92v7H77Z" transform="translate(-6.34 -5.54)"/><path d="M80.54,32.08a13.71,13.71,0,0,1-.59,2,2.32,2.32,0,0,1-4.37-.66,11.69,11.69,0,0,1,0-2.71,2.35,2.35,0,0,1,4.61-.32A15.9,15.9,0,0,1,80.54,32.08Z" transform="translate(-6.34 -5.54)"/><path d="M64.19,32.1c.06-.52.06-1.05.17-1.56a2.35,2.35,0,0,1,2.13-1.92,2.26,2.26,0,0,1,2.39,1.58,4.79,4.79,0,0,1,0,3.57,2.3,2.3,0,0,1-2.68,1.55A2.37,2.37,0,0,1,64.25,33c0-.31,0-.62,0-.93Z" transform="translate(-6.34 -5.54)"/><path d="M54.36,25.26l1.81,5.88H52.63Z" transform="translate(-6.34 -5.54)"/><path d="M105.69,32a13.87,13.87,0,0,1,.8-2.36,2.17,2.17,0,0,1,3.86,0,4.47,4.47,0,0,1-.07,4.83,2.12,2.12,0,0,1-3.73,0A12.86,12.86,0,0,1,105.69,32Z" transform="translate(-6.34 -5.54)"/><path d="M123.48,30.9c.14-1.65,1.19-2.65,2.58-2.53a2.13,2.13,0,0,1,1.82,2.53Z" transform="translate(-6.34 -5.54)"/><path d="M51.09,17.59c0-1.59,0-3.09,0-4.59,0-.16.33-.42.53-.44a2.22,2.22,0,0,1,2.72,2.29,2.38,2.38,0,0,1-2.54,2.77Z" transform="translate(-6.34 -5.54)"/><path d="M94.39,16c0,.24,0,.55-.07.71-.26.35-.58.82-.95.9a1.26,1.26,0,0,1-1.09-.54,4.45,4.45,0,0,1-.07-2,1,1,0,0,1,1.24-.69,1.07,1.07,0,0,1,.92,1.11c0,.17,0,.34,0,.5Z" transform="translate(-6.34 -5.54)"/><path d="M60.38,16a6,6,0,0,1-.17.8,1.12,1.12,0,0,1-1.15.9A1.05,1.05,0,0,1,58,16.82a4.48,4.48,0,0,1,0-1.55,1.08,1.08,0,0,1,1.16-1,1.19,1.19,0,0,1,1.1,1C60.31,15.51,60.33,15.75,60.38,16Z" transform="translate(-6.34 -5.54)"/><path d="M83.16,16a5.71,5.71,0,0,1-.18.82,1.11,1.11,0,0,1-1.1.87,1,1,0,0,1-1.08-.87,4.64,4.64,0,0,1,0-1.62,1.07,1.07,0,0,1,1.11-.94,1.15,1.15,0,0,1,1.12.94A6.31,6.31,0,0,1,83.16,16Z" transform="translate(-6.34 -5.54)"/><path d="M103.57,16.06a3,3,0,0,1-.31,1c-.25.3-.67.66-1,.64a1.31,1.31,0,0,1-1-.7,4,4,0,0,1-.08-1.8,1,1,0,0,1,1.09-.88,1.12,1.12,0,0,1,1.1.87A6.53,6.53,0,0,1,103.57,16.06Z" transform="translate(-6.34 -5.54)"/><path d="M128,15.48h-2a1,1,0,0,1,1-1.22C127.56,14.2,127.93,14.6,128,15.48Z" transform="translate(-6.34 -5.54)"/><path d="M88.22,16.05c.18,1.06-.1,1.61-.82,1.67-.48.05-.79-.14-.84-.64C86.49,16.4,87,16.07,88.22,16.05Z" transform="translate(-6.34 -5.54)"/></svg>                        </a>
                        <a class="ml-xl-2 mt-2 mt-xl-0" href="{{$client_preference_detail->android_app_link??'#'}}" target="_blank">
                           <!-- <img class="blur-up lazyload" data-src="{{ getImageUrl(asset('front-assets/images/google-play.svg'),'270/48') }}" alt=""> -->
                           <svg style="height:40px;" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 165.78 48.58"><defs><style>.cls-1{fill:#d9d9d9;}.cls-2{fill:#a6a6a6;}.cls-3{fill:#00e3fe;}.cls-4{fill:#00c6fe;}.cls-5{fill:#fefefe;}.cls-6{fill:#00f665;}.cls-7{fill:#fe2839;}.cls-8{fill:#fec200;}.cls-9{fill:#fdfdfd;}.cls-10{fill:#fcfcfc;}.cls-11{fill:#f7f7f7;}.cls-12{fill:#fbfbfb;}.cls-13{fill:#010101;}.cls-14{fill:#020202;}</style></defs><path class="cls-1" d="M160.56,48.64a.68.68,0,0,1,0,.14H5.33a.76.76,0,0,0,0-.15,1.1,1.1,0,0,1,.86-.29c.34,0,.67,0,1,0H158.7C159.33,48.34,160,48.15,160.56,48.64Z" transform="translate(-0.08 -0.2)"/><path class="cls-2" d="M160.56,48.64l-1.35,0H6.25l-.93,0A6.13,6.13,0,0,1,.09,42.41q0-17.94,0-35.89A6.21,6.21,0,0,1,6.43.21H159a6.54,6.54,0,0,1,6,3,4.9,4.9,0,0,1,.85,2.7q0,5.13,0,10.24c0,8.78-.05,17.56,0,26.33A6.19,6.19,0,0,1,160.56,48.64Z" transform="translate(-0.08 -0.2)"/><path d="M83,47.76H6.61a5.18,5.18,0,0,1-5.44-4.23,7.61,7.61,0,0,1-.07-1.27q0-17.77,0-35.56A5.13,5.13,0,0,1,6.6,1.2H159.44a5.16,5.16,0,0,1,5.48,5.54V42.21a5.18,5.18,0,0,1-5.56,5.55Z" transform="translate(-0.08 -0.2)"/><path class="cls-3" d="M12.43,10.64a1.48,1.48,0,0,1,.5-1.22c.36,0,.56.23.78.45,3.09,3.21,6.43,6.16,9.52,9.36.3.31.77.55.58,1.12a2.43,2.43,0,0,1-.55.75c-2.46,2.29-4.65,4.85-7.15,7.09-.84.75-1.53,1.65-2.39,2.39a2.37,2.37,0,0,0-.64,1.27c-.1.34-.17.71-.56.86-.22-.18-.15-.39-.15-.58V11.06A.86.86,0,0,1,12.43,10.64Z" transform="translate(-0.08 -0.2)"/><path class="cls-4" d="M12.43,10.64l0,21a2.75,2.75,0,0,0,.12.95C12.63,30.82,14,30,15,29c1.84-1.86,3.7-3.69,5.54-5.55,1.08-1.09,2.13-2.2,3.19-3.31.46.07.71.45,1,.71a32.86,32.86,0,0,1,3.49,3.49.49.49,0,0,1,0,.24,3.23,3.23,0,0,1-.66.76c-1.92,1.92-3.82,3.85-5.75,5.74-2.72,2.66-5.5,5.26-8.15,8-.22.22-.42.48-.77.47a2.11,2.11,0,0,1-.59-1.7q0-13.35,0-26.72A3,3,0,0,1,12.43,10.64Z" transform="translate(-0.08 -0.2)"/><path class="cls-5" d="M62.44,28.17c1,0,2,0,3,0,.75,0,.89.28.92.91a7,7,0,0,1-6.47,7.78,8,8,0,0,1-7.78-3.31,7.37,7.37,0,0,1-.81-7.91,8.25,8.25,0,0,1,12.41-3.06c.48.37.66.6.1,1.1-1,.86-.94.9-2.05.23a5.83,5.83,0,1,0-1.91,10.75,4.7,4.7,0,0,0,4.1-3.52c.16-.58.05-.8-.61-.77-1.29,0-2.6,0-3.89,0-.58,0-.7-.18-.69-.72,0-1.49,0-1.49,1.51-1.49Z" transform="translate(-0.08 -0.2)"/><path class="cls-6" d="M28.22,24.43l-4.46-4.27c-1.18-1.42-2.58-2.65-3.89-4-2.29-2.28-4.62-4.52-6.94-6.78.81-.85,1.7-.57,2.54-.11,2.55,1.39,5.07,2.82,7.6,4.24,3.46,1.94,6.91,3.9,10.37,5.85a.41.41,0,0,1-.19.43C31.73,21.12,30.42,22.62,29,24,28.77,24.19,28.58,24.48,28.22,24.43Z" transform="translate(-0.08 -0.2)"/><path class="cls-7" d="M12.94,39.55,27.6,25.1l.62-.57c.3-.05.47.16.65.33C30.26,26.25,31.66,27.64,33,29a.74.74,0,0,1,.3.58q-5.88,3.31-11.75,6.63c-2,1.15-4.07,2.3-6.12,3.42C14.33,40.29,13.72,40.24,12.94,39.55Z" transform="translate(-0.08 -0.2)"/><path class="cls-5" d="M97.79,35.8a4.51,4.51,0,0,1-6.44-.55,5.16,5.16,0,0,1-.1-6.86c1.74-2,4.18-2.31,6.57-.7-.13-.68.17-.88.76-.78a4.81,4.81,0,0,0,.93,0c.34,0,.51.07.5.46-.06,3.27.15,6.54-.11,9.8A4.39,4.39,0,0,1,96,41.48a5,5,0,0,1-5.53-3c.2-.09.41-.17.61-.28.78-.41,1.39-.64,2,.39a2.68,2.68,0,0,0,4.66-1.32A5.27,5.27,0,0,0,97.79,35.8Z" transform="translate(-0.08 -0.2)"/><path class="cls-5" d="M119.64,29c0-2.25,0-4.51,0-6.76,0-.66.19-.79.8-.78,1.72,0,3.45,0,5.16.06a4.68,4.68,0,0,1,4.47,5.64,4.87,4.87,0,0,1-4.6,3.7c-.93,0-1.86.05-2.79,0-.58,0-.78.12-.76.74,0,1.43,0,2.87,0,4.31,0,.51-.07.73-.67.73-1.62,0-1.62,0-1.62-1.63Z" transform="translate(-0.08 -0.2)"/><path class="cls-8" d="M33.34,29.61l-5.12-5.08a.34.34,0,0,1,0-.1L32.75,20c.22-.22.46-.42.69-.62,2,1.13,4.09,2.23,6.1,3.41,1.61,1,1.59,2.42,0,3.35C37.48,27.35,35.4,28.47,33.34,29.61Z" transform="translate(-0.08 -0.2)"/><path class="cls-5" d="M141.8,35.38a3.78,3.78,0,0,1-4.64,1.19A3.08,3.08,0,0,1,135,33.72a3,3,0,0,1,1.85-3,5.52,5.52,0,0,1,4.87-.07,1.47,1.47,0,0,0-.69-1.53,2.63,2.63,0,0,0-3.46.24c-.46.5-.77.14-1.11,0-1.35-.53-1.37-.78-.26-1.74,2.46-2.1,6.84-1,7.65,1.92a8.22,8.22,0,0,1,.15,2.27c0,1.38,0,2.77,0,4.15,0,.48-.14.67-.62.6a2,2,0,0,0-.42,0C142.23,36.65,141.55,36.66,141.8,35.38Z" transform="translate(-0.08 -0.2)"/><path class="cls-5" d="M78.54,31.7a5.25,5.25,0,1,1,5.18,5.22A5.13,5.13,0,0,1,78.54,31.7Z" transform="translate(-0.08 -0.2)"/><path class="cls-5" d="M110.16,36.92A5.1,5.1,0,0,1,105,31.54a4.92,4.92,0,0,1,5.21-4.92,5,5,0,0,1,4.31,3.53c.13.48-.2.44-.42.53-1.92.79-3.84,1.61-5.79,2.35-.71.27-.6.53-.21,1a2.82,2.82,0,0,0,4.08.07c.53-.55.83-.49,1.36-.11,1.11.79,1.14.77.12,1.66A5.2,5.2,0,0,1,110.16,36.92Z" transform="translate(-0.08 -0.2)"/><path class="cls-5" d="M67.12,31.76a5.23,5.23,0,1,1,5.22,5.16A5.09,5.09,0,0,1,67.12,31.76Z" transform="translate(-0.08 -0.2)"/><path class="cls-5" d="M149.4,33.57c.86-2.14,1.65-4.06,2.39-6a.84.84,0,0,1,1-.66c.58.05,1.17,0,1.84,0l-3.68,8.34c-.77,1.75-1.56,3.5-2.29,5.27a.91.91,0,0,1-1.07.67c-.5-.05-1,0-1.64,0,.7-1.53,1.35-3,2-4.42a1.46,1.46,0,0,0,0-1.35c-1.18-2.58-2.32-5.19-3.5-7.77-.23-.53-.3-.77.44-.75,1.72,0,1.72,0,2.38,1.6S148.65,31.76,149.4,33.57Z" transform="translate(-0.08 -0.2)"/><path class="cls-9" d="M133.73,29c0,2.31,0,4.62,0,6.94,0,.55-.15.71-.71.7-1.62,0-1.62,0-1.62-1.63,0-4.26,0-8.52,0-12.78,0-.59.14-.77.74-.76,1.58,0,1.58,0,1.58,1.6Z" transform="translate(-0.08 -0.2)"/><path class="cls-10" d="M103.88,29.05c0,2.29,0,4.57,0,6.86,0,.55-.15.71-.71.7-1.61,0-1.61,0-1.61-1.64,0-4.26,0-8.52,0-12.78,0-.58.13-.76.74-.75,1.58,0,1.58,0,1.58,1.6Z" transform="translate(-0.08 -0.2)"/><path class="cls-9" d="M94,10.52c0,1.82,0,3.43,0,5,0,.45-.12.58-.56.57s-.65-.06-.65-.59c0-2,0-4.06,0-6.09,0-.27-.28-.77.28-.81s1-.22,1.34.39c.88,1.47,1.83,2.91,2.75,4.36l.35.53c0-1.67,0-3.11,0-4.56,0-.46,0-.77.63-.77s.56.36.56.75c0,2,0,4,0,6,0,.25.24.7-.24.75s-.86.17-1.18-.37c-.92-1.55-1.91-3.06-2.88-4.58C94.3,11,94.19,10.83,94,10.52Z" transform="translate(-0.08 -0.2)"/><path class="cls-9" d="M56.56,12h.59c1.4,0,1.5.12,1.22,1.54-.5,2.58-3.88,3.63-6.1,1.91A3.92,3.92,0,0,1,57,9.16c.2.15.76.27.35.73-.27.31-.46.69-1,.2a2.69,2.69,0,0,0-3.71.5,2.88,2.88,0,0,0,.29,3.84,2.83,2.83,0,0,0,3.77,0,1.79,1.79,0,0,0,.36-.46c.39-.69.29-.85-.5-.86-.42,0-.85,0-1.27,0s-.64-.08-.64-.58.24-.54.62-.52S56.1,12,56.56,12Z" transform="translate(-0.08 -0.2)"/><path class="cls-9" d="M83.91,12.34a3.79,3.79,0,0,1,3.9-3.89,3.91,3.91,0,1,1,0,7.82A3.81,3.81,0,0,1,83.91,12.34Z" transform="translate(-0.08 -0.2)"/><path class="cls-9" d="M59.57,12.27c0-1,0-2,0-3,0-.45.08-.65.59-.63,1.16,0,2.31,0,3.47,0,.42,0,.52.14.52.54s0,.63-.54.6c-.73,0-1.47,0-2.2,0-.44,0-.65.07-.65.59,0,1.5,0,1.5,1.43,1.5.34,0,.68,0,1,0s.61,0,.61.52-.17.62-.63.59-1.19,0-1.77,0-.66.16-.66.67c0,1.41,0,1.41,1.43,1.41.42,0,.85,0,1.27,0s.7.06.7.63-.33.52-.7.52c-1.07,0-2.14,0-3.21,0-.6,0-.7-.2-.68-.72C59.6,14.36,59.57,13.32,59.57,12.27Z" transform="translate(-0.08 -0.2)"/><path class="cls-11" d="M66.91,13c0-.85,0-1.7,0-2.54,0-.59-.2-.75-.73-.69-.25,0-.51,0-.76,0-.42,0-.61-.09-.6-.56s.12-.59.57-.58c1.41,0,2.82,0,4.24,0,.38,0,.57.08.55.51s0,.62-.53.64c-1.55.07-1.54.09-1.54,1.62s0,2.76,0,4.14c0,.5-.16.61-.63.62s-.63-.16-.61-.63C66.94,14.65,66.91,13.8,66.91,13Z" transform="translate(-0.08 -0.2)"/><path class="cls-12" d="M77.81,12.88c0-.84,0-1.69,0-2.53,0-.48-.13-.68-.62-.62-.25,0-.51,0-.76,0-.42,0-.75,0-.74-.58s.31-.55.7-.54c1.35,0,2.7,0,4,0,.39,0,.63,0,.62.53s-.15.61-.62.61C79,9.76,79,9.78,79,11.27s0,2.82,0,4.23c0,.47-.11.62-.61.63s-.61-.17-.59-.63C77.83,14.63,77.81,13.76,77.81,12.88Z" transform="translate(-0.08 -0.2)"/><path class="cls-10" d="M73.59,12.29c0-1,0-2.09,0-3.13,0-.45.13-.57.57-.56s.66,0,.65.58q0,3.16,0,6.34c0,.49-.16.6-.62.61s-.61-.17-.59-.63C73.61,14.43,73.59,13.36,73.59,12.29Z" transform="translate(-0.08 -0.2)"/><path d="M98,31.74a2.93,2.93,0,0,1-2.75,3.14,3.13,3.13,0,0,1-.06-6.24A2.94,2.94,0,0,1,98,31.74Z" transform="translate(-0.08 -0.2)"/><path class="cls-13" d="M121.92,26.14c0-.67,0-1.35,0-2,0-.46.15-.58.58-.56.93,0,1.86,0,2.79,0a2.59,2.59,0,0,1,.15,5.17c-1,.07-2,0-3.05,0-.39,0-.47-.17-.46-.51C121.94,27.55,121.92,26.85,121.92,26.14Z" transform="translate(-0.08 -0.2)"/><path class="cls-13" d="M139.85,32a3,3,0,0,1,.43,0c.53.07,1.22.08,1.41.56s-.25,1.07-.66,1.46a2.56,2.56,0,0,1-2.79.7,1.19,1.19,0,0,1-.93-1A1.27,1.27,0,0,1,138,32.4,3,3,0,0,1,139.85,32Z" transform="translate(-0.08 -0.2)"/><path d="M86.71,31.75a2.94,2.94,0,1,1-2.89-3.11A3,3,0,0,1,86.71,31.75Z" transform="translate(-0.08 -0.2)"/><path class="cls-14" d="M107.28,31.47a2.71,2.71,0,0,1,1.55-2.57,2.4,2.4,0,0,1,2.56.13c.21.14.46.32.45.57s-.34.26-.53.34c-1.27.53-2.55,1-3.82,1.56C107.45,31.52,107.38,31.49,107.28,31.47Z" transform="translate(-0.08 -0.2)"/><path d="M69.4,31.79a2.95,2.95,0,1,1,3,3.09A3,3,0,0,1,69.4,31.79Z" transform="translate(-0.08 -0.2)"/><path d="M85.12,12.35a2.65,2.65,0,0,1,2.67-2.78A2.69,2.69,0,0,1,90.5,12.4a2.67,2.67,0,0,1-2.73,2.73A2.63,2.63,0,0,1,85.12,12.35Z" transform="translate(-0.08 -0.2)"/></svg>
                        </a>
                     </div>
                     @if(count($social_media_details))
                     <div class="footer-title mt-0">
                        <h4 class="mt-0">{{ __('Keep In Touch') }}</h4>
                     </div>
                     <ul class="social-links d-flex">
                        @foreach($social_media_details as $social_media_detail)
                        <li>
                           <a href="{{http_check($social_media_detail->url)}}" target="_blank">
                              <i class="fa fa-{{$social_media_detail->icon}}" aria-hidden="true"></i>
                              <!-- <span>{{$social_media_detail->icon ? ucfirst($social_media_detail->icon) : "Facebook"}}</span> -->
                           </a>
                        </li>
                        @endforeach
                     </ul>
                     @endif
                  </div>
               </div>
            </div>
            @if($client_preference_detail->show_payment_icons == 1)
            <div class="col-lg-3 col-md-6 col-7 payment-card mobile-border">
               <div class="sub-title">
                  <div class="footer-title mt-0">
                     <h4 class="mt-0">{{ __('Payment Method') }}</h4>
                  </div>
                  <div class="footer-contant">
                     @if($client_preference_detail->show_payment_icons == 1)
                     <div class="payment-card-bottom text-center d-flex">
                        <ul>
                           <li>
                              <a href="#"><img class="blur-up lazyload" data-src="{{ getImageUrl(asset('assets/images/visa.png'),'26/26') }}"></a>
                           </li>
                           <li>
                              <a href="#"><img class="blur-up lazyload" data-src="{{ getImageUrl(asset('assets/images/mastercard.png'),'26/26') }}"></a>
                           </li>
                           <li>
                              <a href="#"><img class="blur-up lazyload" data-src="{{ getImageUrl(asset('assets/images/paypal.png'),'26/26') }}"></a>
                           </li>
                           <li>
                              <a href="#"><img class="blur-up lazyload" data-src="{{ getImageUrl(asset('assets/images/american-express.png'),'26/26') }}"></a>
                           </li>
                           <li>
                              <a href="#"><img class="blur-up lazyload" data-src="{{ getImageUrl(asset('assets/images/discover.png'),'26/26') }}"></a>
                           </li>
                        </ul>
                     </div>
                     @endif
                  </div>
               </div>
            </div>
         </div>
         @endif
      </div>
   </section>
   <div class="sub-footer">
      <div class="container">
         <div class="row">
            <div class="col-12 text-center">
               <div class="footer-end">
                  @php
                  $currYear = \Carbon\Carbon::now()->year;
                  $prevYear = $currYear - 1;
                  $currYear = substr($currYear, -2);
                  @endphp
                  <p><i class="fa fa-copyright" aria-hidden="true"></i> {{$prevYear}}-{{$currYear}} | {{__('All rights reserved')}}</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</footer>
<div class="modal fade single-vendor-order-modal" id="single_vendor_order_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="s_vendor_remove_cartLabel" style="background-color: rgba(0,0,0,0.8);">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header pb-0">
            <h5 class="modal-title" id="s_vendor_remove_cartLabel">{{__('Remove Cart')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="m-0">{{__('You can only buy products for single vendor. Do you want to remove all your cart products to continue?')}}</h6>
         </div>
         <div class="modal-footer flex-nowrap justify-content-center align-items-center">
            <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
            <button type="button" class="btn btn-solid" id="single_vendor_remove_cart_btn" data-cart_id="">{{__('Remove')}}</button>
         </div>
      </div>
   </div>
</div>