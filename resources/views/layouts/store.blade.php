<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.store.title-meta', ['title' => $title])
    @include('layouts.store.head-content', ["demo" => "creative"])
    @yield('css')

    <script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('front-assets/js/jquery-ui.min.js')}}"></script>

</head>

<body>

    @include('layouts.store/topbar')
    <header>
        <div class="mobile-fix-option"></div>
        @include('layouts.store/left-sidebar')
    </header>

    
    @yield('content')
            
    @include('layouts.store/footer-content')
    <div id="fb-root"></div>

    @include('layouts.store/footer')

    @yield('script')
    </body>
</html>