<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Promocode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body" id="addCardBox1">
                <section>
                    <form action="{{route('promocode.store')}}" method="POST">
                        @csrf
                        @if(Session::has('Data_Inserted'))
                        <div class="alert alert-success" role="alert">
                            {{Session::get('Data_Inserted')}};
                        </div>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="RoleName">PromoCode</label>
                                <input type="text" class="form-control" name="name" id="inputRoleName" placeholder="Enter promocode">
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <p class="text-muted mt-3 mb-2">Types</p>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="inlineRadio3" value="option3" name="radioInline3" checked>
                                    <label for="inlineRadio3"> Inline One </label>
                                </div>
                                <div class="radio form-check-inline">
                                    <input type="radio" id="inlineRadio4" value="option4" name="radioInline4">
                                    <label for="inlineRadio4"> Inline Two </label>
                                </div>
                                <div class="radio form-check-inline">
                                    <input type="radio" id="inlineRadio5" value="option5" name="radioInline5">
                                    <label for="inlineRadio5"> Inline Two </label>
                                </div>
                                <!-- <p class="font-weight-bold text-muted">Types</p>
                                <select class="form-control" name="types" data-toggle="select2">
                                    <option>Select</option>
                                    <option value="0">Fixed</option>
                                    <option value="1">Percentage</option>
                                    <option value="2">Fixed per product</option>
                                </select> -->
                                @error('types')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label for="example-number">Amount</label>
                                <input class="form-control" id="example-number" type="number" name="amount" placeholder="Enter total amount">
                                @error('amount')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Expiry Date</label>
                                <input type="text" id="humanfd-datepicker" name="expiry_date" class="form-control" placeholder="October 9, 2018">
                                @error('expiry_date')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label for="">Allow Free Delivery</label> <br>
                                <input type="checkbox" checked data-plugin="switchery" name="free_delivery" data-color="#039cfd" />
                                @error('free_delivery')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label for="">First Order Only</label> <br>
                                <input type="checkbox" checked data-plugin="switchery" name="first_order" data-color="#039cfd" />
                                @error('first_order')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>


                            <div class="form-group mb-6 col-md-6">
                                <label for="example-number">Minimum Amount</label>
                                <input class="form-control" id="example-number" type="number" name="minimum_amount" placeholder="Enter Minimum Amount">
                                @error('minimum_amount')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>


                            <div class="form-group mb-6 col-md-6">
                                <label for="example-number">Maximum Amount</label>
                                <input class="form-control" id="example-number1" type="number" name="maximum_amount" placeholder="Enter Maximum Amount">
                                @error('maximum_amount')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Limit Per Users</label>
                                <input class="form-control" id="example-number" type="number" name="limit_per_user" placeholder="Enter limit per users">
                                @error('limit_per_user')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group  col-md-6">
                                <label>Total Limit</label>
                                <input class="form-control" id="example-number" type="number" name="total_limit" placeholder="Enter total limits">
                                @error('total_limit')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-6" name="paid_by[]">
                                <p class="text-muted mb-2">Paid By</p>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="inlineRadio1" value="admin" name="radioInline" checked>
                                    <label for="inlineRadio1"> Admin</label>
                                </div>
                                <div class="radio form-check-inline">
                                    <input type="radio" id="inlineRadio2" value="vendor" name="radioInline">
                                    <label for="inlineRadio2"> Vendor</label>
                                </div>
                                @error('Paid By')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <p class="font-weight-bold text-muted">Restriction Types</p>
                                <select class="form-control" name="restriction_types" data-toggle="select2">
                                    <option>Select</option>
                                    <option value="0">Product</option>
                                    <option value="1">Vendor</option>
                                    <option value="2">Category</option>
                                </select>
                                @error('restriction_types')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </section>

            </div>
        </div>
    </div>
</div>
<!--Modal for create ends here  -->


<div id="add-promo-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Promocode</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="addPromoForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="addCardBox">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-blue waves-effect waves-light submitEditForm">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div id="edit--promo-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Promocode</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="editPromoForm" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editCardBox">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-blue waves-effect waves-light submitEditForm">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>