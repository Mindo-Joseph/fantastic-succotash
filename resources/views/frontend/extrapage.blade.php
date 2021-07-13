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
        <form class="vendor-signup" id="vendor_signup_form">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="row">
                        <div class="col-12">
                            <h2>Personal Details.</h2>
                        </div>    
                    </div>
                    <div class="needs-validation vendor-signup">
                        <div class="form-row">
                            <div class="col-md-6 mb-3" id="nameInput">
                                <label for="fullname">Full name</label>
                                <input type="text" class="form-control" name="fullname" value="Mark">
                                <div class="invalid-feedback"><strong></strong></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationCustom02">Phone No.</label>
                                <input type="text" class="form-control" name="phone_no" value="Otto" required="">
                                <div class="invalid-feedback" id="phone_error"><strong></strong></div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3" id="emailInput">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" value="Mark" required="">
                                <div class="valid-feedback"><strong></strong></div>
                            </div>
                            <div class="col-md-4 mb-3" id="passwordInput">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" value="Otto" required="">
                                <div class="valid-feedback"><strong></strong></div>
                            </div>
                             <div class="col-md-4 mb-3" id="confirm_passwordInput">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password" value="Otto" required="">
                                <div class="valid-feedback"><strong></strong></div>
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
                                <input type="text" class="form-control" name="vendor_name" value="Mark">
                                <div class="valid-feedback">
                                    Enter Full Name!
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationCustom02">Description</label>
                                <textarea class="form-control" name="vendor_description" cols="30" rows="3"></textarea>
                                <div class="valid-feedback">
                                    Enter Vaild Number!
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="validationCustom01">Address</label>
                                <input type="text" class="form-control" name="address" value="Mark">
                                <div class="valid-feedback">
                                    Enter Full Name!
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationCustom02">Website</label>
                                <input type="text" class="form-control" name="website" value="Otto">
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
        </form>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#register_btn').click(function(){
            var form =  document.getElementById('vendor_signup_form');
            var formData = new FormData(form);
            $.ajax({
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                url: "{{ route('vendor.register') }}",
                headers: {Accept: "application/json"},
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
                error: function(response) {
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            $("#"+ key + "Input input").addClass("is-invalid");
                            $("#"+ key + "Input div.invalid-feedback").children("strong").text(errors[key][0]);
                            $("#"+ key +"Input div.invalid-feedback").show();
                        });
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                    }
                }
            });
        });
    });
</script>
@endsection