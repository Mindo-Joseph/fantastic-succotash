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
                            <a class="mt-2 mr-2" href="{{$client_preference_detail->ios_link??'#'}}"  target="_blank"><svg style="height:35px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 161 41"><defs><style>.cls-1{stroke:#515251;stroke-miterlimit:10;}.cls-2{fill:#fff;}</style></defs><rect class="cls-1" x="0.5" y="0.5" width="160" height="40" rx="8"/><path class="cls-2" d="M55.83,18.72A5.82,5.82,0,0,0,56.49,29a2,2,0,0,1-.17.59,15.93,15.93,0,0,1-2.92,4.84,3.38,3.38,0,0,1-4.45,1,5.78,5.78,0,0,0-5.07,0,3.09,3.09,0,0,1-3.9-.58c-3.36-3.58-5.07-7.77-4.44-12.73a6.51,6.51,0,0,1,5.38-5.81,5.13,5.13,0,0,1,2.74.28,5.62,5.62,0,0,0,4.87,0,6,6,0,0,1,6.85,1.52A7.21,7.21,0,0,0,55.83,18.72Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M45.92,15.82A5.64,5.64,0,0,1,51,10C51.69,12.67,48.65,16.21,45.92,15.82Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M72.72,12.46h1.75c2,0,3.18,1.08,3.18,3.25S76.47,19,74.53,19H72.72Zm1.67,5.6c1.32,0,2.07-.75,2.07-2.35s-.75-2.31-2.07-2.31h-.51v4.66Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M78.55,16.55a2.35,2.35,0,1,1,4.67,0,2.35,2.35,0,1,1-4.67,0Zm3.49,0c0-1-.44-1.64-1.16-1.64s-1.15.66-1.15,1.64.43,1.63,1.15,1.63S82,17.53,82,16.55Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M83.81,14.09H85l.59,2.55c.1.47.17.93.26,1.41h0c.09-.48.19-.95.31-1.41l.64-2.55h1l.65,2.55c.12.47.22.93.33,1.41h0c.09-.48.16-.94.26-1.41l.59-2.55H90.8L89.56,19H88.19l-.57-2.29c-.11-.46-.19-.91-.3-1.43h0c-.09.52-.18,1-.29,1.44L86.43,19H85.11Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M91.78,14.09h1l.08.66h0A2.32,2.32,0,0,1,94.46,14c1.05,0,1.51.71,1.51,1.95V19H94.82V16.07c0-.81-.24-1.11-.77-1.11a1.56,1.56,0,0,0-1.12.61V19H91.78Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M97.38,17.74v-5.8h1.15V17.8c0,.28.13.38.25.38a.69.69,0,0,0,.18,0l.15.86a1.57,1.57,0,0,1-.61.1C97.68,19.12,97.38,18.59,97.38,17.74Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M99.77,16.55a2.35,2.35,0,1,1,4.67,0,2.35,2.35,0,1,1-4.67,0Zm3.48,0c0-1-.43-1.64-1.15-1.64s-1.15.66-1.15,1.64.43,1.63,1.15,1.63S103.25,17.53,103.25,16.55Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M105.2,17.68c0-1.05.87-1.6,2.89-1.81,0-.53-.23-1-.9-1a2.8,2.8,0,0,0-1.38.48l-.42-.77a3.84,3.84,0,0,1,2-.64c1.22,0,1.83.76,1.83,2.12V19h-.95l-.08-.54h0a2.39,2.39,0,0,1-1.52.66A1.37,1.37,0,0,1,105.2,17.68Zm2.89,0v-1.1c-1.34.17-1.78.52-1.78,1s.3.63.71.63A1.56,1.56,0,0,0,108.09,17.68Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M110.32,16.55A2.31,2.31,0,0,1,112.4,14a1.81,1.81,0,0,1,1.29.54l0-.79V11.94h1.15V19h-1l-.08-.53h0a2,2,0,0,1-1.39.65C111.12,19.12,110.32,18.17,110.32,16.55Zm3.33,1.07V15.33a1.46,1.46,0,0,0-1-.41c-.63,0-1.14.59-1.14,1.62s.4,1.63,1.11,1.63A1.33,1.33,0,0,0,113.65,17.62Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M117.94,16.55a2.35,2.35,0,1,1,4.67,0,2.35,2.35,0,1,1-4.67,0Zm3.48,0c0-1-.43-1.64-1.15-1.64s-1.15.66-1.15,1.64.43,1.63,1.15,1.63S121.42,17.53,121.42,16.55Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M123.75,14.09h1l.08.66h0a2.32,2.32,0,0,1,1.61-.78c1,0,1.51.71,1.51,1.95V19h-1.15V16.07c0-.81-.23-1.11-.77-1.11a1.56,1.56,0,0,0-1.12.61V19h-1.15Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M131.52,17.34V15h-.69v-.87l.75-.05.14-1.34h1v1.34h1.25V15h-1.25v2.33c0,.58.22.86.69.86a1.41,1.41,0,0,0,.51-.11l.2.85a3.14,3.14,0,0,1-1,.18C131.94,19.12,131.52,18.4,131.52,17.34Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M135,11.94h1.15v1.83l0,.94a2.29,2.29,0,0,1,1.57-.74c1.05,0,1.51.71,1.51,1.95V19H138V16.07c0-.81-.23-1.11-.77-1.11a1.56,1.56,0,0,0-1.12.61V19H135Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M140.22,16.55A2.39,2.39,0,0,1,142.48,14c1.33,0,2,1,2,2.33a2.68,2.68,0,0,1,0,.56h-3.12a1.41,1.41,0,0,0,1.45,1.37,2.05,2.05,0,0,0,1.14-.36l.39.72a3.1,3.1,0,0,1-1.68.53A2.37,2.37,0,0,1,140.22,16.55Zm3.29-.47c0-.76-.33-1.22-1-1.22a1.23,1.23,0,0,0-1.18,1.22Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M75.69,23.11h2.45L82,34.89H79.77L78,28.62c-.38-1.24-.72-2.59-1.07-3.88h-.07c-.32,1.3-.67,2.64-1,3.88L74,34.89H71.86Zm-1.57,7h5.55V31.7H74.12Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M83.25,26H85l.16.95h.06a4.26,4.26,0,0,1,2.66-1.16c2.22,0,3.48,1.77,3.48,4.5,0,3-1.81,4.77-3.77,4.77a3.53,3.53,0,0,1-2.28-1l.07,1.47v2.79H83.25Zm5.93,4.31c0-1.76-.58-2.81-1.92-2.81a2.8,2.8,0,0,0-1.93,1v4.12a2.74,2.74,0,0,0,1.77.73C88.28,33.39,89.18,32.35,89.18,30.35Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M93.41,26H95.1l.16.95h.06A4.29,4.29,0,0,1,98,25.83c2.21,0,3.47,1.77,3.47,4.5,0,3-1.8,4.77-3.77,4.77a3.52,3.52,0,0,1-2.27-1l.06,1.47v2.79H93.41Zm5.92,4.31c0-1.76-.57-2.81-1.92-2.81a2.8,2.8,0,0,0-1.93,1v4.12a2.74,2.74,0,0,0,1.77.73C98.43,33.39,99.33,32.35,99.33,30.35Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M106.42,33.38,107.64,32a4.62,4.62,0,0,0,3.07,1.34c1.32,0,2.05-.6,2.05-1.52S112,30.49,110.93,30l-1.61-.7a3.37,3.37,0,0,1-2.38-3.12c0-1.88,1.66-3.32,4-3.32a5.12,5.12,0,0,1,3.63,1.5l-1.07,1.32a3.83,3.83,0,0,0-2.56-1c-1.11,0-1.84.52-1.84,1.38s.9,1.27,1.87,1.67l1.59.67a3.26,3.26,0,0,1,2.38,3.17c0,1.91-1.58,3.5-4.23,3.5A6.06,6.06,0,0,1,106.42,33.38Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M116.7,31.91V27.69h-1.26V26.14l1.37-.1.24-2.41h1.73V26H121v1.65h-2.25V31.9c0,1,.4,1.55,1.25,1.55a2.8,2.8,0,0,0,.92-.2l.35,1.53a5.63,5.63,0,0,1-1.8.32C117.46,35.1,116.7,33.81,116.7,31.91Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M122,30.47c0-2.94,2-4.64,4.2-4.64s4.21,1.7,4.21,4.64a4.22,4.22,0,1,1-8.41,0Zm6.28,0c0-1.77-.78-2.95-2.08-2.95s-2.07,1.18-2.07,2.95.78,2.94,2.07,2.94S128.3,32.24,128.3,30.47Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M132.48,26h1.7l.16,1.58h.06a3,3,0,0,1,2.51-1.79,2.13,2.13,0,0,1,1,.18l-.36,1.8a2.88,2.88,0,0,0-.9-.15c-.71,0-1.56.49-2.09,1.83v5.4h-2.08Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M138.43,30.47c0-2.87,2-4.64,4.08-4.64,2.39,0,3.65,1.73,3.65,4.2a5,5,0,0,1-.09,1h-5.61a2.52,2.52,0,0,0,2.6,2.47,3.82,3.82,0,0,0,2.06-.64l.7,1.29a5.52,5.52,0,0,1-3,1A4.27,4.27,0,0,1,138.43,30.47Zm5.94-.85c0-1.36-.61-2.19-1.82-2.19a2.21,2.21,0,0,0-2.11,2.19Z" transform="translate(-22.39 -4.28)"/></svg></a>
                            <!-- <img class="blur-up lazyload" data-src="{{ getImageUrl(asset('front-assets/images/app-store.svg'),'135/24') }}" alt=""> -->
                            <a class="mt-2" href="{{$client_preference_detail->android_app_link??'#'}}"  target="_blank"> <svg style="height:35px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 161 41"><defs><style>.cls-1{stroke:#515251;stroke-miterlimit:10;}.cls-2{fill:#fff;}.cls-3{fill:#5ccae7;}.cls-4{fill:#33c3f2;}.cls-5{fill:#6abe55;}.cls-6{fill:#ee3540;}.cls-7{fill:#fcc210;}</style></defs><rect class="cls-1" x="0.5" y="0.5" width="160" height="40" rx="8"/><path class="cls-2" d="M75,18.84a3.09,3.09,0,0,1,3.09-3.41,2.71,2.71,0,0,1,2,.83l-.62.75a1.79,1.79,0,0,0-1.32-.57c-1.16,0-1.94.9-1.94,2.36s.69,2.4,2,2.4a1.44,1.44,0,0,0,.93-.29V19.5H77.86v-.95h2.27v2.88a3,3,0,0,1-2.09.78C76.28,22.21,75,21,75,18.84Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M81.59,15.55h3.93v1H82.75V18.2H85.1v1H82.75v1.93h2.87v1h-4Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M88.29,16.53H86.4v-1h4.93v1H89.45v5.56H88.29Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M94.43,15.55h1.16v6.54H94.43Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M98.57,16.53H96.68v-1h4.94v1H99.73v5.56H98.57Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M104.37,18.79c0-2.11,1.17-3.36,2.88-3.36s2.88,1.25,2.88,3.36-1.18,3.42-2.88,3.42S104.37,20.91,104.37,18.79Zm4.57,0c0-1.46-.66-2.35-1.69-2.35s-1.69.89-1.69,2.35.66,2.41,1.69,2.41S108.94,20.26,108.94,18.79Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M111.45,15.55h1.19l2,3.67.68,1.4h0c-.06-.68-.15-1.5-.15-2.23V15.55h1.1v6.54h-1.19l-2-3.68L112.45,17h0c0,.69.14,1.48.14,2.2v2.87h-1.1Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M75.41,31.08c0-3.82,2.29-6.14,5.42-6.14a4.56,4.56,0,0,1,3.41,1.46l-.82,1a3.35,3.35,0,0,0-2.55-1.12C78.49,26.26,77,28.09,77,31s1.41,4.83,3.93,4.83a3.23,3.23,0,0,0,2.13-.7V32.1H80.51V30.85h3.88v5a5.06,5.06,0,0,1-3.65,1.37C77.63,37.19,75.41,34.91,75.41,31.08Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M86.41,32.61c0-2.92,1.91-4.6,4.06-4.6s4,1.68,4,4.6-1.91,4.58-4,4.58S86.41,35.51,86.41,32.61Zm6.58,0c0-2-1-3.37-2.52-3.37s-2.53,1.36-2.53,3.37S89,36,90.47,36,93,34.62,93,32.61Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M96.17,32.61c0-2.92,1.91-4.6,4-4.6s4.06,1.68,4.06,4.6-1.91,4.58-4.06,4.58S96.17,35.51,96.17,32.61Zm6.58,0c0-2-1-3.37-2.53-3.37S97.7,30.6,97.7,32.61s1,3.35,2.52,3.35S102.75,34.62,102.75,32.61Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M105.91,38.66a2.46,2.46,0,0,1,1.28-2v-.07a1.64,1.64,0,0,1-.78-1.44,2.07,2.07,0,0,1,1-1.62v-.08a3,3,0,0,1-1.13-2.35A3.1,3.1,0,0,1,109.52,28a3.64,3.64,0,0,1,1.24.21h3v1.14H112a2.55,2.55,0,0,1,.71,1.8,3,3,0,0,1-3.21,3,3.16,3.16,0,0,1-1.3-.3,1.29,1.29,0,0,0-.56,1c0,.55.36,1,1.54,1h1.7c2,0,3.06.63,3.06,2.09,0,1.62-1.72,3-4.43,3C107.39,41,105.91,40.16,105.91,38.66Zm6.6-.46c0-.81-.62-1.09-1.77-1.09h-1.51a4.75,4.75,0,0,1-1.14-.13,1.84,1.84,0,0,0-.89,1.47c0,.94,1,1.53,2.54,1.53S112.51,39.11,112.51,38.2Zm-1.13-7.08a1.87,1.87,0,1,0-3.72,0,1.87,1.87,0,1,0,3.72,0Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M115.65,35.22V24.15h1.48V35.33c0,.45.19.63.41.63a1.13,1.13,0,0,0,.32,0l.2,1.13a2.26,2.26,0,0,1-.85.14C116.1,37.19,115.65,36.48,115.65,35.22Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M119.58,32.61c0-2.85,1.92-4.6,3.94-4.6,2.23,0,3.49,1.6,3.49,4.1a4,4,0,0,1-.07.84h-5.89A2.91,2.91,0,0,0,124,36a3.77,3.77,0,0,0,2.17-.7l.54,1a5.09,5.09,0,0,1-2.89.91C121.44,37.19,119.58,35.49,119.58,32.61Zm6.12-.72c0-1.75-.78-2.7-2.15-2.7a2.68,2.68,0,0,0-2.51,2.7Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M132.9,25.16h3.38c2.64,0,4.43.89,4.43,3.48s-1.78,3.65-4.36,3.65h-1.94V37H132.9Zm3.26,5.91c2.06,0,3.05-.75,3.05-2.43s-1.05-2.26-3.12-2.26h-1.68v4.69Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M143,35.22V24.15h1.48V35.33c0,.45.2.63.41.63a1.19,1.19,0,0,0,.33,0l.2,1.13a2.26,2.26,0,0,1-.85.14C143.41,37.19,143,36.48,143,35.22Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M147,34.7c0-1.9,1.64-2.86,5.37-3.26,0-1.13-.37-2.21-1.8-2.21a4.85,4.85,0,0,0-2.61.93l-.58-1A6.38,6.38,0,0,1,150.81,28c2.14,0,3,1.42,3,3.59V37h-1.22l-.12-1h0a4.69,4.69,0,0,1-2.9,1.26A2.38,2.38,0,0,1,147,34.7Zm5.37.14V32.41c-2.93.35-3.92,1.07-3.92,2.19A1.34,1.34,0,0,0,150,36,3.57,3.57,0,0,0,152.37,34.84Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M155.73,40.57l.29-1.18a2.34,2.34,0,0,0,.67.13c1,0,1.61-.8,2-1.88l.19-.65-3.51-8.77h1.53l1.78,4.85c.28.76.57,1.65.86,2.46h.07c.25-.79.5-1.69.74-2.46l1.55-4.85h1.46L160,37.7c-.62,1.73-1.52,3-3.28,3A3.06,3.06,0,0,1,155.73,40.57Z" transform="translate(-23.98 -7.28)"/><path class="cls-3" d="M40.2,15.93a1.26,1.26,0,0,1,.43-1,.91.91,0,0,1,.66.39c2.65,2.75,5.52,5.28,8.17,8,.26.27.66.48.5,1a2.3,2.3,0,0,1-.47.64c-2.11,2-4,4.16-6.14,6.08C42.63,31.62,42,32.4,41.3,33a2.07,2.07,0,0,0-.55,1.09c-.08.29-.14.61-.48.74-.18-.16-.12-.34-.12-.5V16.29A.62.62,0,0,1,40.2,15.93Z" transform="translate(-23.98 -7.28)"/><path class="cls-4" d="M40.2,15.93v18a2.23,2.23,0,0,0,.1.81c.07-1.51,1.24-2.22,2.1-3.08,1.58-1.59,3.17-3.16,4.75-4.76C48.08,26,49,25,49.89,24.08c.39.06.61.38.86.6a29.2,29.2,0,0,1,3,3,.34.34,0,0,1,0,.2,2.56,2.56,0,0,1-.57.66c-1.64,1.64-3.27,3.3-4.93,4.92-2.33,2.28-4.72,4.51-7,6.86-.19.19-.36.41-.66.4a1.77,1.77,0,0,1-.5-1.45V16.35A1.85,1.85,0,0,1,40.2,15.93Z" transform="translate(-23.98 -7.28)"/><path class="cls-5" d="M53.74,27.75l-3.83-3.66c-1-1.22-2.21-2.27-3.33-3.43-2-1.95-4-3.88-5.95-5.81.69-.73,1.45-.49,2.17-.1,2.19,1.19,4.35,2.42,6.52,3.64q4.45,2.49,8.9,5a.36.36,0,0,1-.17.37c-1.3,1.14-2.42,2.42-3.64,3.61C54.21,27.55,54.05,27.8,53.74,27.75Z" transform="translate(-23.98 -7.28)"/><path class="cls-6" d="M40.63,40.72,53.21,28.33l.53-.49c.26,0,.4.14.56.28l3.54,3.55a.67.67,0,0,1,.26.5C54.73,34.06,51.38,36,48,37.86c-1.72,1-3.49,2-5.25,2.93C41.83,41.36,41.3,41.32,40.63,40.72Z" transform="translate(-23.98 -7.28)"/><path class="cls-7" d="M58.13,32.2l-4.39-4.36v-.09L57.63,24c.18-.19.39-.36.59-.54,1.71,1,3.51,1.92,5.23,2.93,1.38.86,1.36,2.07,0,2.87C61.68,30.26,59.9,31.22,58.13,32.2Z" transform="translate(-23.98 -7.28)"/></svg></a>
                            <!-- <img class="blur-up lazyload" data-src="{{ getImageUrl(asset('front-assets/images/google-play.svg'),'135/24') }}" alt=""> -->
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
                        <div class="col-lg-3 col-md-6 col-7">
                            <div class="sub-title">
                                <div class="footer-title mt-0">
                                    <h4 class="mt-0">{{ __('Contact Us') }}</h4>
                                </div>
                                <div class="footer-contant">
                                    <ul class="contact-list">
                                        <li class="pl-0"><i class="icon-location"></i> <span>{{$clientData ? ($clientData->contact_address ?? $clientData->company_address)  : 'Demo Store, 345-659'}}</span></li>
                                        <li class="pl-0"><i class="icon-ic_call"></i> <a href="tel: {{$clientData ? ($clientData->contact_phone_number ?? $clientData->phone_number) : '123-456-7898'}}"><span>{{$clientData ?  ($clientData->contact_phone_number ?? $clientData->phone_number) : '123-456-7898'}}</span></a></li>
                                        <li class="pl-0"><i class="icon-ic_mail"></i> <a href="mailto: {{$clientData ? ($clientData->contact_email ??$clientData->email) : 'Support@Fiot.com'}}" style="text-transform:none"><span>{{$clientData ? ($clientData->contact_email ??$clientData->email) : 'Support@Fiot.com'}}</span></a></li>
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
                                <a href="{{$client_preference_detail->ios_link??'#'}}" target="_blank"><svg style="height: 35px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 161 41"><defs><style> .cls-1{stroke:#515251;stroke-miterlimit:10;}.cls-2{fill:#fff;}</style></defs><rect class="cls-1" x="0.5" y="0.5" width="160" height="40" rx="8"/><path class="cls-2" d="M55.83,18.72A5.82,5.82,0,0,0,56.49,29a2,2,0,0,1-.17.59,15.93,15.93,0,0,1-2.92,4.84,3.38,3.38,0,0,1-4.45,1,5.78,5.78,0,0,0-5.07,0,3.09,3.09,0,0,1-3.9-.58c-3.36-3.58-5.07-7.77-4.44-12.73a6.51,6.51,0,0,1,5.38-5.81,5.13,5.13,0,0,1,2.74.28,5.62,5.62,0,0,0,4.87,0,6,6,0,0,1,6.85,1.52A7.21,7.21,0,0,0,55.83,18.72Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M45.92,15.82A5.64,5.64,0,0,1,51,10C51.69,12.67,48.65,16.21,45.92,15.82Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M72.72,12.46h1.75c2,0,3.18,1.08,3.18,3.25S76.47,19,74.53,19H72.72Zm1.67,5.6c1.32,0,2.07-.75,2.07-2.35s-.75-2.31-2.07-2.31h-.51v4.66Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M78.55,16.55a2.35,2.35,0,1,1,4.67,0,2.35,2.35,0,1,1-4.67,0Zm3.49,0c0-1-.44-1.64-1.16-1.64s-1.15.66-1.15,1.64.43,1.63,1.15,1.63S82,17.53,82,16.55Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M83.81,14.09H85l.59,2.55c.1.47.17.93.26,1.41h0c.09-.48.19-.95.31-1.41l.64-2.55h1l.65,2.55c.12.47.22.93.33,1.41h0c.09-.48.16-.94.26-1.41l.59-2.55H90.8L89.56,19H88.19l-.57-2.29c-.11-.46-.19-.91-.3-1.43h0c-.09.52-.18,1-.29,1.44L86.43,19H85.11Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M91.78,14.09h1l.08.66h0A2.32,2.32,0,0,1,94.46,14c1.05,0,1.51.71,1.51,1.95V19H94.82V16.07c0-.81-.24-1.11-.77-1.11a1.56,1.56,0,0,0-1.12.61V19H91.78Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M97.38,17.74v-5.8h1.15V17.8c0,.28.13.38.25.38a.69.69,0,0,0,.18,0l.15.86a1.57,1.57,0,0,1-.61.1C97.68,19.12,97.38,18.59,97.38,17.74Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M99.77,16.55a2.35,2.35,0,1,1,4.67,0,2.35,2.35,0,1,1-4.67,0Zm3.48,0c0-1-.43-1.64-1.15-1.64s-1.15.66-1.15,1.64.43,1.63,1.15,1.63S103.25,17.53,103.25,16.55Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M105.2,17.68c0-1.05.87-1.6,2.89-1.81,0-.53-.23-1-.9-1a2.8,2.8,0,0,0-1.38.48l-.42-.77a3.84,3.84,0,0,1,2-.64c1.22,0,1.83.76,1.83,2.12V19h-.95l-.08-.54h0a2.39,2.39,0,0,1-1.52.66A1.37,1.37,0,0,1,105.2,17.68Zm2.89,0v-1.1c-1.34.17-1.78.52-1.78,1s.3.63.71.63A1.56,1.56,0,0,0,108.09,17.68Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M110.32,16.55A2.31,2.31,0,0,1,112.4,14a1.81,1.81,0,0,1,1.29.54l0-.79V11.94h1.15V19h-1l-.08-.53h0a2,2,0,0,1-1.39.65C111.12,19.12,110.32,18.17,110.32,16.55Zm3.33,1.07V15.33a1.46,1.46,0,0,0-1-.41c-.63,0-1.14.59-1.14,1.62s.4,1.63,1.11,1.63A1.33,1.33,0,0,0,113.65,17.62Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M117.94,16.55a2.35,2.35,0,1,1,4.67,0,2.35,2.35,0,1,1-4.67,0Zm3.48,0c0-1-.43-1.64-1.15-1.64s-1.15.66-1.15,1.64.43,1.63,1.15,1.63S121.42,17.53,121.42,16.55Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M123.75,14.09h1l.08.66h0a2.32,2.32,0,0,1,1.61-.78c1,0,1.51.71,1.51,1.95V19h-1.15V16.07c0-.81-.23-1.11-.77-1.11a1.56,1.56,0,0,0-1.12.61V19h-1.15Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M131.52,17.34V15h-.69v-.87l.75-.05.14-1.34h1v1.34h1.25V15h-1.25v2.33c0,.58.22.86.69.86a1.41,1.41,0,0,0,.51-.11l.2.85a3.14,3.14,0,0,1-1,.18C131.94,19.12,131.52,18.4,131.52,17.34Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M135,11.94h1.15v1.83l0,.94a2.29,2.29,0,0,1,1.57-.74c1.05,0,1.51.71,1.51,1.95V19H138V16.07c0-.81-.23-1.11-.77-1.11a1.56,1.56,0,0,0-1.12.61V19H135Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M140.22,16.55A2.39,2.39,0,0,1,142.48,14c1.33,0,2,1,2,2.33a2.68,2.68,0,0,1,0,.56h-3.12a1.41,1.41,0,0,0,1.45,1.37,2.05,2.05,0,0,0,1.14-.36l.39.72a3.1,3.1,0,0,1-1.68.53A2.37,2.37,0,0,1,140.22,16.55Zm3.29-.47c0-.76-.33-1.22-1-1.22a1.23,1.23,0,0,0-1.18,1.22Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M75.69,23.11h2.45L82,34.89H79.77L78,28.62c-.38-1.24-.72-2.59-1.07-3.88h-.07c-.32,1.3-.67,2.64-1,3.88L74,34.89H71.86Zm-1.57,7h5.55V31.7H74.12Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M83.25,26H85l.16.95h.06a4.26,4.26,0,0,1,2.66-1.16c2.22,0,3.48,1.77,3.48,4.5,0,3-1.81,4.77-3.77,4.77a3.53,3.53,0,0,1-2.28-1l.07,1.47v2.79H83.25Zm5.93,4.31c0-1.76-.58-2.81-1.92-2.81a2.8,2.8,0,0,0-1.93,1v4.12a2.74,2.74,0,0,0,1.77.73C88.28,33.39,89.18,32.35,89.18,30.35Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M93.41,26H95.1l.16.95h.06A4.29,4.29,0,0,1,98,25.83c2.21,0,3.47,1.77,3.47,4.5,0,3-1.8,4.77-3.77,4.77a3.52,3.52,0,0,1-2.27-1l.06,1.47v2.79H93.41Zm5.92,4.31c0-1.76-.57-2.81-1.92-2.81a2.8,2.8,0,0,0-1.93,1v4.12a2.74,2.74,0,0,0,1.77.73C98.43,33.39,99.33,32.35,99.33,30.35Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M106.42,33.38,107.64,32a4.62,4.62,0,0,0,3.07,1.34c1.32,0,2.05-.6,2.05-1.52S112,30.49,110.93,30l-1.61-.7a3.37,3.37,0,0,1-2.38-3.12c0-1.88,1.66-3.32,4-3.32a5.12,5.12,0,0,1,3.63,1.5l-1.07,1.32a3.83,3.83,0,0,0-2.56-1c-1.11,0-1.84.52-1.84,1.38s.9,1.27,1.87,1.67l1.59.67a3.26,3.26,0,0,1,2.38,3.17c0,1.91-1.58,3.5-4.23,3.5A6.06,6.06,0,0,1,106.42,33.38Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M116.7,31.91V27.69h-1.26V26.14l1.37-.1.24-2.41h1.73V26H121v1.65h-2.25V31.9c0,1,.4,1.55,1.25,1.55a2.8,2.8,0,0,0,.92-.2l.35,1.53a5.63,5.63,0,0,1-1.8.32C117.46,35.1,116.7,33.81,116.7,31.91Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M122,30.47c0-2.94,2-4.64,4.2-4.64s4.21,1.7,4.21,4.64a4.22,4.22,0,1,1-8.41,0Zm6.28,0c0-1.77-.78-2.95-2.08-2.95s-2.07,1.18-2.07,2.95.78,2.94,2.07,2.94S128.3,32.24,128.3,30.47Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M132.48,26h1.7l.16,1.58h.06a3,3,0,0,1,2.51-1.79,2.13,2.13,0,0,1,1,.18l-.36,1.8a2.88,2.88,0,0,0-.9-.15c-.71,0-1.56.49-2.09,1.83v5.4h-2.08Z" transform="translate(-22.39 -4.28)"/><path class="cls-2" d="M138.43,30.47c0-2.87,2-4.64,4.08-4.64,2.39,0,3.65,1.73,3.65,4.2a5,5,0,0,1-.09,1h-5.61a2.52,2.52,0,0,0,2.6,2.47,3.82,3.82,0,0,0,2.06-.64l.7,1.29a5.52,5.52,0,0,1-3,1A4.27,4.27,0,0,1,138.43,30.47Zm5.94-.85c0-1.36-.61-2.19-1.82-2.19a2.21,2.21,0,0,0-2.11,2.19Z" transform="translate(-22.39 -4.28)"/></svg></a>
                                <a class="ml-xl-2 mt-2 mt-xl-0" href="{{$client_preference_detail->android_app_link??'#'}}" target="_blank"><svg style="height:35px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 161 41"><defs><style>.cls-1{stroke:#515251;stroke-miterlimit:10;}.cls-2{fill:#fff;}.cls-3{fill:#5ccae7;}.cls-4{fill:#33c3f2;}.cls-5{fill:#6abe55;}.cls-6{fill:#ee3540;}.cls-7{fill:#fcc210;}</style></defs><rect class="cls-1" x="0.5" y="0.5" width="160" height="40" rx="8"/><path class="cls-2" d="M75,18.84a3.09,3.09,0,0,1,3.09-3.41,2.71,2.71,0,0,1,2,.83l-.62.75a1.79,1.79,0,0,0-1.32-.57c-1.16,0-1.94.9-1.94,2.36s.69,2.4,2,2.4a1.44,1.44,0,0,0,.93-.29V19.5H77.86v-.95h2.27v2.88a3,3,0,0,1-2.09.78C76.28,22.21,75,21,75,18.84Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M81.59,15.55h3.93v1H82.75V18.2H85.1v1H82.75v1.93h2.87v1h-4Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M88.29,16.53H86.4v-1h4.93v1H89.45v5.56H88.29Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M94.43,15.55h1.16v6.54H94.43Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M98.57,16.53H96.68v-1h4.94v1H99.73v5.56H98.57Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M104.37,18.79c0-2.11,1.17-3.36,2.88-3.36s2.88,1.25,2.88,3.36-1.18,3.42-2.88,3.42S104.37,20.91,104.37,18.79Zm4.57,0c0-1.46-.66-2.35-1.69-2.35s-1.69.89-1.69,2.35.66,2.41,1.69,2.41S108.94,20.26,108.94,18.79Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M111.45,15.55h1.19l2,3.67.68,1.4h0c-.06-.68-.15-1.5-.15-2.23V15.55h1.1v6.54h-1.19l-2-3.68L112.45,17h0c0,.69.14,1.48.14,2.2v2.87h-1.1Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M75.41,31.08c0-3.82,2.29-6.14,5.42-6.14a4.56,4.56,0,0,1,3.41,1.46l-.82,1a3.35,3.35,0,0,0-2.55-1.12C78.49,26.26,77,28.09,77,31s1.41,4.83,3.93,4.83a3.23,3.23,0,0,0,2.13-.7V32.1H80.51V30.85h3.88v5a5.06,5.06,0,0,1-3.65,1.37C77.63,37.19,75.41,34.91,75.41,31.08Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M86.41,32.61c0-2.92,1.91-4.6,4.06-4.6s4,1.68,4,4.6-1.91,4.58-4,4.58S86.41,35.51,86.41,32.61Zm6.58,0c0-2-1-3.37-2.52-3.37s-2.53,1.36-2.53,3.37S89,36,90.47,36,93,34.62,93,32.61Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M96.17,32.61c0-2.92,1.91-4.6,4-4.6s4.06,1.68,4.06,4.6-1.91,4.58-4.06,4.58S96.17,35.51,96.17,32.61Zm6.58,0c0-2-1-3.37-2.53-3.37S97.7,30.6,97.7,32.61s1,3.35,2.52,3.35S102.75,34.62,102.75,32.61Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M105.91,38.66a2.46,2.46,0,0,1,1.28-2v-.07a1.64,1.64,0,0,1-.78-1.44,2.07,2.07,0,0,1,1-1.62v-.08a3,3,0,0,1-1.13-2.35A3.1,3.1,0,0,1,109.52,28a3.64,3.64,0,0,1,1.24.21h3v1.14H112a2.55,2.55,0,0,1,.71,1.8,3,3,0,0,1-3.21,3,3.16,3.16,0,0,1-1.3-.3,1.29,1.29,0,0,0-.56,1c0,.55.36,1,1.54,1h1.7c2,0,3.06.63,3.06,2.09,0,1.62-1.72,3-4.43,3C107.39,41,105.91,40.16,105.91,38.66Zm6.6-.46c0-.81-.62-1.09-1.77-1.09h-1.51a4.75,4.75,0,0,1-1.14-.13,1.84,1.84,0,0,0-.89,1.47c0,.94,1,1.53,2.54,1.53S112.51,39.11,112.51,38.2Zm-1.13-7.08a1.87,1.87,0,1,0-3.72,0,1.87,1.87,0,1,0,3.72,0Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M115.65,35.22V24.15h1.48V35.33c0,.45.19.63.41.63a1.13,1.13,0,0,0,.32,0l.2,1.13a2.26,2.26,0,0,1-.85.14C116.1,37.19,115.65,36.48,115.65,35.22Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M119.58,32.61c0-2.85,1.92-4.6,3.94-4.6,2.23,0,3.49,1.6,3.49,4.1a4,4,0,0,1-.07.84h-5.89A2.91,2.91,0,0,0,124,36a3.77,3.77,0,0,0,2.17-.7l.54,1a5.09,5.09,0,0,1-2.89.91C121.44,37.19,119.58,35.49,119.58,32.61Zm6.12-.72c0-1.75-.78-2.7-2.15-2.7a2.68,2.68,0,0,0-2.51,2.7Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M132.9,25.16h3.38c2.64,0,4.43.89,4.43,3.48s-1.78,3.65-4.36,3.65h-1.94V37H132.9Zm3.26,5.91c2.06,0,3.05-.75,3.05-2.43s-1.05-2.26-3.12-2.26h-1.68v4.69Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M143,35.22V24.15h1.48V35.33c0,.45.2.63.41.63a1.19,1.19,0,0,0,.33,0l.2,1.13a2.26,2.26,0,0,1-.85.14C143.41,37.19,143,36.48,143,35.22Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M147,34.7c0-1.9,1.64-2.86,5.37-3.26,0-1.13-.37-2.21-1.8-2.21a4.85,4.85,0,0,0-2.61.93l-.58-1A6.38,6.38,0,0,1,150.81,28c2.14,0,3,1.42,3,3.59V37h-1.22l-.12-1h0a4.69,4.69,0,0,1-2.9,1.26A2.38,2.38,0,0,1,147,34.7Zm5.37.14V32.41c-2.93.35-3.92,1.07-3.92,2.19A1.34,1.34,0,0,0,150,36,3.57,3.57,0,0,0,152.37,34.84Z" transform="translate(-23.98 -7.28)"/><path class="cls-2" d="M155.73,40.57l.29-1.18a2.34,2.34,0,0,0,.67.13c1,0,1.61-.8,2-1.88l.19-.65-3.51-8.77h1.53l1.78,4.85c.28.76.57,1.65.86,2.46h.07c.25-.79.5-1.69.74-2.46l1.55-4.85h1.46L160,37.7c-.62,1.73-1.52,3-3.28,3A3.06,3.06,0,0,1,155.73,40.57Z" transform="translate(-23.98 -7.28)"/><path class="cls-3" d="M40.2,15.93a1.26,1.26,0,0,1,.43-1,.91.91,0,0,1,.66.39c2.65,2.75,5.52,5.28,8.17,8,.26.27.66.48.5,1a2.3,2.3,0,0,1-.47.64c-2.11,2-4,4.16-6.14,6.08C42.63,31.62,42,32.4,41.3,33a2.07,2.07,0,0,0-.55,1.09c-.08.29-.14.61-.48.74-.18-.16-.12-.34-.12-.5V16.29A.62.62,0,0,1,40.2,15.93Z" transform="translate(-23.98 -7.28)"/><path class="cls-4" d="M40.2,15.93v18a2.23,2.23,0,0,0,.1.81c.07-1.51,1.24-2.22,2.1-3.08,1.58-1.59,3.17-3.16,4.75-4.76C48.08,26,49,25,49.89,24.08c.39.06.61.38.86.6a29.2,29.2,0,0,1,3,3,.34.34,0,0,1,0,.2,2.56,2.56,0,0,1-.57.66c-1.64,1.64-3.27,3.3-4.93,4.92-2.33,2.28-4.72,4.51-7,6.86-.19.19-.36.41-.66.4a1.77,1.77,0,0,1-.5-1.45V16.35A1.85,1.85,0,0,1,40.2,15.93Z" transform="translate(-23.98 -7.28)"/><path class="cls-5" d="M53.74,27.75l-3.83-3.66c-1-1.22-2.21-2.27-3.33-3.43-2-1.95-4-3.88-5.95-5.81.69-.73,1.45-.49,2.17-.1,2.19,1.19,4.35,2.42,6.52,3.64q4.45,2.49,8.9,5a.36.36,0,0,1-.17.37c-1.3,1.14-2.42,2.42-3.64,3.61C54.21,27.55,54.05,27.8,53.74,27.75Z" transform="translate(-23.98 -7.28)"/><path class="cls-6" d="M40.63,40.72,53.21,28.33l.53-.49c.26,0,.4.14.56.28l3.54,3.55a.67.67,0,0,1,.26.5C54.73,34.06,51.38,36,48,37.86c-1.72,1-3.49,2-5.25,2.93C41.83,41.36,41.3,41.32,40.63,40.72Z" transform="translate(-23.98 -7.28)"/><path class="cls-7" d="M58.13,32.2l-4.39-4.36v-.09L57.63,24c.18-.19.39-.36.59-.54,1.71,1,3.51,1.92,5.23,2.93,1.38.86,1.36,2.07,0,2.87C61.68,30.26,59.9,31.22,58.13,32.2Z" transform="translate(-23.98 -7.28)"/></svg></a>
                                <!-- <img class="blur-up lazyload" data-src="{{ getImageUrl(asset('front-assets/images/google-play.svg'),'270/48') }}" alt=""> -->
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
