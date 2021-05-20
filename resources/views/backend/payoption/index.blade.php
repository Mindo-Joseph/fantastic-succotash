@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Payment'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Payment</h4>
            </div>
        </div>
    </div>
    
    <div class="row">
        @foreach($payOption as $key => $opt)
        <div class="col-md-4">
            <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
                <input type="hidden" name="verify_config" id="verify_config" value="1">
                @csrf
                <div class="card-box">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title text-uppercase mb-0">{{$opt->title}}</h4>
                        <span class="d-block">
                            <input type="checkbox" id="all_select" data-plugin="switchery" name="all" class="chk_box all_select " data-color="#43bee1" @if($opt->status == 1) checked @endif>
                        </span>
                    </div>
                    
                    <!-- <div class="d-flex align-items-center justify-content-between mb-2">
                        <button class="btn btn-info d-block" type="submit"> Save </button>
                    </div> -->
                </div>
            </form>
        </div>
        @endforeach
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        $('.applyVendor').click(function(){
            $('#applyVendorModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $('.all_select').change(function(){
            //var that = $(this);

            $('.vendorRow').toggle();
        });
    </script>
@endsection