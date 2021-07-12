@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Pages'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-8">
            <div class="page-title-box">
                <h4 class="page-title">Pages</h4>
            </div>
        </div>
    </div>
    <div class="row cms-cols">
        <div class="col-lg-5 col-xl-3 mb-2">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4>List</h4>
                        <button class="btn btn-info add_cms_page" data-toggle="modal">
                            <i class="mdi mdi-plus-circle"></i> Add
                        </button>
                    </div> 
                   <div class="table-responsive pages-list-data">
                        <table class="table table-striped w-100">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">Page Name</th>
                                    <th class="text-right border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                    <tr class="page-title active-page page-detail" data-page_id="{{$page->id}}" data-show_url="{{route('cms.page.show', ['id'=> $page->id])}}">
                                        <td>
                                            <a class="text-body" href="javascript:void(0)" id="text_body_{{$page->id}}">{{$page->primary->title}}</a>
                                        </td>
                                        <td align="right">
                                            @if(!in_array($page->id, [1,2]))
                                                <a class="text-body delete-page" href="javascript:void(0)" data-page_id="{{$page->id}}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                   </div>
                </div>            
            </div>
        </div>
        <div class="col-lg-7 col-xl-9 mb-2">
            <div class="card">
                <div class="card-body p-3" id="edit_page_content">
                    <div class="row mb-2">                        
                        <div class="offset-xl-6 col-md-4 col-xl-2">
                            <div class="form-group mb-0">
                                <select class="form-control" id="client_language">
                                   @foreach($client_languages as $client_language)
                                    <option value="{{$client_language->langId}}">{{$client_language->langName}}</option>
                                   @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-2">
                            <div class="form-group mb-0">
                                <select class="form-control" id="published">
                                    <option value="0">Draft</option>
                                    <option value="1">Publish</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-2 text-right">
                            <button type="button" class="btn btn-info w-100" id="update_page_btn"> Update</button>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="page_id" value="">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="title" class="control-label">Title</label>
                                    <input class="form-control" id="edit_title" placeholder="Meta Title" name="meta_title" type="text">
                                    <span class="text-danger error-text updatetitleError"></span>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="title" class="control-label">Meta Keyword</label>
                                    <textarea class="form-control" id="edit_meta_keyword" placeholder="Meta Keyword" rows="3" name="meta_keyword" cols="10"></textarea>
                                </div>
                            </div>         
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="title" class="control-label">Meta Title</label>
                                    <input class="form-control" id="edit_meta_title" placeholder="Meta Title" name="meta_title" type="text">
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="title" class="control-label">Meta Description</label>
                                    <textarea class="form-control" id="edit_meta_description" placeholder="Meta Description" rows="3" name="meta_description" cols="10"></textarea>
                                </div>                               
                            </div>         
                        </div>
                        <div class="col-12 mb-3">
                            <label for="title" class="control-label">Description</label>
                            <textarea class="form-control" id="edit_description" placeholder="Meta Description" rows="9" name="meta_description" cols="100"></textarea>
                            <span class="text-danger error-text updatedescrpitionError"></span>
                        </div>
                    </div>
                </div>            
            </div>
        </div>         
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        setTimeout(function(){ 
            $('tr.page-title:first').trigger('click');
        }, 500);
        $(document).on("click","#client_language",function() {
            $('tr.page-title:first').trigger('click');
        });
        $(document).on("click",".page-detail",function() {
            $('#edit_page_content #edit_description').val('');
            $('#edit_page_content #edit_description').summernote('destroy');
            let url = $(this).data('show_url');
            let language_id = $('#edit_page_content #client_language :selected').val();
            $.get(url, {language_id:language_id},function(response) {
              if(response.status == 'Success'){
                if(response.data){
                    $('#edit_page_content #page_id').val(response.data.id);
                    if(response.data.translation){
                        $('#edit_page_content #edit_title').val(response.data.translation.title);
                        $("#edit_page_content #published").val(response.data.translation.is_published);
                        $('#edit_page_content #edit_meta_title').val(response.data.translation.meta_title);
                        $('#edit_page_content #edit_description').val(response.data.translation.description);
                        $('#edit_page_content #edit_meta_keyword').val(response.data.translation.meta_keyword);
                        $('#edit_page_content #edit_meta_description').val(response.data.translation.meta_description);
                        $('#edit_page_content #edit_description').summernote({'height':300});
                    }else{
                      $(':input:text').val('');
                    }
                }else{
                    $(':input:text').val('');
                    $('#edit_page_content #page_id').val('');
                }
              }
            });
        });
        $(document).on("click",".add_cms_page",function() {
            $('.page-heading').html('Add Page Content');
            $("#update_page_btn").html('Add');
            $('#edit_page_content #page_id').val('');
            $('#edit_page_content #edit_title').val('');
            $('#edit_page_content #edit_meta_title').val('');
            $('#edit_page_content #edit_description').summernote('reset');
            $('#edit_page_content #edit_meta_keyword').val('');
            $('#edit_page_content #edit_meta_description').val('');
        });  
        $(document).on("click",".delete-page",function() {
            var page_id = $(this).data('page_id');
            let destroy_url = "{{route('cms.page.delete')}}";
            if (confirm('Are you sure?')) {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: destroy_url,
                    data: {page_id: page_id},
                    success: function(response) {
                        if (response.status == "Success") {
                            $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                            setTimeout(function() {
                                location.reload()
                            }, 2000);
                        }
                    }
                });
            }
        });
        $(document).on("click","#update_page_btn",function() {
            var update_url = "{{route('cms.page.update')}}";
            let page_id = $('#edit_page_content #page_id').val();
            if(page_id == ''){
                var update_url = "{{route('cms.page.create')}}";
            }
            let edit_title = $('#edit_page_content #edit_title').val();
            let is_published = $('#edit_page_content #published option:selected').val();
            let language_id = $('#edit_page_content #client_language :selected').val();
            let edit_meta_title = $('#edit_page_content #edit_meta_title').val();
            let edit_description = $('#edit_page_content #edit_description').val();
            let edit_meta_keyword = $('#edit_page_content #edit_meta_keyword').val();
            let edit_meta_description = $('#edit_page_content #edit_meta_description').val();
            var data = { page_id: page_id, is_published: is_published, edit_title: edit_title,edit_meta_title:edit_meta_title, edit_description:edit_description, edit_meta_keyword:edit_meta_keyword, edit_meta_description:edit_meta_description,language_id:language_id};
            $.post(update_url, data, function(response) {
              $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
              $('#text_body_'+response.data.id).html(response.data.title);
              setTimeout(function() {
                    location.reload()
                }, 2000);
            }).fail(function(response) {
                $('#edit_page_content .updatetitleError').html(response.responseJSON.errors.edit_title[0]);
                $('#edit_page_content .updatedescrpitionError').html(response.responseJSON.errors.edit_description[0]);
            });
        });
    });
</script>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
@endsection