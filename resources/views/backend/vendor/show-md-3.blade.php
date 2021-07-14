<div class="card-box text-center p-0 overflow-hidden" style="">
    <div class="background pt-3 pb-2 px-2" style="background:url({{$vendor->banner['proxy_url'] . '200/100' . $vendor->banner['image_path']}}) no-repeat center center;background-size:cover;">
        <div class="vendor_text">
            <img src="{{$vendor->logo['proxy_url'] . '90/90' . $vendor->logo['image_path']}}" class="rounded-circle avatar-lg img-thumbnail"
                alt="profile-image">
                {{$vendor->status}}
            <h4 class="mb-0 text-white">{{ucfirst($vendor->name)}}</h4>
            <p class="text-white">{{$vendor->address}}</p>
            <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light openEditModal"  data-toggle="modal" data-target="#exampleModal"> Edit </button>
            @if($vendor->status == 0 && Auth::user()->is_superadmin == 1)
                <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light" id="approve_btn" data-vendor_id="{{$vendor->id}}" data-status="1">Accept</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light" id="reject_btn" data-vendor_id="{{$vendor->id}}" data-status="2">Reject</button>
            @else
                @if(Auth::user()->is_superadmin == 1)
                    <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light" id="block_btn" data-vendor_id="{{$vendor->id}}" data-status="{{$vendor->status == 2  ? '1' : '2'}}">{{$vendor->status == 2 ? 'Unblock' : 'Block'}}</button>
                @endif
                @if($client_preferences->need_dispacher_ride == 1)
                    <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light openConfirmDispatcher" data-id="{{ $vendor->id }}"> Login Into Dispatcher </button>
                @endif
            @endif
        </div>
    </div>
    <div class="text-left mt-0 p-3">
        <p class="text-muted font-13 mb-0">
           {{$vendor->desc}}
        </p>
    </div>
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
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="order_pre_time" type="text" value="{{$vendor->order_pre_time}}" {{$vendor->status == 1 ? '' : 'disabled'}}>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="auto_reject_timeInput">
                            {!! Form::label('title', 'Auto Reject Time(In minutes, 0 for no rejection)',['class' => 'control-label']) !!}
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="auto_reject_time" type="text" value="{{$vendor->auto_reject_time}}" {{$vendor->status == 1 ? '' : 'disabled'}}>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="order_min_amountInput">
                            {!! Form::label('title', 'Order Min Amount',['class' => 'control-label']) !!}
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="order_min_amount" type="text" value="{{$vendor->order_min_amount}}" {{$vendor->status == 1 ? '' : 'disabled'}}>
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-info waves-effect waves-light w-100" {{$vendor->status == 1 ? '' : 'disabled'}}>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
            <form name="config-form" action="{{route('vendor.config.update', $vendor->id)}}" class="needs-validation" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-2"> <span class="">Commission</span> (Visible For Admin)</h4>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                        {!! Form::label('title', '24*7 Availability',['class' => 'control-label']) !!} 
                        <input type="checkbox" data-plugin="switchery" name="show_slot" class="form-control" data-color="#43bee1" @if($vendor->show_slot == 1) checked @endif {{$vendor->status == 1 ? '' : 'disabled'}}>
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
                    <!-- <div class="col-md-12">
                        <div class="form-group" id="commission_monthlyInput">
                            {!! Form::label('title', 'Commission Monthly',['class' => 'control-label']) !!}
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="commission_monthly" type="text" value="{{$vendor->commission_monthly}}">
                        </div>
                    </div> -->
                    <div class="col-12">
                        <button class="btn btn-info waves-effect waves-light w-100" {{$vendor->status == 1 ? '' : 'disabled'}}>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style type="text/css">
    #nestable_list_1 ol, #nestable_list_1 ul{
        list-style-type: none;
    }
