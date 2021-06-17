<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.store.title-meta', ['title' => $title])
    @include('layouts.store.head-content', ["demo" => "creative"])
</head>
<body>
    @if (Auth::check())
      @include('layouts.store/topbar-auth')
    @else
      @include('layouts.store/topbar-guest')
    @endif
    @yield('content')
    @include('layouts.store/footer-content')
    @include('layouts.store/footer')
    @yield('script')
    </body>
</html>