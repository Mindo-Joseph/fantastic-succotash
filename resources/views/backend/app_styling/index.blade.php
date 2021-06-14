@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'App Styling'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">App-Styling</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Font Styles</h4>
                        <p class="sub-header">Examples of Spectrum Fonts.</p>
                        <div class="mb-3">
                            <label class="form-label">Selecting multiple dates</label>
                            <select class="form-control">
                                <option selected="">Fonts</option>
                                <option value="1">Open Sans</option>
                                <option value="2">Roboto</option>
                                <option value="3">Lato</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Color Picker</h4>
                        <p class="sub-header">Examples of Spectrum Colorpicker.</p>
                        <div class="mb-3">
                            <label class="form-label">Simple input field</label>
                            <span class="sp-original-input-container" style="margin: 0px; display: flex;"><div class="sp-colorize-container sp-add-on" style="width: 37.375px; border-radius: 3.2px; border: 1px solid rgb(66, 78, 90);"><div class="sp-colorize" style="background-color: rgb(74, 129, 212); color: white;"></div> </div><input type="text" class="form-control spectrum with-add-on" id="colorpicker-default" value="#4a81d4"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Tab Bar Style</h4>
                        <p class="sub-header">Examples of Spectrum Colorpicker.</p>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card mb-0">
                                    <img class="card-img-top img-fluid" src="{{asset('assets/images/small/img-1.jpg')}}" alt="Card image cap">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 custom-control custom-radio">
                                                <input type="radio" value="" id="webTemplate" name="web_template_id" class="custom-control-input" }}>
                                                <label class="custom-control-label" for="webTemplate">Select Image</label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card mb-0">
                                    <img class="card-img-top img-fluid" src="{{asset('assets/images/small/img-1.jpg')}}" alt="Card image cap">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 custom-control custom-radio">
                                                <input type="radio" value="" id="webTemplate" name="web_template_id" class="custom-control-input" }}>
                                                <label class="custom-control-label" for="webTemplate">Select Image</label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card mb-0">
                                    <img class="card-img-top img-fluid" src="{{asset('assets/images/small/img-1.jpg')}}" alt="Card image cap">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 custom-control custom-radio">
                                                <input type="radio" value="" id="webTemplate" name="web_template_id" class="custom-control-input" }}>
                                                <label class="custom-control-label" for="webTemplate">Select Image</label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Home Page Style</h4>
                        <p class="sub-header">Examples of Spectrum Colorpicker.</p>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card mb-0">
                                    <img class="card-img-top img-fluid" src="{{asset('assets/images/small/img-1.jpg')}}" alt="Card image cap">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 custom-control custom-radio">
                                                <input type="radio" value="" id="webTemplate" name="web_template_id" class="custom-control-input" }}>
                                                <label class="custom-control-label" for="webTemplate">Select Image</label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card mb-0">
                                    <img class="card-img-top img-fluid" src="{{asset('assets/images/small/img-1.jpg')}}" alt="Card image cap">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 custom-control custom-radio">
                                                <input type="radio" value="" id="webTemplate" name="web_template_id" class="custom-control-input" }}>
                                                <label class="custom-control-label" for="webTemplate">Select Image</label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card mb-0">
                                    <img class="card-img-top img-fluid" src="{{asset('assets/images/small/img-1.jpg')}}" alt="Card image cap">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 custom-control custom-radio">
                                                <input type="radio" value="" id="webTemplate" name="web_template_id" class="custom-control-input" }}>
                                                <label class="custom-control-label" for="webTemplate">Select Image</label>
                                            </div>
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

