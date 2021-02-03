@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Vendor'])

@section('css')
<link href="{{asset('assets/libs/fullcalendar-list/fullcalendar-list.min.css')}}" rel="stylesheet" type="text/css" />

<style type="text/css">
    .pac-container, .pac-container .pac-item { z-index: 99999 !important; }
</style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ucfirst($vendor->name)}} profile</h4>
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <span>{!! \Session::get('success') !!}</span>
                    </div>
                    @endif
                    @if (\Session::has('error_delete'))
                    <div class="alert alert-danger">
                        <span>{!! \Session::get('error_delete') !!}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-3 col-xl-3">
                @include('backend.vendor.show-md-3')
            </div>

            <div class="col-lg-9 col-xl-9">
                <div class="">
                    <ul class="nav nav-pills navtab-bg nav-justified">
                        <li class="nav-item">
                            <a href="{{ route('vendor.show', $vendor->id) }}" aria-expanded="false" class="nav-link {{($tab == 'configuration') ? 'active' : '' }}">
                                Configuration
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendor.categories', $vendor->id) }}"  aria-expanded="true" class="nav-link {{($tab == 'category') ? 'active' : '' }}">
                                Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendor.catalogs', $vendor->id) }}"  aria-expanded="false" class="nav-link {{($tab == 'catalog') ? 'active' : '' }}">
                                Catalog
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{($tab == 'configuration') ? 'active show' : '' }} card-body" id="configuration">

                        </div> <!-- end tab-pane -->
                        <!-- end about me section content -->

                        <div class="tab-pane {{($tab == 'category') ? 'active show' : '' }}" id="category">

                        </div>
                        <!-- end timeline content-->

                        <div class="tab-pane {{($tab == 'catalog') ? 'active show' : '' }}" id="catalog">
                           <div class="row card-box">
                                <h4 class="mb-4"> Catalog</h4>
                                <div class="col-md-12">
                                    <div class="row mb-2">
                                        <div class="col-md-8">
                                            Catalog
                                        </div>
                                        <div class="col-sm-4 text-right">
                                          <a class="btn btn-blue waves-effect waves-light text-sm-right addProductBtn" dataid="0" href="javascript:void(0);"><i class="mdi mdi-plus-circle mr-1"></i> Add Product
                                          </a>
                                      </div>
                                    </div>

                                    <div class="table-responsive">
                                      <table class="table table-centered table-nowrap table-striped" id="">
                                          <thead>
                                              <tr>
                                                  <th>SKU</th>
                                                  <th>Name</th>
                                                  <th>New</th>
                                                  <th>Featured</th>
                                                  <th>Is live</th>
                                                  <th>Physical</th>
                                                  <th>Required Shipping</th>
                                                  <th>Has Inventory</th>
                                                  <th>Has Variant</th>
                                                  <th>Action</th>
                                              </tr>
                                          </thead>
                                          <tbody id="post_list">
                                              @foreach($products as $prod)
                                              <tr data-row-id="{{$prod->id}}">
                                                  <td> 
                                                     <!-- <img src="{{ url('storage/'.$prod->logo)}}" alt="{{$prod->id}}" width="50" height="50"> -->
                                                     {{ $prod->sku }}
                                                  </td>
                                                  <td> {{ (isset($prod->english->title) && !empty($prod->english->title)) ? $prod->english->title : '' }} </td>
                                                  <td> {{ ($prod->is_new == 0) ? 'No' : 'Yes' }}</td>
                                                  <td> {{ ($prod->is_featured == 0) ? 'No' : 'Yes' }}</td>
                                                  <td> {{ ($prod->is_live == 0) ? 'No' : 'Yes' }}</td>
                                                  <td> {{ ($prod->is_physical == 0) ? 'No' : 'Yes' }}</td>
                                                  <td> {{ ($prod->requires_shipping == 0) ? 'No' : 'Yes' }}</td>
                                                  <td> {{ ($prod->has_inventory == 0) ? 'No' : 'Yes' }}</td>
                                                  <td> {{ (!empty($prod->variantSet) && count($prod->variantSet) > 0) ? 'Yes' : 'No' }}</td>
                                                  <td> 
                                                    <div class="form-ul" style="width: 60px;">
                                                      <div class="inner-div" style="float: left;">
                                                        <a class="action-icon" href="{{ route('product.edit', $prod->id) }}" userId="{{$prod->id}}"><h3> <i class="mdi mdi-square-edit-outline"></i></h3></a> 
                                                      </div>
                                                      <div class="inner-div">
                                                          <form method="POST" action="">
                                                              @csrf
                                                              @method('DELETE')
                                                              <div class="form-group">
                                                                 <button type="submit" onclick="return confirm('Are you sure? You want to delete the vendor.')" class="btn btn-primary-outline action-icon"><h3><i class="mdi mdi-delete"></i></h3></button>
                                                              </div>
                                                          </form>
                                                      </div>
                                                    </div>
                                                  </td>
                                              </tr>
                                             @endforeach
                                          </tbody>
                                      </table>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end tab-content -->
                </div> <!-- end card-box-->
            </div> 
        </div>
    </div>
    <div class="row address" id="def" style="display: none;">
        <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
    </div>

