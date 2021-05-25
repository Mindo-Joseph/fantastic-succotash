<div class="card-box text-center" style="">
    
    <div class="background">
        <img src="{{$vendor->logo['proxy_url'] . '90/90' . $vendor->logo['image_path']}}" class="rounded-circle avatar-lg img-thumbnail"
            alt="profile-image">

        <h4 class="mb-0">{{ucfirst($vendor->name)}}</h4>
        <p class="text-muted">{{$vendor->address}}</p>

        <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light openEditModal"> Edit </button>
        <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light"> Block </button>
    </div>
    <div class="text-left mt-3">
        <!-- <h4 class="font-13">Description :</h4> -->
        <p class="text-muted font-13 mb-3">
           {{$vendor->desc}}
        </p>
        <!-- <p class="text-muted mb-2 font-13"><strong>Latitude :</strong> <span class="ml-2">{{$vendor->latitude}}</span></p>

        <p class="text-muted mb-2 font-13"><strong>Longitude :</strong><span class="ml-2">{{$vendor->longitude}}</span></p>

        <p class="text-muted mb-1 font-13"><strong>Status :</strong> <span class="ml-2">
            {{ ($vendor->status == 1) ? 'Active' : (($vendor->status == 2) ? 'Blocked' : 'Pending') }}
        </span></p> -->
    </div>

    <!-- <ul class="social-list list-inline mt-3 mb-0">
        <li class="list-inline-item">
            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i
                    class="mdi mdi-facebook"></i></a>
        </li>
        <li class="list-inline-item">
            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i
                    class="mdi mdi-google"></i></a>
        </li>
        <li class="list-inline-item">
            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i
                    class="mdi mdi-twitter"></i></a>
        </li>
        <li class="list-inline-item">
            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i
                    class="mdi mdi-github"></i></a>
        </li>
    </ul> -->
</div>
<div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
            <form name="config-form" action="{{route('vendor.config.update', $vendor->id)}}" class="needs-validation" id="slot-configs" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-2 "> <span class="">Configuration</span></h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group" id="order_pre_timeInput">
                            {!! Form::label('title', 'Order Prepare Time(In minutes)',['class' => 'control-label']) !!}
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="order_pre_time" type="text" value="{{$vendor->order_pre_time}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="auto_reject_timeInput">
                            {!! Form::label('title', 'Auto Reject Time(In minutes, 0 for no rejection)',['class' => 'control-label']) !!}
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="auto_reject_time" type="text" value="{{$vendor->auto_reject_time}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="order_min_amountInput">
                            {!! Form::label('title', 'Order Min Amount',['class' => 'control-label']) !!}
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="order_min_amount" type="text" value="{{$vendor->order_min_amount}}">
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-info waves-effect waves-light w-100">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
            <form name="config-form" action="{{route('vendor.config.update', $vendor->id)}}" class="needs-validation" id="slot-configs" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-2"> <span class="">Commission</span> (Visible For Admin)</h4>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                        {!! Form::label('title', 'Can Add Category',['class' => 'control-label']) !!} 
                        <input type="checkbox" data-plugin="switchery" name="add_category" class="form-control can_add_category1" data-color="#43bee1" @if($vendor->add_category == 1) checked @endif >
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="commission_percentInput">
                            {!! Form::label('title', 'Commission Percent',['class' => 'control-label']) !!}
                            <input class="form-control" name="commission_percent" type="text" value="{{$vendor->commission_percent}}" onkeypress="return isNumberKey(event)">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="commission_fixed_per_orderInput">
                            {!! Form::label('title', 'Commission Fixed Per Order',['class' => 'control-label']) !!} 
                            <input class="form-control" name="commission_fixed_per_order" type="text" value="{{$vendor->commission_fixed_per_order}}" onkeypress="return isNumberKey(event)">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="commission_monthlyInput">
                            {!! Form::label('title', 'Commission Monthly',['class' => 'control-label']) !!}
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="commission_monthly" type="text" value="{{$vendor->commission_monthly}}">
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-info waves-effect waves-light w-100">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- end card-box -->



<div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mb-2"> <span class="">Active Main Category</h4>
                </div>
            </div>
            
            @foreach($categorList as $key => $list)
                <form name="config-form" id="categorForm_{{$list->id}}" action="{{route('vendor.category.update', $vendor->id)}}" class="needs-validation" id="slot-configs" method="post">
                    @csrf
                    <input type="hidden" name="vid" value="{{$vendor->id}}">
                    <input type="hidden" name="cid" value="{{$list->id}}">
                    <div class="row mb-2">
                        <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                            {!! Form::label('title', $list->slug, ['class' => 'control-label']) !!} 
                            <input type="checkbox" data-plugin="switchery" data-id="{{$list->id}}" name="category" class="form-control activeCategory" data-color="#43bee1" @if(!in_array($list->id, $blockedCategory)) checked @endif>
                        </div>
                    </div>
                </form>
            
            @endforeach
        </div>
    </div>
</div>

<div class="card-box">
    <h4 class="header-title mb-3">Users</h4>

    <div class="inbox-widget" data-simplebar style="max-height: 350px;">
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-2.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Tomaslau</p>
            <p class="inbox-item-text">I've finished it! See you so...</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-3.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Stillnotdavid</p>
            <p class="inbox-item-text">This theme is awesome!</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-4.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Kurafire</p>
            <p class="inbox-item-text">Nice to meet you</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>

        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-5.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Shahedk</p>
            <p class="inbox-item-text">Hey! there I'm available...</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-6.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Adhamdannaway</p>
            <p class="inbox-item-text">This theme is awesome!</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>

        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-3.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Stillnotdavid</p>
            <p class="inbox-item-text">This theme is awesome!</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
        <div class="inbox-item">
            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-4.jpg')}}" class="rounded-circle" alt=""></div>
            <p class="inbox-item-author">Kurafire</p>
            <p class="inbox-item-text">Nice to meet you</p>
            <p class="inbox-item-date">
                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
            </p>
        </div>
    </div> <!-- end inbox-widget -->

</div>
<script type="text/javascript">
    $('.activeCategory').change(function(){
        var id = $(this).data('id');
        console.log(id);
        $('#categorForm_'+id).submit();

        //$('.vendorRow').toggle();
    });
</script>