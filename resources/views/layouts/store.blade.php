<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.store.title-meta', ['title' => $title])
    @include('layouts.store.head-content', ["demo" => "creative"])

    <script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('front-assets/js/jquery-ui.min.js')}}"></script>

</head>
<body>

    @if (Auth::check())
      @include('layouts.store/topbar-auth')
    @else
      @include('layouts.store/topbar-guest')
    @endif
    
    @yield('content')
            
    @include('layouts.store/footer-content')
    <div id="fb-root"></div>

    @include('layouts.store/footer')

    @yield('script')
    </body>
</html>