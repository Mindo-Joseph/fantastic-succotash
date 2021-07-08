@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Pages'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
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
                        <button class="btn btn-info" data-toggle="modal" data-target="#add_cms_page"><i class="mdi mdi-plus-circle"></i> Add</button>
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
                                <tr>
                                    <td>
                                        <a class="text-body" href="#">Terms & Conditions</a>
                                    </td>
                                    <td align="right">
                                        <a class="text-body" href="#"><i class="mdi mdi-square-edit-outline"></i></a>
                                        <a class="text-body" href="#"><i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a class="text-body" href="#">Privacy Policy</a>
                                    </td>
                                    <td align="right">
                                        <a class="text-body" href="#"><i class="mdi mdi-square-edit-outline"></i></a>
                                        <a class="text-body" href="#"><i class="mdi mdi-delete"></i></a>
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
                <div class="card-body p-3">
                    <div class="row mb-2 align-items-center">
                        <div class="col-8">
                            <h4 class="m-0">Page Content</h4>
                        </div>
                        <div class="col-4 text-right" style="margin: auto;">
                            <button type="button" class="btn btn-info waves-effect waves-light text-sm-right saveProduct"> Update</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label for="title" class="control-label">Title</label>
                                    <input class="form-control" id="meta_title" placeholder="Meta Title" name="meta_title" type="text">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="title" class="control-label">Meta Keyword</label>
                                    <textarea class="form-control" id="meta_keyword" placeholder="Meta Keyword" rows="3" name="meta_keyword" cols="50"></textarea>
                                </div>
                            </div>         
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label for="title" class="control-label">Meta Title</label>
                                    <input class="form-control" id="meta_title" placeholder="Meta Title" name="meta_title" type="text">
                                </div>
                                
                                <div class="col-12 mb-2">
                                    <label for="title" class="control-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" placeholder="Meta Description" rows="3" name="meta_description" cols="50"></textarea>
                                </div>                               
                            </div>         
                        </div>
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Description</label>
                            <textarea class="form-control" id="meta_description" placeholder="Meta Description" rows="9" name="meta_description" cols="50"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="page-content mb-3">
                                <h6 class="text-uppercase mb-2">Lorem ipsum dolor sit amet. </h6>
                                <p>We provide Visitors (as defined below) with access to the Website and Registered Members (as defined below) with access to the Platform subject to the following Terms of Use. By browsing the public areas of the Website, you acknowledge that you have read, understood, and agree to be legally bound by these Terms of Use and our Privacy Policy, which is hereby incorporated by reference (collectively, this “Agreement”). If you do not agree to any of these terms, then please do not use the Website, the App, and/or the Platform. We may change the terms and conditions of these Terms of Use from time to time with or without notice to you.</p>
                                <p>We provide Visitors (as defined below) with access to the Website and Registered Members (as defined below) with access to the Platform subject to the following Terms of Use.</p>
                            </div>
                            <div class="page-content mb-3">
                                <h6 class="text-uppercase mb-2">DESCRIPTION AND USE OF PLATFORM</h6>
                                <p>We provide Visitors (as defined below) with access to the Website and Registered Members (as defined below) with access to the Platform subject to the following Terms of Use.</p>
                            </div>
                            <div class="page-content mb-3">
                                <h6 class="text-uppercase mb-2">DESCRIPTION AND USE OF PLATFORM</h6>
                                <p>We provide Visitors (as defined below) with access to the Website and Registered Members (as defined below) with access to the Platform subject to the following Terms of Use. By browsing the public areas of the Website, you acknowledge that you have read, understood, and agree to be legally bound by these Terms of Use and our Privacy Policy, which is hereby incorporated by reference (collectively, this “Agreement”). If you do not agree to any of these terms, then please do not use the Website, the App, and/or the Platform. We may change the terms and conditions of these Terms of Use from time to time with or without notice to you.</p>
                                <div class="upadted_date text-uppercase">
                                    Updated on 12/07/2020
                                </div>
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
        </div>         
    </div>
</div>


<!-- Modal -->
<div class="modal fade cms-page" id="add_cms_page" tabindex="-1" aria-labelledby="add_cms_pageLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="add_cms_pageLabel">Add New Page</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Title</label>
                            <input class="form-control" id="meta_title" placeholder="Meta Title" name="meta_title" type="text">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Meta Keyword</label>
                            <textarea class="form-control" id="meta_keyword" placeholder="Meta Keyword" rows="3" name="meta_keyword" cols="50"></textarea>
                        </div>
                    </div>         
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Meta Title</label>
                            <input class="form-control" id="meta_title" placeholder="Meta Title" name="meta_title" type="text">
                        </div>
                        
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" placeholder="Meta Description" rows="3" name="meta_description" cols="50"></textarea>
                        </div>                               
                    </div>         
                </div>
                <div class="col-12 mb-2">
                            <label for="title" class="control-label">Description</label>
                            <textarea class="form-control" id="meta_description" placeholder="Meta Description" rows="9" name="meta_description" cols="50"></textarea>
                        </div>
                <div class="col-12 mt-3">
                    <button type="button" class="btn btn-info w-100" data-dismiss="modal">Done</button>
                </div>
            </div>
      </div>
      
    </div>
  </div>
</div>
@endsection