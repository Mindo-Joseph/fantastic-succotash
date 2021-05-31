<div class="card-box text-center p-0 overflow-hidden" style="">
    
    <div class="background pt-3 pb-2 px-2" style="background:url({{$vendor->banner['proxy_url'] . '90/90' . $vendor->banner['image_path']}}) no-repeat center center;background-size:cover;">
        <div class="vendor_text">
            <img src="{{$vendor->logo['proxy_url'] . '90/90' . $vendor->logo['image_path']}}" class="rounded-circle avatar-lg img-thumbnail"
                alt="profile-image">

            <h4 class="mb-0 text-white">{{ucfirst($vendor->name)}}</h4>
            <p class="text-white">{{$vendor->address}}</p>

            <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light openEditModal"> Edit </button>
            <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light"> Block </button>
        </div>
    </div>
    <div class="text-left mt-0 p-3">
        <!-- <h4 class="font-13">Description :</h4> -->
        <p class="text-muted font-13 mb-0">
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
                        {!! Form::label('title', '24*7 Availability',['class' => 'control-label']) !!} 
                        <input type="checkbox" data-plugin="switchery" name="show_slot" class="form-control" data-color="#43bee1" @if($vendor->show_slot == 1) checked @endif >
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
<style type="text/css">
    #nestable_list_2 ol, #nestable_list_2 ul{
        list-style-type: none;

    }
</style>
<div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
            <form name="config-form" action="{{route('vendor.category.update', $vendor->id)}}" class="needs-validation" id="slot-configs" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-2"> <span class="">Category Setup</span> (Visible For Admin)</h4>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                        {!! Form::label('title', 'Can Add Category',['class' => 'control-label']) !!} 
                        <input type="checkbox" data-plugin="switchery" name="add_category" class="form-control can_add_category1" data-color="#43bee1" @if($vendor->add_category == 1) checked @endif >
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6 mb-3">
                        {!! Form::label('title', 'Vendor Detail To Show',['class' => 'control-label ']) !!}
                    </div>
                    <div class="col-md-6 mb-3">
                        <select class="selectize-select form-control assignToSelect" name="assignTo">
                            @foreach($templetes as $templete)
                                <option value="{{$templete->id}}">{{$templete->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        {!! Form::label('title', 'Vendor Category',['class' => 'control-label']) !!}
                        <div class="custom-dd dd" id="nestable_list_1">
                        <?php print_r($categoryToggle); ?>
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
    var CSRF_TOKEN = $("input[name=_token]").val();
    $(document).on('change', '.activeCategory', function(){
        var  that = $(this);
        var value = 0;
        var category_id = $(this).data('id');
        if(this.checked){
            value = 1;
        }
        document.getElementById('toggleStatusField').value = value;
        document.getElementById('toggleCategoryId').value = category_id;
        if(value == 0){
            $('#categoryToggleForm').submit();
        }else{
            $.ajax({
                type: "post",
                dataType: "json",
                url: "{{ route('category.parent.status', $vendor->id) }}",
                data: {
                    _token: CSRF_TOKEN,
                    value: value,
                    category_id: category_id
                },
                success: function(response) {
                    console.log(response);
                    if (response.status == 'Success') {
                        $('#categoryToggleForm').submit();
                    }
                   
                    /*if(response.status == 'Error'){
                        alert(response.message);
                    }*/
                },
                error: function (response) {
                    that.prop('checked', false);
                    let errors = response.responseJSON;
                    $.NotificationApp.send("Error", errors.message, "top-right", "#bf441d", "error");
                },
            });
        }  
    });
</script>