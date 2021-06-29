<form id="review-upload-form" class="theme-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="order_vendor_product_id" value="143">
    <textarea class="form-control" maxlength="500" name="hidden_review" hidden>{{$rating_details->review??''}}</textarea>
    <div class="rating-form">
        <fieldset class="form-group">
            <legend class="form-legend">Rating:</legend> 
            <div class="form-item">

            <input id="rating-5" name="rating" type="radio" value="5" {{ $rating_details->rating == 5 ? 'checked' : '' }}/>
                <label for="rating-5" data-value="5">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">5</span>
                </label>
                <input id="rating-4" name="rating" type="radio" value="4"  {{ $rating_details->rating == 4 ? 'checked' : '' }}/>
                <label for="rating-4" data-value="4">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">4</span>
                </label>
                <input id="rating-3" name="rating" type="radio" value="3"  {{ $rating_details->rating == 3 ? 'checked' : '' }}/>
                <label for="rating-3" data-value="3">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">3</span>
                </label>
                <input id="rating-2" name="rating" type="radio" value="2"  {{ $rating_details->rating == 2 ? 'checked' : '' }}/>
                <label for="rating-2" data-value="2">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">2</span>
                </label>
                <input id="rating-1" name="rating" type="radio" value="1"  {{ $rating_details->rating == 1 ? 'checked' : '' }}/>
                <label for="rating-1" data-value="1">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">1</span>
                </label>

                <div class="form-output">
                    ? / 5
                </div>

            </div>
        </fieldset>
    </div>

    <div class="row rating_files">
        <div class="col-12">
            <h4>Upload Images</h4>
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
                @foreach ($rating_details->reviewFiles as $files)
                    <img class="col-6 col-md-3 col-lg-2 update_pic" src="{{$files->file['proxy_url'].'300/300'.$files->file['image_path']}}">
                @endforeach
                
            </span>
        </div> 
        
    </div>

    <div class="form-row">
                                            
        <div class="col-md-12 mb-3">
            <label for="review">Review Title</label>
            <textarea class="form-control"
                placeholder="Wrire Your Testimonial Here"
                id="exampleFormControlTextarea1" rows="4"  name="review" maxlength="500" required>{{$rating_details->review}}</textarea>
        </div>
        <span class="text-danger" id="error-msg"></span>
        <span class="text-success" id="success-msg"></span>
        <div class="col-md-12">
            <button class="btn btn-solid buttonload" type="submit" id="review_form_button">Submit Your Review</button>
        </div>
        
    </div>
  </form>



  <script type="text/javascript">
    $(document).ready(function (e) {

  $('input[type=radio][name=rating]').on('change', function() {
    $('.rating_files').show(); 
    $('.form-row').show();    
    $(this).closest("form").submit();
    });


$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
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
$('#review-upload-form').submit(function(e) {
e.preventDefault();

var formData = new FormData(this);
let review = $('#exampleFormControlTextarea1').val();
let TotalImages = $('#input-file')[0].files.length; //Total Images
let images = $('#input-file')[0];
for (let i = 0; i < TotalImages; i++) {
formData.append('images' + i, images.files[i]);
}
formData.append('TotalImages', TotalImages);
$.ajax({
type:'POST',
url: "{{ route('update.order.rating')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
beforeSend: function () {
    if(TotalImages > 0 && review.length > 0)
        $("#review_form_button").html('<i class="fa fa-spinner fa-spin fa-custom"></i> Loading').prop('disabled', true);
    },
success: (data) => {
if(data.status == 'Success')
    {
        if(TotalImages == 0  && review.length == 0){
            $("#review_form_button").html('Submit Your Review').prop('disabled', false);
        }
        else
        $("#review_form_button").html('Submitted');
    }else{
        $('#error-msg').text(data.message);
        $("#review_form_button").html('Submit Your Review').prop('disabled', false);
    }
},
error: function(data){
    $('#error-msg').text(data.message);
    $("#review_form_button").html('Submit Your Review').prop('disabled', false);
}
});
});

});
</script>
