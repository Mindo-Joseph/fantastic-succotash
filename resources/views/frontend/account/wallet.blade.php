@extends('layouts.store', ['title' => 'My Wallet'])

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
@php
$timezone = Auth::user()->timezone;
@endphp
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }

    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }

    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }

    .product-right .size-box ul li.active {
        background-color: inherit;
    }

    .login-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }
    .invalid-feedback {
        display: block;
    }
    .box-info table tr:first-child td {
        padding-top: .85rem;
    }
</style>

<section class="section-b-space">
    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{route('user.profile')}}"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Back to
                    Profile</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 profile-sidebar">
                <!-- <div class="row">
                    <div class="col-12">
                    </div>
                </div> -->
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left"
                                aria-hidden="true"></i> back</span></div>
                    <div class="block-content">
                        <ul>
                            <li><a href="{{route('user.profile')}}">Account Info</a></li>
                            <li><a href="{{route('user.addressBook')}}">Address Book</a></li>
                            <li><a href="{{route('user.orders')}}">My Orders</a></li>
                            <li><a href="{{route('user.wishlists')}}">My Wishlist</a></li>
                            <li class="active"><a href="{{route('user.account')}}">My Wallet</a></li>
                            <li><a href="{{route('user.changePassword')}}">Change Password</a></li>
                            <li class="last"><a href="{{route('user.logout')}}">Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h3 class="mt-0">My Wallet</h3>
                        </div>
                        <div class="box-account box-info">
                            <div class="card-box mb-0">
                                <div class="row align-items-center">
                                    <div class="col-md-6 text-md-left text-center mb-md-0 mb-4">
                                        <h5 class="text-17 mb-2">Available Balance</h5>
                                        <div class="text-36">${{Auth::user()->balance}}</div>
                                    </div>
                                    <div class="col-md-6 text-md-right text-center">
                                        <button type="button" class="btn btn-solid" id="topup_wallet_btn" data-toggle="modal" data-target="#topup_wallet">Topup Wallet</button>
                                        <button type="button" class="btn btn-solid" data-toggle="modal" data-target="#add-money">Payout</button>
                                    </div>
                                </div>
                            </div>
                            <h6>Transaction History</h6>
                            <div class="card-box">
                                <div class="table-responsive table-responsive-xs">
                                  <table class="table wallet-transactions border">
                                    <thead>
                                        <tr class="table-head">
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th class="text-right"><span class="text-success">Credit</span> / <span class="text-danger">Debit</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user_transactions as $ut)
                                        <?php $reason = json_decode($ut->meta) ?>
                                          <tr>
                                              <td>{{convertDateTimeInTimeZone($ut->created_at, $timezone, 'l, F d, Y, H:i A')}}</td>
                                              <td  class="name_">{!!$reason[0]!!}</td>
                                              <td class="text-right {{ ($ut->type == 'deposit') ? 'text-success' : (($ut->type == 'deposit') ? 'text-danger' : '') }}"><b>+${{$ut->amount}}</b></td>
                                          </tr>
                                        @endforeach 
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade wallet_money" id="add-money" tabindex="-1" aria-labelledby="add-moneyLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="add-moneyLabel">Pay-Out</h5>
        <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="">
            <div class="form-group">
                <label for="">Account Number</label>
                <input class="form-control" type="text" placeholder="Account Number">
            </div>
            <div class="form-group">
                <label for="">Account Name</label>
                <input class="form-control" type="text" placeholder="Account Name">
            </div>
            <div class="form-group">
                <label for="">Bank Name</label>
                <input class="form-control" type="text" placeholder="Bank Name">
            </div>
            <div class="form-group">
                <label for="">IFSC Code</label>
                <input class="form-control" type="text" placeholder="IFSC Code">
            </div>
            <button type="button" class="btn btn-solid w-100 mt-2" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="topup_wallet" tabindex="-1" aria-labelledby="topup_walletLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="topup_walletLabel">Topup Wallet</h5>
        <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="">
        <div class="modal-body">
            <div class="form-group">
                <h5 class="text-17 mb-2">Available Balance</h5>
                <div class="text-36">${{Auth::user()->balance}}</div>
            </div>
            <div class="form-group">
                <label for="">Amount</label>
                <input class="form-control" name="wallet_amount" id="wallet_amount" type="text" placeholder="Enter Amount">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-solid mt-2 custom_amount">+10</button>
                <button type="button" class="btn btn-solid mt-2 custom_amount">+20</button>
                <button type="button" class="btn btn-solid mt-2 custom_amount">+50</button>
            </div>
            <hr />
            <h5 class="text-17 mb-2">Select Payment Method</h5>
            <div class="form-group" id="wallet_payment_methods">
            </div>
        </div>
        <div class="modal-footer text-center">
            <button type="button" class="btn btn-solid mt-2" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-solid mt-2 topup_wallet_confirm">Topup Wallet</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/template" id="payment_method_template">
    <% if(payment_options == '') { %>
        <h6>Payment Methods Not Avaialable</h6>
    <% }else{ %>
        <% _.each(payment_options, function(payment_option, k){%>
            <% if( (payment_option.slug != 'cash_on_delivery') && (payment_option.slug != 'loyalty_points') ) { %>
                <label class="radio mt-2">
                    <%= payment_option.title %> 
                    <input type="radio" name="address_id" id="radio-<%= payment_option.slug %>" value="<%= payment_option.id %>" data-payment_option_id="<%= payment_option.id %>">
                    <span class="checkround"></span>
                </label>
            <% } %>
        <% }); %>
    <% } %>
</script>

@endsection

@section('script')

<script type="text/javascript">
    var payment_option_list_url = "{{route('payment.option.list')}}";
    var ajaxCall = 'ToCancelPrevReq';
    $('.verifyEmail').click(function() {
        verifyUser('email');
    });
    $('.verifyPhone').click(function() {
        verifyUser('phone');
    });

    function verifyUser($type = 'email') {
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('verifyInformation', Auth::user()->id) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "type": $type,
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                var res = response.result;
            },
            error: function(data) {},
        });
    }

    $(document).delegate(".custom_amount", "click", function(){
        let wallet_amount = $("#wallet_amount").val();
        let amount = $(this).text();
        if(wallet_amount == ''){ wallet_amount = 0; }
        let new_amount = parseInt(amount) + parseInt(wallet_amount);
        $("#wallet_amount").val(new_amount);
    });

    $(document).on("click","#topup_wallet_btn",function() {
        // $('.alert-danger').html('');
        $.ajax({
            data: {},
            type: "POST",
            async: false,
            dataType: 'json',
            url: payment_option_list_url,
            success: function(response) {
                if (response.status == "Success") {
                    $('#wallet_payment_methods').html('');
                    let payment_method_template = _.template($('#payment_method_template').html());
                    $("#wallet_payment_methods").append(payment_method_template({payment_options: response.data}));
                    // stripeInitialize();
                }
            },error: function(error){
                var response = $.parseJSON(error.responseText);
                let error_messages = response.message;
                $.each(error_messages, function(key, error_message) {
                    $('#min_order_validation_error_'+error_message.vendor_id).html(error_message.message).show();
                });
            }
        });
    });
</script>

<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
</script>

@endsection