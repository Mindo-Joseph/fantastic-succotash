<div class="modal fade" id="standard-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="min-width: 530px;">
        <div class="modal-content">
            <div class="modal-header py-3 px-4 border-bottom-0 d-block">
                <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="modal-title">Book Slot</h5>
            </div>
            <div class="modal-body p-4">
                <form class="needs-validation" name="slot-form" id="slot-event" action="{{ route('vendor.saveSlot', $vendor->id) }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Start Time(24 hours format)</label>
                                <input class="form-control" placeholder="Start Time" type="text" name="start_time" id="start_time" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">End Time(24 hours format)</label>
                                <input class="form-control" placeholder="End Time" type="text" name="end_time" id="end_time" required />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2"></div>
                        <div class="col-md-10 slotForDiv">
                            {!! Form::label('title', 'Slot For',['class' => 'control-label']) !!} 
                            <div class="form-group">
                                <ul class="list-inline">
                                    <li class="d-inline-block mr-2">
                                        <input type="radio" class="custom-control-input check slotTypeRadio" id="slotDay" name="stot_type" value="day" checked="">
                                        <label class="custom-control-label" for="slotDay">Days&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    </li>
                                    <li class="d-inline-block">
                                        <input type="radio" class="custom-control-input check slotTypeRadio" id="slotDate" name="stot_type" value="date">
                                        <label class="custom-control-label" for="slotDate">Date</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="">
                                {!! Form::label('title', 'Slot Type',['class' => 'control-label']) !!} 
                            </div>
                            @if($vendor->dine_in == 1)
                                <div class="checkbox checkbox-success form-check-inline"  @if($client_preferences->dinein_check == 0) style="display: none;" @endif>
                                    <input name="slot_type[]" type="checkbox" id="dine_in" checked value="dine_in">
                                    <label for="dine_in"> Dine in </label>
                                </div>
                            @endif
                            @if($vendor->takeaway == 1)
                                <div class="checkbox checkbox-success form-check-inline"  @if($client_preferences->takeaway_check == 0) style="display: none;" @endif >
                                    <input name="slot_type[]" type="checkbox" id="takeaway" checked value="takeaway">
                                    <label for="takeaway"> Takeaway </label>
                                </div>
                            @endif
                            @if($vendor->delivery == 1)
                                <div class="checkbox checkbox-success form-check-inline"  @if($client_preferences->delivery_check == 0) style="display: none;" @endif>
                                    <input name="slot_type[]" type="checkbox" id="delivery" checked value="delivery">
                                    <label for="delivery"> Delivery </label>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2 weekDays">
                        <div class="col-md-12">
                            <div class="">
                            {!! Form::label('title', 'Select days of week',['class' => 'control-label']) !!} 
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="day_1" value="1">
                                <label for="day_1"> Sunday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="day_2" value="2">
                                <label for="day_2"> Monday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="day_3" value="3">
                                <label for="day_3"> Tuesday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="day_4" value="4">
                                <label for="day_4"> Wednesday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="day_5" value="5">
                                <label for="day_5"> Thursday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="day_6" value="6">
                                <label for="day_6"> Friday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="day_7" value="7">
                                <label for="day_7"> Saturday </label>
                            </div>
                        </div>
                    </div>

                    <div class="row forDate" style="display: none;">
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label class="control-label">Slot Date</label>
                                <input class="form-control date-datepicker" placeholder="Select Date" type="text" name="slot_date" id="slot_date" required />
                            </div>
                        </div>

                        <!--<div class="col-md-3" >
                            <div class="radio radio-success mb-2 form-check-inline">
                                <input type="radio" name="slot_date_for" id="radio1" value="active_date" checked>
                                <label for="radio1">Slot Date</label>
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div class="radio radio-success mb-2 form-check-inline">
                                <input type="radio" name="slot_date_for" id="radio2" value="block_date">
                                <label for="radio2">Block Date</label>
                            </div>
                        </div> -->
                    </div>
                    <div class="row mt-2">
                        
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-light mr-1" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-info" id="btn-save-slot">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-slot-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="min-width: 530px;">
        <div class="modal-content">
            <div class="modal-header py-3 px-4 border-bottom-0 d-block">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="modal-title">Edit Slot</h5>
                <form method="post" action="{{ route('vendor.deleteSlot', $vendor->id) }}" id="deleteSlotForm">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="slot_day_id" id="deleteSlotDayid" value="" >
                        <input type="hidden" name="slot_id" id="deleteSlotId" value="" >
                        <input type="hidden" name="slot_type" id="deleteSlotType" value="" >
                       <button type="button" class="btn btn-primary-outline action-icon" style="display: none;"></button> 
                    </div>
                </form>
            </div>
            <div class="modal-body p-4">
                <form class="needs-validation" name="slot-form" id="update-event" action="{{ route('vendor.updateSlot', $vendor->id) }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Start Time(24 hours format)</label>
                                <input class="form-control" placeholder="Start Time" type="text" name="start_time" id="edit_start_time" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">End Time(24 hours format)</label>
                                <input class="form-control" placeholder="End Time" type="text" name="end_time" id="edit_end_time" required />
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2"></div>
                        <div class="col-md-10 slotForDiv">
                            {!! Form::label('title', 'Slot For',['class' => 'control-label']) !!} 
                            <div class="form-group">
                                <ul class="list-inline">
                                    <li class="d-inline-block mr-2">
                                        <input type="radio" class="custom-control-input check slotTypeEdit" id="edit_slotDay" name="slot_type_edit" value="day" checked="">
                                        <label class="custom-control-label" id="edit_slotlabel" for="edit_slotDay">Days</label>
                                    </li>
                                    <li class="d-inline-block"> &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="custom-control-input check slotTypeEdit" id="edit_slotDate" name="slot_type_edit" value="date">
                                        <label class="custom-control-label" for="edit_slotDate">Date</label>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="">
                            {!! Form::label('title', 'Slot Type',['class' => 'control-label']) !!} 
                            </div>
                            @if($vendor->dine_in == 1)
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="slot_type[]" type="checkbox" id="edit_dine_in" checked value="dine_in">
                                <label for="dine_in"> Dine in </label>
                            </div>
                            @endif
                            @if($vendor->takeaway == 1)
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="slot_type[]" type="checkbox" id="edit_takeaway" checked value="takeaway">
                                <label for="takeaway"> Takeaway </label>
                            </div>
                            @endif
                            @if($vendor->delivery == 1)
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="slot_type[]" type="checkbox" id="edit_delivery" checked value="delivery">
                                <label for="delivery"> Delivery </label>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!--<div class="row mb-2 weekDaysEdit">
                        <div class="col-md-12"> 
                            <div class="">
                            {!! Form::label('title', 'Select days of week',['class' => 'control-label']) !!} 
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="edit_day_1" value="1">
                                <label for="day_1"> Sunday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="edit_day_2" value="2">
                                <label for="day_2"> Monday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="edit_day_3" value="3">
                                <label for="day_3"> Tuesday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="edit_day_4" value="4">
                                <label for="day_4"> Wednesday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="edit_day_5" value="5">
                                <label for="day_5"> Thursday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="edit_day_6" value="6">
                                <label for="day_6"> Friday </label>
                            </div>
                            <div class="checkbox checkbox-success form-check-inline">
                                <input name="week_day[]" type="checkbox" id="edit_day_7" value="7">
                                <label for="day_7"> Saturday </label>
                            </div>
                        </div>
                    </div> -->

                    <div class="row forDateEdit" style="display: none;">
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label class="control-label">Slot Date</label>
                                <input class="form-control date-datepicker" placeholder="Select Date" type="text" name="slot_date" id="edit_slot_date" required />
                            </div>
                            <input  name="edit_type" type="hidden" id="edit_type" value="">
                            <input  name="edit_day" type="hidden" id="edit_day" value="">
                            <input name="edit_type_id" type="hidden" id="edit_type_id" value="">
                        </div>
                    </div>


                    <div class="row mt-2">
                        
                        <div class="col-12 text-right">
                            <a type="button" class="btn btn-danger mr-1" id="deleteSlotBtn">Delete Slot</a> 
                            <button type="button" class="btn btn-light mr-1" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-info" id="btn-update-slot">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">

            <div class="modal-header border-bottom">
                <h4 class="modal-title">Select Location</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                
                <div class="row">
                    <form id="task_form" action="#" method="POST" style="width: 100%">
                        <div class="col-md-12">
                            <div id="googleMap" style="height: 500px; min-width: 500px; width:100%"></div>
                            <input type="hidden" name="lat_input" id="lat_map" value="0" />
                            <input type="hidden" name="lng_input" id="lng_map" value="0" />
                            <input type="hidden" name="for" id="map_for" value="" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info waves-effect waves-light selectMapLocation">Ok</button>
                <!--<button type="Cancel" class="btn btn-info waves-effect waves-light cancelMapLocation">cancel</button>-->
            </div>
        </div>
    </div>
</div>

<div id="edit-area-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add Service Area</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="edit-area-form" action="{{ route('vendor.serviceArea', $vendor->id) }}" method="POST">
                @csrf
                <div class="modal-body" id="editAreaBox">
                       
                </div>

                <div class="modal-footer">
                    <div class="row mt-1">
                        <!-- <div class="col-md-6">
                            <button type="button"
                                class="btn btn-block btn-outline-blue waves-effect waves-light">Cancel</button>
                        </div> -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-block btn-blue waves-effect waves-light">Save</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>