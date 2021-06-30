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
                                    <div class="col-sm-9 text-sm-left text-center mb-md-0 mb-4">
                                        <h5 class="text-17 mb-2">Available Balance</h5>
                                        <div class="text-36">${{Auth::user()->balance}}</div>
                                    </div>
                                    <div class="col-sm-3 text-sm-right text-center">
                                        <a class="btn btn-solid" href="#" data-toggle="modal" data-target="#add-money">Payout</a>
                                    </div>
                                </div>
                            </div>
                            <h6>Transaction History</h6>
                            <div class="card-box">
                              <div class="table-responsive">
                                  <table class="table wallet-tarnsaction w-100">
                                      <tbody>
                                      @foreach($user_transactions as $ut)
                                      <?php $reason = json_decode($ut->meta) ?>
                                          <tr>
                                              <td>{{convertDateTimeInTimeZone($ut->created_at, $timezone, 'l, F d, Y, H:i A')}}</td>
                                              <td  class="name_">{!!$reason[0]!!}</td>
                                              <td class="text-right"><b>+${{$ut->amount}}</b></td>
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

@endsection

@section('script')

<script type="text/javascript">
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
</script>

<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
</script>

@endsection