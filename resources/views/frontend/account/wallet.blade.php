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
        <div class="row">
            <div class="col-sm-12">
                <div class="text-sm-left" id="wallet_response">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                        @php
                            \Session::forget('success');
                        @endphp
                    @endif
                    @if ( ($errors) && (count($errors) > 0) )
                        <div class="alert alert-danger">
                            <ul class="m-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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
                    @include('layouts.store/profile-sidebar')
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
                                        <h5 class="text-17 mb-2 mt-0">Available Balance</h5>
                                        <div class="text-36">$<span class="wallet_balance">@money(Auth::user()->balanceFloat)</span></div>
                                    </div>
                                    <div class="col-md-6 text-md-right text-center">
                                        <button type="button" class="btn btn-solid" id="topup_wallet_btn" data-toggle="modal" data-target="#topup_wallet">Topup Wallet</button>
                                        <button type="button" class="btn btn-solid" data-toggle="modal" data-target="#add-money">Payout</button>
                                    </div>
                                </div>
                            </div>
                            <h6>Transaction History</h6>
                            <div class="card-box" id="wallet_transactions_history">
                                <div class="table-responsive table-responsive-xs">
                                  <table class="table wallet-transactions border">
                                    <thead>
                                        <tr class="table-head">
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th class="text-right" style="white-space:nowrap"><span class="text-success">Credit</span> / <span class="text-danger">Debit</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user_transactions as $ut)
                                        <?php $reason = json_decode($ut->meta) ?>
                                          <tr>
                                              <td>{{convertDateTimeInTimeZone($ut->created_at, $timezone, 'l, F d, Y, H:i A')}}</td>
                                              <td  class="name_">{!!$reason[0]!!}</td>
                                              <td class="text-right {{ ($ut->type == 'deposit') ? 'text-success' : (($ut->type == 'deposit') ? 'text-danger' : '') }}"><b>$@money(sprintf("%.2f", $ut->amount / 100))</b></td>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
        <h5 class="modal-title text-17 mb-0 mt-0" id="topup_walletLabel">Available Balance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" id="wallet_topup_form">
        @csrf
        @method('POST')
        <div class="modal-body pb-0">
            <div class="form-group">
                <!-- <h5 class="text-17 mb-2 mt-0">Available Balance</h5> -->
                <div class="text-36">$<span class="wallet_balance">@money(Auth::user()->balanceFloat)</span></div>
            </div>
            <div class="form-group">
                <h5 class="text-17 mb-2">Topup Wallet</h5>
            </div>
            <div class="form-group">
                <label for="wallet_amount">Amount</label>
                <input class="form-control" name="wallet_amount" id="wallet_amount" type="text" placeholder="Enter Amount">
            </div>
            <div class="form-group">
                <div><label for="custom_amount">Recommended</label></div>
                <button type="button" class="btn btn-solid mb-2 custom_amount">+10</button>
                <button type="button" class="btn btn-solid mb-2 custom_amount">+20</button>
                <button type="button" class="btn btn-solid mb-2 custom_amount">+50</button>
            </div>
            <hr class="mt-0 mb-1" />
            <div class="payment_response">
                <div class="alert p-0 m-0" role="alert"></div>
            </div>
            <h5 class="text-17 mb-2">Debit From</h5>
            <div class="form-group" id="wallet_payment_methods">
            </div>
        </div>
        <div class="modal-footer d-block text-center">
            <div class="row">
                <div class="col-sm-6 pl-sm-0 pr-sm-1"><button type="button" class="btn btn-block btn-solid mt-2 topup_wallet_confirm">Topup Wallet</button></div>
                <div class="col-sm-6 pr-sm-0 pl-sm-1"><button type="button" class="btn btn-block btn-solid mt-2" data-dismiss="modal">Cancel</button></div>
            </div>
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
                    <input type="radio" name="wallet_payment_method" id="radio-<%= payment_option.slug %>" value="<%= payment_option.slug %>" data-payment_option_id="<%= payment_option.id %>">
                    <span class="checkround"></span>
                </label>
                <% if(payment_option.slug == 'stripe') { %>
                    <div class="col-md-12 mt-3 mb-3 stripe_element_wrapper d-none">
                        <div class="form-control">
                            <label class="d-flex flex-row pt-1 pb-1 mb-0">
                                <div id="stripe-card-element"></div>
                            </label>
                        </div>
                        <span class="error text-danger" id="stripe_card_error"></span>
                    </div>
                <% } %>
            <% } %>
        <% }); %>
    <% } %>
</script>
<?php /* ?><script type="text/template" id="wallet_transactions_template">
    <% _.each(wallet_transactions, function(transaction, k){ %>
        <% let reason = JSON.parse(transaction->meta); %>
        <tr>
            <td><%= transaction->created_at %></td>
            <td><%= reason[0] %></td>
            <td class="text-right <%= (transaction->type == 'deposit') ? 'text-success' : ((transaction->type == 'deposit') ? 'text-danger' : '') %>">
                <b>+$<%= transaction->amount %></b>
            </td>
        </tr>
    <% }); %>
</script><?php */ ?>
@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
    var credit_wallet_url = "{{route('user.creditWallet')}}";
    var payment_stripe_url = "{{route('payment.stripe')}}";
    var payment_paypal_url = "{{route('payment.paypalPurchase')}}";
    var payment_option_list_url = "{{route('payment.option.list')}}";
    var payment_success_paypal_url = "{{route('payment.paypalCompletePurchase')}}";

    // $(".table.wallet-transactions tbody").html('');
    // let wallet_transactions_template = _.template($('#wallet_transactions_template').html());
    // $(".table.wallet-transactions tbody").append(wallet_transactions_template({wallet_transactions: '{!! json_encode($user_transactions->toArray()) !!}' }));

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

    $(document).on('change', '#wallet_payment_methods input[name="wallet_payment_method"]', function() {
        var method = $(this).val();
        if(method == 'stripe'){
            $("#wallet_payment_methods .stripe_element_wrapper").removeClass('d-none');
        }else{
            $("#wallet_payment_methods .stripe_element_wrapper").addClass('d-none');
        }
    });
</script>

<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
</script>

@endsection