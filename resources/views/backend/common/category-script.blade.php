<script type="text/javascript">
    $(".openCategoryModal").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uri = "{{route('category.create')}}";
       
        var id = $(this).attr('dataid');
        if(id > 0){
            uri = "<?php echo url('client/category'); ?>" + '/' + id + '/edit';
        }

        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function (data) {
                if(id > 0){
                    $('#edit-category-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#edit-category-form #editCategoryBox').html(data.html);
                    elems1 = document.getElementsByClassName('switch1Edit');
                    elems2 = document.getElementsByClassName('switch2Edit');
                    var switchery = new Switchery(elems1[0]);
                    var switchery = new Switchery(elems2[0]);

                }else{

                    $('#add-category-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#add-category-form #AddCategoryBox').html(data.html);
                    elems1 = document.getElementsByClassName('switch1');
                    elems2 = document.getElementsByClassName('switch2');
                    var switchery = new Switchery(elems1[0]);
                    var switchery = new Switchery(elems2[0]);

                }

                $('.dropify').dropify();
                $('.selectize-select').selectize(); 
                
            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.addCategorySubmit', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('addCategoryForm');
        var formData = new FormData(form);
        var url =  "{{route('category.store')}}";
        saveCategory(formData, 'add', url );

    });

    $(document).on('click', '.editCategorySubmit', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('editCategoryForm');
        var formData = new FormData(form);
        var url =  document.getElementById('cateId').getAttribute('url');

        saveCategory(formData, 'edit', url);

    });

    function saveCategory(formData, type, base_uri){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: base_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {

                if (response.status == 'success') {
                    $(".modal .close").click();
                    location.reload(); 
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        if(key == 'name.0'){
                            $("#nameInput input").addClass("is-invalid");
                            $("#nameInput span.invalid-feedback").children("strong").text('The default language name field is required.');
                            $("#nameInput span.invalid-feedback").show();
                        }else{
                            $("#" + key + "Input input").addClass("is-invalid");
                            $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                            $("#" + key + "Input span.invalid-feedback").show();
                        }
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });
    }

</script>