<div id="add-product" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Product</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <form id="save_product_form" method="post" enctype="multipart/form-data" action="{{route('product.store')}}">
            @csrf
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12 card-box">
                      <div class="row mb-2">
                        <div class="col-6 mb-2">
                            {!! Form::label('title', 'Product Type',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="typeSelectBox" name="type_id">
                                @foreach($typeArray as $type)
                                    <option value="{{$type->id}}">{{$type->title}}</option>
                                @endforeach
                            </select>

                            {!! Form::hidden('vendor_id', $vendor->id) !!}
                        </div>

                        <div class="col-6 mb-2" id="">
                          <div class="form-group" id="skuInput">
                            {!! Form::label('title', 'SKU (Allowed Keys -> a-z,A-Z,0-9,-,_)',['class' => 'control-label']) !!}
                            <span class="text-danger">*</span>
                            {!! Form::text('sku', null, ['class'=>'form-control','id' => 'sku', 'onkeypress' => 'return alplaNumeric(event)', 'placeholder' => 'Apple-iMac']) !!}

                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                          </div>
                        </div>

                        <div class="col-6" style="cursor: not-allowed;">
                            {!! Form::label('title', 'Url Slug',['class' => 'control-label']) !!}
                            {!! Form::text('product_url', null, ['class'=>'form-control', 'id' => 'product_url', 'placeholder' => 'Apple iMac', 'style' => 'pointer-events:none;']) !!}
                        </div>

                        <div class="col-6">
                          <div class="form-group" id="categoryInput">
                            {!! Form::label('title', 'Category',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="category_list" name="category[]">
                                <option value="">Select Category...</option>
                                @foreach($categories as $cate)
                                    <option value="{{$cate->id}}">{{$cate->english->name}}</option>
                                @endforeach
                            </select>

                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue waves-effect waves-light submitProduct">Submit</button>
            </div>
        </form>
    </div>
  </div>
</div>
<script type="text/javascript">
   
       /*$('#submitButton').on('click', function(e){
           // We don't want this to act as a link so cancel the link action
           e.preventDefault();
           doSubmit();
       });*/
       
       /*$('#deleteButton').on('click', function(e){
           // We don't want this to act as a link so cancel the link action
           e.preventDefault();
           doDelete();
       });*/
       
       /*function doDelete(){
           $("#calendarModal").modal('hide');
           var eventID = $('#eventID').val();
           $.ajax({
               url: 'index.php',
               data: 'action=delete&id='+eventID,
               type: "POST",
               success: function(json) {
                   if(json == 1)
                        $("#calendar").fullCalendar('removeEvents',eventID);
                   else
                        return false;
                    
                   
               }
           });
       }*/
       /*function doSubmit(){
           $("#createEventModal").modal('hide');
           var title = $('#title').val();
           var startTime = $('#startTime').val();
           var endTime = $('#endTime').val();
           
           $.ajax({
               url: 'index.php',
               data: 'action=add&title='+title+'&start='+startTime+'&end='+endTime,
               type: "POST",
               success: function(json) {
                   $("#calendar").fullCalendar('renderEvent',
                   {
                       id: json.id,
                       title: title,
                       start: startTime,
                       end: endTime,
                   },
                   true);
               }
           });
           
       }
    });*/

    $('.addProductBtn').click(function(){
      $('#add-product').modal({
            keyboard: false
        });
    });
    var regexp = /^[a-zA-Z0-9-_]+$/;
    function alplaNumeric(evt){
        var charCode = String.fromCharCode(event.which || event.keyCode);

        if (!regexp.test(charCode)){
            return false;
        }
        var n1 = document.getElementById('sku');
        var n2 = document.getElementById('product_url');
        n2.value = n1.value+charCode;
        return true;
    }

    $(document).on('click', '.submitProduct', function(e) { 
        var form =  document.getElementById('save_product_form');
        var formData = new FormData(form);

       console.log('asdas');
        $.ajax({
            type: "post",
            url: "{{route('product.validate')}}",
            data: formData,
            contentType: false,
            processData: false,
            success: function (resp) {
              console.log(resp);
                if(resp.status == 'success'){
                    $('#save_product_form').submit();
                }
            },
            error: function (response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                      if(key == 'category.0'){
                        $("#categoryInput input").addClass("is-invalid");
                        $("#categoryInput span.invalid-feedback").children("strong").text('The category field is required.');
                        $("#categoryInput span.invalid-feedback").show();
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
    });

</script>


@include('backend.vendor.modals')
@endsection

@section('script')

@include('backend.vendor.pagescript')

@endsection