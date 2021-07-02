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
        <!-- <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Payment Options</h4>
            </div>
        </div> -->
      <div class="col-12">
         <div class="text-sm-left">
            @if (\Session::has('success'))
            <div class="alert alert-success">
               <span>{!! \Session::get('success') !!}</span>
            </div>
            @endif
         </div>
      </div>
   </div>
    
    <form method="POST" id="payment_option_form" action="{{route('payoption.updateAll')}}">
        @csrf
        @method('POST')
        <div class="row align-items-center">
            <div class="col-sm-8">
                <div class="text-sm-left">
                    <div class="page-title-box">
                        <h4 class="page-title">Payment Options</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> Save</button>
            </div>
        </div>
        <div class="row">
            @foreach($payOption as $key => $opt)
            <div class="col-md-4">
            
                <input type="hidden" name="method_id[]" id="{{$opt->id}}" value="{{$opt->id}}">
                <input type="hidden" name="method_name[]" id="{{$opt->title}}" value="{{$opt->title}}">
                
                <?php 
                $creds = json_decode($opt->credentials);
                $username = (isset($creds->username)) ? $creds->username : '';
                $password = (isset($creds->password)) ? $creds->password : '';
                $signature = (isset($creds->signature)) ? $creds->signature : '';
                $api_key = (isset($creds->api_key)) ? $creds->api_key : '';
                ?>

                <div class="card-box h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{$opt->title}}</h4>
                    </div>
                    <div class="form-group mb-0 switchery-demo">
                        <label for="" class="mr-3">Enable</label>
                        <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->title}}" data-plugin="switchery" name="active[{{$opt->id}}]" class="chk_box all_select" data-color="#43bee1" @if($opt->status == 1) checked @endif>
                    </div>

                    @if ( (strtolower($opt->title) == 'stripe') )
                    <div id="stripe_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="stripe_api_key" class="mr-3">API Key</label>
                                    <input type="textbox" name="stripe_api_key" id="stripe_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->title) == 'paypal') )
                    <div id="paypal_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="paypal_username" class="mr-3">Username</label>
                                    <input type="textbox" name="paypal_username" id="paypal_username" class="form-control" value="{{$username}}" @if($opt->status == 1) value="" required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="paypal_password" class="mr-3">Password</label>
                                    <input type="textbox" name="paypal_password" id="paypal_password" class="form-control" value="{{$password}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="paypal_signature" class="mr-3">Signature</label>
                                    <input type="textbox" name="paypal_signature" id="paypal_signature" class="form-control" value="{{$signature}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- <div class="d-flex align-items-center justify-content-between mb-2">
                        <button class="btn btn-info d-block" type="submit"> Save </button>
                    </div> -->
                </div>
            </div>
            @endforeach
        </div>
    </form>
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
            var id = $(this).data('id');
            // console.log(id);
            var title = $(this).data('title');

            if( title.toLowerCase() == 'stripe' ){
                if($(this).is(":checked")){
                    $("#stripe_fields_wrapper").show();
                    $("#stripe_fields_wrapper").find('input').attr('required', true);
                }
                else{
                    $("#stripe_fields_wrapper").hide();
                    $("#stripe_fields_wrapper").find('input').removeAttr('required');
                }
            }

            if( title.toLowerCase() == 'paypal' ){
                if($(this).is(":checked")){
                    $("#paypal_fields_wrapper").show();
                    $("#paypal_fields_wrapper").find('input').attr('required', true);
                }
                else{
                    $("#paypal_fields_wrapper").hide();
                    $("#paypal_fields_wrapper").find('input').removeAttr('required');
                }
            }

            // $('#form_'+id).submit();

            //$('.vendorRow').toggle();
        });
    </script>
@endsection