@yield('css')
@php 
$mapKey = '1234';
$theme = \App\Models\ClientPreference::where(['id' => 1])->first();

if($theme && !empty($theme->map_key)){
	$mapKey = $theme->map_key;
}
@endphp

<!-- 
<script src="https://maps.googleapis.com/maps/api/js?key={{$mapKey}}&v=3.exp&libraries=places,drawing"></script>
-->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/fontawesome.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/slick.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/slick-theme.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/themify-icons.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/bootstrap.css')}}">
<!-- Theme css -->
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/color1.css')}}" media="screen" id="color">
