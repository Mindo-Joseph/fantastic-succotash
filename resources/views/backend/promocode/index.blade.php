@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Promocode'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Promocode</h4>
            </div>
        </div>
    </div>

    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                                @if (\Session::has('Data_Updated'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('Data_Updated') !!}</span>
                                </div>
                                @endif
                                @if (\Session::has('error_delete'))
                                <div class="alert alert-danger">
                                    <span>{!! \Session::get('error_delete') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-blue waves-effect waves-light text-sm-right openPromoModal" userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Add </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <form name="saveOrder" id="saveOrder"> @csrf </form>
                        <table class="table table-centered table-nowrap table-striped" id="promo-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Promo Code</th>
                                    <th>Promo Types</th>
                                    <th>Total Amount</th>
                                    <th>Expiry Date</th>
                                    <th>Minimum amount to spend</th>
                                    <th>Maximum amount to spend</th>
                                    <th>Limit Per User</th>
                                    <th>Total limit</th>
                                    <!-- <th>End On</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($promocodes as $promo)
                                <tr data-row-id="">
                                    <td class="draggableTd"><span class="dragula-handle"></span></td>
                                    <td>{{$promo->name}}</td>
                                    <td>{{$promo->type->title}}</td>
                                    <td>{{$promo->amount}}</td>
                                    <td>{{$promo->expiry_date}}</td>
                                    <td>{{$promo->minimum_spend}}</td>
                                    <td>{{$promo->maximum_spend}}</td>
                                    <td>{{$promo->limit_per_user}}</td>
                                    <td>{{$promo->limit_total}}</td>
                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon openPromoModal" userId="{{$promo->id}}" href="#">
                                                    <h3> <i class="mdi mdi-square-edit-outline"></i></h3>
                                                </a>
                                            </div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{route('promocode.destroy', $promo->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <button type="submit" onclick="return confirm('Are you sure? You want to delete the Promocode.')" class="btn btn-primary-outline action-icon">
                                                            <h3><i class="mdi mdi-delete"></i></h3>
                                                        </button>

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
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{-- $promocode->links() --}}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>

@include('backend.promocode.modals')
@endsection

@section('script')

<script type="text/javascript">
    // function assignSortAttach() {
    //     $("table").sortable({
    //         axis: "y",
    //         cursor: "grabbing",
    //         handle: ".handle",
    //         cancel: "thead",
    //         opacity: 0.6,
    //         placeholder: "two-place",
    //         helper: function(e, item) {
    //             if (!item.hasClass("selected")) {
    //                 item.addClass("selected");
    //             }
    //             console.log("Selected: ", $(".selected"));
    //             var elements = $(".selected").not(".ui-sortable-placeholder").clone();
    //             console.log("Making helper from: ", elements);
    //             // Hide selected Elements
    //             $(".selected").not(".ui-sortable-placeholder").addClass("hidden");
    //             var helper = $("<table />");
    //             helper.append(elements);
    //             console.log("Helper: ", helper);
    //             return helper;
    //         },
    //         start: function(e, ui) {
    //             var elements = $(".selected.hidden").not('.ui-sortable-placeholder');
    //             console.log("Start: ", elements);
    //             ui.item.data("items", elements);
    //         },
    //         update: function(e, ui) {
    //             console.log("Receiving: ", ui.item.data("items"));
    //             ui.item.before(ui.item.data("items")[1], ui.item.data("items")[0]);
    //         },
    //         stop: function(e, ui) {
    //             $('.selected.hidden').not('.ui-sortable-placeholder').removeClass('hidden');
    //             $('.selected').removeClass('selected');
    //         }
    //     });
    // }
</script>

@include('backend.promocode.pagescript')

@endsection