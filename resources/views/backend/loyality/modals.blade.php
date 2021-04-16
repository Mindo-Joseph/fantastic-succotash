<div class="modal fade bd-example-modal-lg addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h4 class="modal-title">Add Loyalty Card</h4><br>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_loyality_form">
                @csrf
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="form-group" id="nameInput">
                            {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="minimum_pointsInput">
                            {!! Form::label('title', 'Minimum Points to reach this level *',['class' => 'control-label']) !!}
                            {!! Form::text('minimum_points', null, ['class' => 'form-control']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="descriptionInput">
                            {!! Form::label('title', 'Description *',['class' => 'control-label']) !!}
                            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>


                <br>
                <h5>Earnings</h5>
                <br>

                <div class="form-group" id="per_order_pointsInput">
                    {!! Form::label('title', 'Earnings Per Order*',['class' => 'control-label']) !!}
                    {!! Form::text('per_order_points', null, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
                <!-- <div class="form-group" id="per_purchase_minimum_amountInput">
                    {!! Form::label('title', 'Minimum Order Amount to redeem Purchase Points*',['class' => 'control-label']) !!}
                    {!! Form::text('per_purchase_minimum_amount', null, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div> -->
                <br>

                <label for="purchase">Order Amount for 1 Loyalty Point (as per primary currency)</label>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">1 Loyalty Point =</span>
                                </div>
                                <input type="text" onkeypress="return isNumberKey(event);" class="form-control" name="amount_per_loyalty_point" id="amount_per_loyalty_point" placeholder="Value" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light submitAddForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-loyalty-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Loyalty Card</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="update_loyality_form" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editLoyaltyBox">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-blue waves-effect waves-light submitEditForm">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade redeemPoint" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Redeem Point Value</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="setRedeem">
            @csrf
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text primaryKey" id="basic-addon1"></span>
                                    </div>
                                    <input type="text" onkeypress="return isNumberKey(event);" class="form-control" name="redeem_points_per_primary_currency" id="redeem_points_per_primary_currency" placeholder="Value" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary setredeempoints">Save changes</button>
                </div>x
            </form>
        </div>
    </div>
</div>