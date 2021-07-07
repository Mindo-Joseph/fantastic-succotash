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
                <h4 class="page-title">Page</h4>
            </div>
        </div>
        <!-- <div class="col-4 text-right" style="margin: auto;">
            <button type="button" class="btn btn-info waves-effect waves-light text-sm-right saveProduct"> Submit</button>
        </div> -->
    </div>
    <!-- <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Meta Title</label>
                            <input class="form-control" id="meta_title" placeholder="Meta Title" name="meta_title" type="text">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Meta Keyword</label>
                            <textarea class="form-control" id="meta_keyword" placeholder="Meta Keyword" rows="3" name="meta_keyword" cols="50"></textarea>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" placeholder="Meta Description" rows="3" name="meta_description" cols="50"></textarea>
                        </div>
                    </div>
                </div>            
            </div>            
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Product Name</label>
                            <input class="form-control" id="product_name" placeholder="Apple iMac" name="product_name" type="text" value="Cheese Chilly">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="title" class="control-label">Product Description</label>
                            <textarea class="form-control" id="meta_description" placeholder="Meta Description" rows="9" name="meta_description" cols="50"></textarea>
                        </div>
                    </div>
                </div>            
            </div>            
        </div>
    </div> -->
</div>
@endsection