</style>
<div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-2"> <span class="">Category Setup</span> (Visible For Admin)</h4>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                        {!! Form::label('title', 'Can Add Category',['class' => 'control-label']) !!} 
                        <input type="checkbox" data-plugin="switchery" name="can_add_category" class="form-control can_add_category1" data-color="#43bee1" @if($vendor->add_category == 1) checked @endif {{$vendor->status == 1 ? '' : 'disabled'}}>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6 mb-3">
                        {!! Form::label('title', 'Vendor Detail To Show',['class' => 'control-label ']) !!}
                    </div>
                    <div class="col-md-6 mb-3">
                        <select class="selectize-select form-control assignToSelect" id="assignTo" {{$vendor->status == 1 ? '' : 'disabled'}}>
                            @foreach($templetes as $templete)
                                <option value="{{$templete->id}}" {{$vendor->vendor_templete_id == $templete->id ? 'selected="selected"' : ''}}>{{$templete->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        {!! Form::label('title', 'Vendor Category',['class' => 'control-label']) !!}
                        <div class="custom-dd dd nestable_list_1" id="nestable_list_1">
                            <ol class="dd-list">
                                @forelse($builds as $build)
                                <li class="dd-item dd3-item" data-category_id="{{$build['id']}}">
                                    <div class="dd3-content"> 
                                        <img class="rounded-circle mr-1" src="{{$build['icon']['proxy_url']}}30/30{{$build['icon']['image_path']}}"> {{$build['translation_one']['name']}}
                                        <span class="inner-div text-right">
                                            <a class="action-icon" data-id="3" href="javascript:void(0)">
                                                @if(in_array($build['id'], $VendorCategory))
                                                    <input type="checkbox" data-category_id="{{ $build['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" checked {{$vendor->status == 1 ? '' : 'disabled'}}>
                                                @else
                                                    <input type="checkbox" data-category_id="{{ $build['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" {{$vendor->status == 1 ? '' : 'disabled'}}>
                                                @endif
                                                <input type="hidden" value="{{ $build['id'] }}">
                                            </a>
                                        </span> 
                                    </div>
                                    @if(isset($build['children']))
                                    <ol class="dd-list">
                                        @forelse($build['children']  as $first_child)
                                        <li class="dd-item dd3-item" data-id="{{$first_child['id']}}">
                                            <div class="dd3-content"> 
                                                <img class="rounded-circle mr-1" src="{{$first_child['icon']['proxy_url']}}30/30{{$first_child['icon']['image_path']}}"> {{$first_child['translation_one']['name']}} 
                                                <span class="inner-div text-right">
                                                    <a class="action-icon" data-id="2" href="javascript:void(0)">
                                                        @if(in_array($first_child['id'], $VendorCategory))
                                                            <input type="checkbox" data-category_id="{{ $first_child['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" checked="" {{$vendor->status == 1 ? '' : 'disabled'}}>
                                                        @else
                                                            <input type="checkbox" data-category_id="{{ $first_child['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" {{$vendor->status == 1 ? '' : 'disabled'}}>
                                                        @endif
                                                    </a>
                                                </span>
                                            </div>
                                            @if(isset($first_child['children']))
                                                <ol class="dd-list">
                                                    @forelse($first_child['children'] as $second_child)
                                                        <li class="dd-item dd3-item" data-id="6">
                                                            <div class="dd3-content">
                                                                <img class="rounded-circle mr-1" src="{{$second_child['icon']['proxy_url']}}30/30{{$second_child['icon']['image_path']}}">{{$second_child['translation_one']['name']}}
                                                                    <span class="inner-div text-right">
                                                                        <a class="action-icon" data-id="6" href="javascript:void(0)">
                                                                            @if(in_array($second_child['id'], $VendorCategory))
                                                                                <input type="checkbox" data-category_id="{{ $second_child['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" checked="" {{$vendor->status == 1 ? '' : 'disabled'}}>
                                                                            @else
                                                                                <input type="checkbox" data-category_id="{{ $second_child['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" {{$vendor->status == 1 ? '' : 'disabled'}}>
                                                                            @endif
                                                                        </a>
                                                                    </span> 
                                                            </div>
                                                        </li>
                                                    @empty
                                                    @endforelse
                                                </ol>
                                            @endif
                                        </li>
                                        @empty
                                        @endforelse
                                    </ol>
                                    @endif
                                </li>
                                @empty
                                @endforelse
                            </ol>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@if(count($vendor->permissionToUser))
 <div class="card-box">
    <h4 class="header-title mb-3">Users</h4>
    <div class="inbox-widget" data-simplebar style="max-height: 350px;">
        @foreach($vendor->permissionToUser as $users)
        <div class="inbox-item">
            <div class="inbox-item-img">
                <img src="{{$users->user ? $users->user->image['proxy_url'].'40/40'.$users->user->image['image_path'] : asset('assets/images/users/user-2.jpg')}}" class="rounded-circle" alt="" >
                                
                {{-- <img src="{{asset('assets/images/users/user-2.jpg')}}" class="rounded-circle" alt=""> --}}
            </div>
            <p class="inbox-item-author">{{ $users->user->name??'' }}</p>
            <p class="inbox-item-text"><i class="fa fa-envelope" aria-hidden="true"> {{ $users->user->email??'' }}</i> <i class="fa fa-phone" aria-hidden="true"> {{ $users->user->phone_number??'' }}</i></p>
        </div>
        @endforeach
    </div>
</div> 
@endif
<div id="edit-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_edit_banner_form" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editCardBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitEditForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
    $(document).on('click', '#approve_btn, #reject_btn, #block_btn', function(){
        var that  = $(this);
        var status = that.data('status');
        var vendor_id = that.data('vendor_id');
        var text = that.text().toLowerCase();
        var message = "Are you sure want to "+text+" this vendor?";
        if(confirm(message)){
            $.ajax({
                type: "POST",
                url: "{{route('vendor.status')}}",
                data: { vendor_id: vendor_id , status:status},
                success: function(data) {
                    if(data.status == 'success'){
                       $.NotificationApp.send("Success", data.message, "top-right", "#5ba035", "success");
                       window.location.href = "{{ route('vendor.index') }}";
                    }
                }
            });
        }       
    });
    $(document).on('change', '.can_add_category1', function(){
        var vendor_id = "{{$vendor->id}}";
        var can_add_category = $(this).is(":checked");
        var url = "{{ url('client/vendor/activeCategory').'/'.$vendor->id}}"
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {vendor_id:vendor_id, can_add_category:can_add_category},
            success: function(response) {
                if (response.status == 'Success') {

                }
            }
        });
    });
    $(document).on('change', '#assignTo', function(){
        var assignTo = $(this).val();
        var vendor_id = "{{$vendor->id}}";
        var url = "{{ url('client/vendor/activeCategory').'/'.$vendor->id}}"
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {vendor_id:vendor_id, assignTo:assignTo},
            success: function(response) {
                if (response.status == 'Success') {

                }
            }
        });
    });
    $(document).on('change', '.activeCategory', function(){
        var vendor_id = "{{$vendor->id}}";
        var status = $(this).is(":checked");
        var category_id = $(this).data('category_id');
        var url = "{{ url('client/vendor/activeCategory').'/'.$vendor->id}}"
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {category_id: category_id, status:status, vendor_id:vendor_id},
            success: function(response) {
                if (response.status == 'Success') {
                    $('#category_list').html('');
                   $('#category_list').html('<option value="">Select Category...</option>');
                   $('#category_list').selectize()[0].selectize.destroy();
                   $.each(response.data, function (key, value) {
                        if(value.category.type_id == 1){
                           $('#category_list').append('<option value='+value.category_id+'>'+value.category.slug+'</option>');
                        }
                   });
                }
            }
        });
    });
});
</script>