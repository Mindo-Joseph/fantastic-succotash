<!DOCTYPE html>
    <html lang="en">

    <head>
        @include('layouts.shared.title-meta', ['title' => $title])
        @include('layouts.shared.head-content', ["demo" => "creative"])
        
        @yield('css')

        <script src="{{asset('assets/js/jquery-3.1.1.min.js')}}"></script>
        <script src="{{asset('assets/js/vendor.min.js')}}"></script>
        <script src="{{asset('assets/js/jquery-ui.min.js')}}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
       
       <style type="text/css">
            .loader_box {
                position: absolute;
                width: 100%;
                height: 100%;
                background: #00000075;
                top: 0;
                left: 0;
            }
            .spinner-border{
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                margin: 0 auto !important;
                display: block;
            }
       </style>

    </head>

    <body @yield('body-extra')>
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.shared/topbar')
            @include('layouts.shared/left-sidebar')

        <!-- Start Page Content here -->

        <div class="content-page">                                    
            <div class="content">
               <!-- @php 
                    $style = "";
                    if(session('preferences.twilio_status') != 'invalid_key'){
                        $style = "display:none;";
                    }
                @endphp -->

                <div class="row displaySettingsError" style="{{$style}}">
                    <div class="col-12">
                        <div class="alert alert-danger excetion_keys" role="alert">
                           <!-- @if(session('preferences.twilio_status') == 'invalid_key')
                            <span><i class="mdi mdi-block-helper mr-2"></i> <strong>Twilio</strong> key is not valid</span> <br/>
                            @endif -->
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
            
        @include('layouts.shared/footer')
        <div class="loader_box" style="display: none;">
            <div class="spinner-border text-danger m-2 showLoader" role="status" ></div>
        </div>

        </div>
        
        <!-- End Page content -->
    </div>

    @include('layouts.shared/right-sidebar')
    @include('layouts.shared/footer-script')

    @yield('script')
    <script src="{{asset('assets/js/app.min.js')}}"></script>
    </body>
</html>