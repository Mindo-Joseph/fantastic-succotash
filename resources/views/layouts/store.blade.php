<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.store.title-meta', ['title' => $title])
    @include('layouts.store.head-content', ["demo" => "creative"])
    <style>
    :root {
  --theme-deafult: <?= ($client_preference_detail->web_color) ? $client_preference_detail->web_color: '#ff4c3b' ?>; }
  a {
    color: <?= ($client_preference_detail->web_color) ? $client_preference_detail->web_color: '#ff4c3b' ?>;
}
    </style>
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