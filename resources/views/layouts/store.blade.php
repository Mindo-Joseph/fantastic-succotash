<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.store.title-meta', ['title' => $title])
    @include('layouts.store.head-content', ["demo" => "creative"])

    <script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('front-assets/js/jquery-ui.min.js')}}"></script>
    @yield('css')

</head>
<body>

    @include('layouts.store/topbar')
    
    @yield('content')
            
    @include('layouts.store/footer-content')
    <div id="fb-root"></div>

    @include('layouts.store/footer')

    @yield('script')
    </body>
</html>