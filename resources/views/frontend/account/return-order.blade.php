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
                            <li><a href="{{route('user.wallet')}}">My Wallet</a></li>
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
                            <h5>Here are your for return product !</h5>
                        </div>
                        <div class="row">
                            <div class="container">
                                @foreach($order->vendors as $key => $vendor)    
                                @foreach($vendor->products as  $key => $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input id="item_one{{$key}}" type="hidden" name="return_ids" value="{{ $product->id }}" required>
                                            <label class="order-items d-flex" for="item_one{{$key}}">  
                                                <div class="item-img mx-1">
                                                    <img src="{{ $product->image['proxy_url'].'74/100'.$product->image['image_path'] }}" alt="">
                                                </div>    
                                                <div class="items-name ml-2">
                                                    <h4 class="mt-0 mb-1"><b>{{ $product->product_name }}</b></h4>
                                                    <label><b>Quantity</b>: {{ $product->quantity }}</label>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                   
                                    
                                </tr>
                                @endforeach  
                            @endforeach  
                                <h2 >{{__('Choose files to share')}}</h2>
                                <form id="return-upload-form" class="theme-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                                        @csrf
                                    <input type="hidden" name="order_vendor_product_id" value="{{app('request')->input('return_ids')}}">
                                    <div class="row rating_files">
                                        <div class="col-12">
                                        <label>{{__('Upload Images')}}</label>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <div class="file file--upload">
                                                <label for="input-file">
                                                    <span class="plus_icon"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                </label>
                                                <input id="input-file" type="file" name="images[]" accept="image/*"  multiple>
                                                
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <span class="row show-multiple-image-preview" id="thumb-output">
                                            </span>
                                        </div>
                                        
                                    </div>
                    
                                    
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>{{__('Reason for return product')}}</label>
                                            <select class="form-control" name="reason" id="reason">
                                                @foreach ($reasons as $reason)
                                                    <option value="{{$reason->title}}">{{$reason->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>
                                    <div class="form-group">
                                        <label>{{__('Comments (Opitonal)')}}:</label>
                                        <textarea class="form-control" name="" id="comments" cols="20" rows="4"></textarea>
                                    </div>
                                    <button class="btn btn-solid mt-3" id="return_form_button">{{__('Request')}}</button>
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
  $(function() {
// Multiple images preview with JavaScript
var ShowMultipleImagePreview = function(input, imgPreviewPlaceholder) {
if (input.files) {
var filesAmount = input.files.length;
for (i = 0; i < filesAmount; i++) {
var reader = new FileReader();
reader.onload = function(event) {
$($.parseHTML('<img>')).addClass('col-6 col-md-3 col-lg-2 update_pic').attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
}
reader.readAsDataURL(input.files[i]);
}
}
};
$('#input-file').on('change', function() {
ShowMultipleImagePreview(this, 'span.show-multiple-image-preview');
});
}); 

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

$('#return-upload-form').submit(function(e) {
e.preventDefault();

var formData = new FormData(this);
let TotalImages = $('#input-file')[0].files.length; //Total Images
let images = $('#input-file')[0];
for (let i = 0; i < TotalImages; i++) {
formData.append('images' + i, images.files[i]);
}
formData.append('TotalImages', TotalImages);
$.ajax({
type:'POST',
url: "{{ route('update.order.return')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
beforeSend: function () {
    if(TotalImages > 0)
        $("#return_form_button").html('<i class="fa fa-spinner fa-spin fa-custom"></i> Loading').prop('disabled', true);
    },
success: (data) => {
if(data.status == 'Success')
    {
      $("#return_form_button").html('Submitted');
    }else{
        $('#error-msg').text(data.message);
        $("#return_form_button").html('Request').prop('disabled', false);
    }
},
error: function(data){
    $('#error-msg').text(data.message);
    $("#review_form_button").html('Request').prop('disabled', false);
}
});
});
       
</script>

@endsection