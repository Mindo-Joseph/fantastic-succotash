@extends('layouts.store', ['title' => 'Home'])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="section-b-space new-pages pb-250">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-3">{{$page_detail->primary->title}}</h2>
                <p>{!!$page_detail->primary->description!!}</p>
            </div>
        </div>
        <div class="vendor-signup ">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                <div class="row">
                    <div class="col-12">
                        <h2>Personal Details.</h2>
                    </div>    
                </div>
                <div class="needs-validation vendor-signup">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="fullname">Full name</label>
                            <input type="text" class="form-control" id="fullname" value="Mark">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">Phone No.</label>
                            <input type="text" class="form-control" id="phone_no" value="Otto" required="">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" value="Mark" required="">
                            <div class="valid-feedback"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" value="Otto" required="">
                            <div class="valid-feedback"></div>
                        </div>
                         <div class="col-md-4 mb-3">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" value="Otto" required="">
                            <div class="valid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h2>Store Details.</h2>
                        </div>    
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="">Upload Logo</label>
                            <div class="file file--upload">
                                <label for="input-file">
                                    <span class="update_pic">
                                        <img src="" alt="" id="output">
                                    </span>
                                    <span class="plus_icon">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                </label>
                                <input id="input-file" type="file" name="profile_image" accept="image/*" onchange="loadFile(event)">
                            </div>
                        </div>      
                        <div class="col-md-8 mb-3">
                            <label for="">Upload Banner</label>
                            <div class="file file--upload">
                                <label for="input-file">
                                    <span class="update_pic">
                                        <img src="" alt="" id="banner">
                                    </span>
                                        <span class="plus_icon"><i class="fas fa-plus"></i></span>
                                </label>
                                <input id="input-file" type="file" name="profile_image" accept="image/*" onchange="loadFile(event)">
                            </div>
                        </div>      
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="validationCustom01">Name</label>
                            <input type="text" class="form-control" id="validationCustom05" value="Mark" required="">
                            <div class="valid-feedback">
                                Enter Full Name!
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="validationCustom02">Description</label>
                            <textarea class="form-control" name="" id="validationCustom06" cols="30" rows="3"></textarea>
                            <div class="valid-feedback">
                                Enter Vaild Number!
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">Address</label>
                            <input type="text" class="form-control" id="validationCustom07" value="Mark" required="">
                            <div class="valid-feedback">
                                Enter Full Name!
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">Website</label>
                            <input type="text" class="form-control" id="validationCustom08" value="Otto" required="">
                            <div class="valid-feedback">
                                Enter Vaild Number!
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2 mb-3">
                            <label for="">Dine In</label>
                            <div class="toggle-icon">
                                <input type="checkbox" id="dine-in"><label for="dine-in">Toggle</label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="">Takeaway</label>
                            <div class="toggle-icon">
                                <input type="checkbox" id="takeaway"><label for="takeaway">Toggle</label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="">Delivery</label>
                            <div class="toggle-icon">
                                <input type="checkbox" id="delivery"><label for="delivery">Toggle</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-solid mt-3 w-100" type="button" id="register_btn">Submit</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#register_btn').click(function(){
            $.ajax({
                type: "POST",
                url: "{{ route('getCartProducts') }}",
                data: '',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (data.res == "null") {
                        $(".checkout-products").html(data.html);
                    } else {
                        var products = data.products;
                        let checkout_products_template = _.template($('#checkout_products_template').html());
                        if(products.length > 0){
                            $("#checkout_products_main_div").html(checkout_products_template({products:products}));
                        }
                        $('#total_payable_amount').html('$'+data.total_payable_amount)
                        $('#total_payable_amount_input').html('$'+data.total_payable_amount)
                    }
                },
                error: function(data) {
                    console.log('Error Found : ' + data);
                }
            });
        });
    });
</script>
@endsection
@section('script')
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
@endsection