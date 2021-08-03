<div id="add-tax-category" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add Tax Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_tax_category" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group" id="titleInput">
                                        {!! Form::label('title', 'Title',['class' => 'control-label']) !!}
                                        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Tax Category Title']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="codeInput">
                                        {!! Form::label('title', 'Code',['class' => 'control-label']) !!}
                                        {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Tax Category Code']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Description',['class' => 'control-label']) !!}
                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'description', 'rows' => '5']) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitAddTaxCate">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-tax-category" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Edit Tax Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="update_tax_category" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="taxCategoryBox">
                    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitEditTaxCate">Submit</button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<div id="add-tax-rate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add Tax Rate</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_tax_rate" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group" id="identifierInput">
                                        {!! Form::label('title', 'Identifier',['class' => 'control-label']) !!}
                                        {!! Form::text('identifier', null, ['class' => 'form-control', 'placeholder' => 'Tax Identifier']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="categoryInput">
                                        {!! Form::label('title', 'Tax Category',['class' => 'control-label']) !!}
                                        <select class="form-control select2-multiple" id="category" name="category[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                            @foreach($taxCates as $cat)
                                                <option value="{{$cat->id}}">{{ $cat->title }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="countryInput">
                                        {!! Form::label('title', 'Country',['class' => 'control-label']) !!}
                                        {!! Form::text('country', null, ['class' => 'form-control', 'placeholder' => 'Country']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="stateInput">
                                        {!! Form::label('title', 'State',['class' => 'control-label']) !!}
                                        {!! Form::text('state', null, ['class' => 'form-control', 'placeholder' => 'State']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="postal_typeInput">
                                        {!! Form::label('title', 'Applied On',['class' => 'control-label']) !!}
                                        <select class="form-control selectize-select postalSelect" name="postal_type" for="add">
                                            <option value="0">No Postal Code</option>
                                            <option value="1">Single Postal Code</option>
                                            <option value="2">Postal Code Length</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="tax_rateInput">
                                        {!! Form::label('title', 'Tax Rate',['class' => 'control-label']) !!}
                                        {!! Form::text('tax_rate', null, ['class' => 'form-control', 'placeholder' => 'Tax Rate', 'onkeypress' => 'return isNumberKey(event)']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2" id="singlePostal-add" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group" id="postal_codeInput">
                                        {!! Form::label('title', 'Postal Code',['class' => 'control-label']) !!}
                                        {!! Form::text('postal_code', null, ['class' => 'form-control', 'placeholder' => 'Tax Identifier', 'onkeypress' => 'return isNumberKey(event)']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="multiPostal-add" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group" id="postal_code_startInput">
                                        {!! Form::label('title', 'Postal Code Start',['class' => 'control-label']) !!}
                                        {!! Form::text('postal_code_start', null, ['class' => 'form-control', 'placeholder' => 'Tax Identifier', 'onkeypress' => 'return isNumberKey(event)']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="postal_code_endInput">
                                        {!! Form::label('title', 'Postal Code End',['class' => 'control-label']) !!}
                                        {!! Form::text('postal_code_end', null, ['class' => 'form-control', 'placeholder' => 'Tax Identifier', 'onkeypress' => 'return isNumberKey(event)']) !!}
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
                    <button type="button" class="btn btn-info waves-effect waves-light submitAddTaxRate">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-tax-rate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Edit Tax Rate</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="update_tax_rate" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="taxRateBox">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitEditTaxRate">Submit</button>
                </div>
                
            </form>
        </div>
    </div>
</div>