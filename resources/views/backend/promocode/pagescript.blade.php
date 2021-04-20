<script>
    $(".openPromoModal").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uri = "{{route('promocode.create')}}";
       
        var uid = $(this).attr('userId');
        if(uid > 0){
            uri = "<?php echo url('client/promocode'); ?>" + '/' + uid + '/edit';
        }

        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function (data) {
                if(uid > 0){
                    $('#add-promo-form #editCardBox').html(data.html);
                    $('#add-promo-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    elems1 = document.getElementsByClassName('switch1Edit');
                    elems2 = document.getElementsByClassName('switch2Edit');
                    var switchery = new Switchery(elems1[0]);
                    var switchery = new Switchery(elems2[0]);
                    
                }else{
                    $('#add-promo-form #addCardBox').html(data.html);
                    $('#add-promo-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    elems1 = document.getElementsByClassName('switch1');
                    elems2 = document.getElementsByClassName('switch2');
                    var switchery = new Switchery(elems1[0]);
                    var switchery = new Switchery(elems2[0]);
                    
                }

            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    

    function runPicker(){
        $('.datetime-datepicker').flatpickr({
            enableTime: true,
            startDate: new Date(),
            minDate: new Date(),
            dateFormat: "Y-m-d H:i"
        });

        $('.selectpicker').selectpicker();
    }



    $(document).on('change', '.assignToSelect', function(){
        var val = $(this).val();
        if(val == 'category'){
            $('.modal .category_vendor').show();
            $('.modal .category_list').show();
            $('.modal .vendor_list').hide();
        }else if(val == 'vendor'){
            $('.modal .category_vendor').show();
            $('.modal .category_list').hide();
            $('.modal .vendor_list').show();
        }else{
            $('.modal .category_vendor').hide();
            $('.modal .category_list').hide();
            $('.modal .vendor_list').hide();
        }
    });

    $(document).on('click', '.submitAddForm', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('save_banner_form');
        var formData = new FormData(form);
        var url =  document.getElementById('bannerId').getAttribute('url');
        saveData(formData, 'add', url );

    });

    $(document).on('click', '.submitEditForm', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('save_edit_banner_form');
        var formData = new FormData(form);
        var url =  document.getElementById('bannerId').getAttribute('url');

        saveData(formData, 'edit', url);

    });

    function saveData(formData, type, banner_uri){
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
            url: banner_uri,
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
                        $("#" + key + "Input input").addClass("is-invalid");
                        $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });
    }

    $("#banner-datatable tbody").sortable({
        placeholder : "ui-state-highlight",
        handle: ".dragula-handle",
        update  : function(event, ui)
        {
            var post_order_ids = new Array();
            $('#post_list tr').each(function(){
                post_order_ids.push($(this).data("row-id"));
            });
            console.log(post_order_ids);
            saveOrder(post_order_ids);
        }
    });

    var CSRF_TOKEN = $("input[name=_token]").val();
    function saveOrder(orderVal){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ url('client/banner/saveOrder') }}",
            data: {
                _token: CSRF_TOKEN,
                order: orderVal
            },
            success: function(response) {

                if (response.status == 'success') {
                }
                return response;
            }
        });
    }

    $("#user-modal #add_user").submit(function(e) {
            e.preventDefault();
    });

    $(document).on('click', '.addVendorForm', function() { 
        var form =  document.getElementById('add_customer');
        var formData = new FormData(form);
        var urls = "{{URL::route('vendor.store')}}";
        saveCustomer(urls, formData, inp = '', modal = 'user-modal');
    });

    $("#edit-user-modal #edit_user").submit(function(e) {
            e.preventDefault();
    });

    $(document).on('click', '.editVendorForm', function(e) {
        e.preventDefault();
        var form =  document.getElementById('edit_customer');
        var formData = new FormData(form);
        var urls =  document.getElementById('customer_id').getAttribute('url');
        saveCustomer(urls, formData, inp = 'Edit', modal = 'edit-user-modal');
        console.log(urls);
    });
  
</script>
