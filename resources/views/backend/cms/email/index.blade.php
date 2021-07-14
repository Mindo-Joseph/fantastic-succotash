@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Emails'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Emails</h4>
            </div>
        </div>
    </div>

    <div class="row cms-cols">
        <div class="col-lg-5 col-xl-3 mb-2">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4>List</h4>
                        <!-- <button class="btn btn-info add_cms_page" data-toggle="modal">
                            <i class="mdi mdi-plus-circle"></i> Add
                        </button> -->
                    </div> 
                   <div class="table-responsive pages-list-data">
                        <table class="table table-striped w-100">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">Template Name</th>
                                    <th class="text-right border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr class="page-title active-page page-detail" data-page_id="" data-show_url="">
                                        <td>
                                            <a class="text-body" href="javascript:void(0)" id="">Orders</a>
                                        </td>
                                        <td align="right">
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-pencil-box-outline"></i>
                                            </a>
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                   </div>
                </div>            
            </div>
        </div>
        <div class="col-lg-7 col-xl-9 mb-2">
            <div class="card">
                <div class="card-body p-3" id="edit_page_content">
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-info" id=""> Publish</button>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="page_id" value="">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="title" class="control-label">Title</label>
                                    <input class="form-control" id="edit_title" placeholder="Meta Title" name="meta_title" type="text">
                                    <span class="text-danger error-text updatetitleError"></span>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="title" class="control-label">Description</label>
                                    <textarea class="form-control" id="edit_meta_keyword" placeholder="Meta Keyword" rows="6" name="meta_keyword" cols="10"></textarea>
                                </div>
                            </div>         
                        </div>
                        
                    </div>
                </div>            
            </div>
        </div>         
    </div>
</div>
@endsection