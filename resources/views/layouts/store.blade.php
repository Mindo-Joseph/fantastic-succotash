@php
$set_template = \App\Models\WebStylingOption::where('web_styling_id',1)->where('is_selected',1)->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  @include('layouts.store.title-meta', ['title' => $title])
  @include('layouts.store.head-content', ["demo" => "creative"])
  <style>
    :root {
      --theme-deafult: <?= ($client_preference_detail) ? $client_preference_detail->web_color : '#ff4c3b' ?>;
      --top-header-color: <?= ($client_preference_detail) ? $client_preference_detail->site_top_header_color : '#4c4c4c' ?>;
    }
    a {
      color: <?= ($client_preference_detail) ? $client_preference_detail->web_color : '#ff4c3b' ?>;
    }
  </style>
@if(isset($set_template)  && $set_template->template_id == 1)
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom-template-one.css')}}">
@endif
</head>
@php
$dark_mode = '';
if($client_preference_detail->show_dark_mode == 0){
  $dark_mode = '';
}
else if($client_preference_detail->show_dark_mode == 1){
  $dark_mode = 'dark';
}
else if($client_preference_detail->show_dark_mode == 2){
  if(session()->has('config_theme')){
    $dark_mode = session()->get('config_theme');
  }
  else{
    $dark_mode = '';
  }
}
@endphp
<body  class="{{$dark_mode}}{{ Request::is('category/cabservice') ? 'cab-booking-body' : '' }}" dir="{{session()->get('locale') == 'ar' ? 'rtl' : ''}}">
  
  @yield('content')


  @if(isset($set_template)  && $set_template->template_id == 1)
  @include('layouts.store/footer-content-template-one')
  @elseif(isset($set_template)  && $set_template->template_id == 2)
  @include('layouts.store/footer-content')
  @else
  @endif
  @include('layouts.store/footer')
  <div class="loader_box" style="display: none;">
    <div class="spinner-border text-danger m-2 showLoader" role="status"></div>
  </div>
  <div class="spinner-overlay">
    <div class="page-spinner">
        <div class="circle-border">
            <div class="circle-core"></div>
        </div>
    </div>
  </div>
  @yield('script')
  @if($client_preference_detail->hide_nav_bar == 1)
  <script>
    $('.main-menu').addClass('d-none').removeClass('d-block');
    $('.menu-navigation').addClass('d-none').removeClass('d-block'); 
  </script>
  @endif

  @if(isset($set_template)  && $set_template->template_id == 1)
  <script src="{{asset('front-assets/js/custom-template-one.js')}}"></script>
  @endif
</body>
</html>