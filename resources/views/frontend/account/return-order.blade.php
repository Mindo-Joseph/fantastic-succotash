@extends('layouts.store', ['title' => 'Return Orders'])

@section('css')


@endsection

@section('content')

<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>


<section class="section-b-space order-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                    <div class="block-content">
                        <ul>
                            <li><a href="{{route('user.profile')}}">Account Info</a></li>
                            <li><a href="{{route('user.addressBook')}}">Address Book</a></li>
                            <li class="active"><a href="{{route('user.orders')}}">My Orders</a></li>
                            <li><a href="{{route('user.wishlists')}}">My Wishlist</a></li>
                            <li><a href="{{route('user.account')}}">My Wallet</a></li>
                            <li><a href="{{route('user.changePassword')}}">Change Password</a></li>
                            <li class="last"><a href="{{route('user.logout')}}" >Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>Return Order</h2>
                        </div>
                        <div class="welcome-msg">
                            <h5>Here are all your for return products !</h5>
                        </div>
                        <div class="row">
                            <div class="container">
                                <h2 >Choose items to return</h2>
                                <form class="" action="">
                                    <div class="row rating_files">
                                        <div class="col-12">
                                        <label>Upload Images</label>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <div class="file file--upload">
                                                <label for="input-file">
                                                    <span class="plus_icon"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                </label>
                                                <input id="input-file" type="file" name="profile_image" accept="image/*" onchange="loadFile(event)">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <span class="update_pic">
                                                <img src="" alt="" id="output">
                                            </span>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <span class="update_pic">
                                                <img src="" alt="" id="output">
                                            </span>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <span class="update_pic">
                                                <img src="" alt="" id="output">
                                            </span>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <span class="update_pic">
                                                <img src="" alt="" id="output">
                                            </span>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <span class="update_pic">
                                                <img src="" alt="" id="output">
                                            </span>
                                        </div>
                                    </div>
                    
                                    
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>Resoan for return product.</label>
                                            <select class="form-control" name="" id=""></select>
                                        </div>    
                                    </div>
                                    <div class="form-group">
                                        <label>Comments (Opitonal):</label>
                                        <textarea class="form-control" name="" id="" cols="30" rows="10"></textarea>
                                    </div>
                                    <button class="btn btn-solid mt-3 ">Request</button>
                                </form>
                            </div>
                        </div>


                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection

@section('script')

<script type="text/javascript">
  
       
</script>

@endsection