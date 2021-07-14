<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Complete Checkout</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/themify-icons.css')}}">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom.css')}}">
</head>
<body>

<section class="section-b-space">
    <div class="container-fluid">
        <div class="payment_response">
            @if (\Session::has('success'))
                <div class="alert p-0 mt-2 alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @php
                    \Session::forget('success');
                @endphp
            @endif
        </div>
    </div>
</section>

<script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
<script>
    
</script>
</body>
</html>
