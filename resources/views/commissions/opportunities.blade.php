@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Opportunities')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Opportunities')}}</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content container-fluid">
            <div class="row">
                <div class="col col-lg-12">


                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Opportunities</h3>
                            <div class="panel-actions">
                                <button class="btn btn-success" data-target="#bulkImportModal"
                                        data-toggle="modal" type="button">Bulk Import
                                </button>
                                <button class="btn btn-primary" data-target="#exampleNiftyFadeScale" data-toggle="modal" type="button">Add Opportunity
                                </button>


                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                    <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Project Name</th>
                                        <th>Date</th>
                                        <th>Project Status</th>
                                        <th>Payment Status</th>
                                        <th>Project Amount</th>
                                        <th>Staff Commissions</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Project Name</th>
                                        <th>Date</th>
                                        <th>Project Status</th>
                                        <th>Payment Status</th>
                                        <th>Project Amount</th>
                                        <th>Staff Commissions</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($opportunities as $opportunity)
                                        <tr>
                                            <td>{{$opportunity->client_id}}</td>
                                            <td>{{$opportunity->project_name}}</td>
                                            <td>{{$opportunity->date}}</td>
                                            <td>{{$opportunity->project_status}}</td>
                                            <td>{{$opportunity->payment_status}}</td>
                                            <td>{{number_format($opportunity->project_amount,2)}}</td>
                                            <td>{{$opportunity->commissions_count}}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu"
                                                         x-placement="bottom-start"
                                                         style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a class="dropdown-item" href="#" onclick="return editOpportunity({{$opportunity}})" role="menuitem">Edit Opportunity</a>
                                                        <a class="dropdown-item" href="{{url('opportunity/commissions',$opportunity->id)}}" role="menuitem">View Commissions</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @include('commissions.modal.add_opportunity')
        @include('commissions.modal.edit_opportunity')
    </div>
@endsection

@section('scripts')
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/vendor/jt-timepicker/jquery.timepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/datepair.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/jquery.datepair.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script>
        function editOpportunity(opportunity){
            $("#editOpportunityModal").modal();
            $("#id").val(opportunity.id);
            $("#client_id").val(opportunity.client_id);
            $("#project_name").val(opportunity.project_name);
            $("#payment_status").val(opportunity.payment_status);
            $("#project_status").val(opportunity.project_status);
            $("#date").val(opportunity.date);
            $("#project_amount").val(opportunity.project_amount);
        }
        $(function () {
            $('#addOpportunityForm').on('submit', function (event) {
                $("#addOpportunityFormSubmit").hide();
                $("#loader").show();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{ url('/opportunities') }}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        if (data.status==='success'){
                            toastr.success(data.details);
                            $('#addOpportunityForm').trigger("reset");
                            $("#addOpportunityFormSubmit").show();
                            $("#loader").hide();
                            location.reload();
                        }
                        else {
                            toastr.error(data.details);
                        }
                    },
                    error: function (data, textStatus, jqXHR) {

                    }
                });
                return event.preventDefault();
            });

            $('#editOpportunityForm').on('submit', function (event) {
                $("#editOpportunityFormSubmit").hide();
                $("#loader2").show();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{ url('/opportunities') }}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        if (data.status==='success'){
                            toastr.success(data.details);
                            $('#editOpportunityForm').trigger("reset");
                            $("#editOpportunityFormSubmit").show();
                            $("#loader2").hide();
                            $('#editOpportunityModal').modal('toggle');
                            location.reload();
                        }
                        else {
                            toastr.error(data.details);
                        }
                    },
                    error: function (data, textStatus, jqXHR) {

                    }
                });
                return event.preventDefault();
            });
        });
    </script>
    <script>
        $(function () {
            $(document).on('submit', '#uploadOpportunityForm', function (event) {
                $("#uploadOpportunityFormSubmit").hide();
                $("#Opportunityloader").show();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{ url('/opportunity-excel') }}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $("#uploadOpportunityFormSubmit").show();
                        $("#Opportunityloader").hide();
                        if (data.status==='success'){
                            toastr.success(data.details);
                            $('#uploadOpportunityForm').trigger("reset");
                        }
                        else {
                            console.log(data)
                            toastr.error(data.details);
                        }
                    },
                    error: function (data, textStatus, jqXHR) {
                        $("#uploadOpportunityFormSubmit").show();
                        $("#Opportunityloader").hide();
                    }
                });
                return event.preventDefault();
            });


            $(document).on('submit', '#uploadCommissionForm', function (event) {
                $("#uploadCommissionFormSubmit").hide();
                $("#Commissionloader").show();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{ url('/commissions-excel') }}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        console.log(data)
                        $("#uploadCommissionFormSubmit").show();
                        $("#Commissionloader").hide();
                        if (data.status==='success'){
                            toastr.success(data.details);
                            $('#uploadCommissionForm').trigger("reset");
                            location.reload();
                        }
                        else {
                            console.log(data)
                            toastr.error(data.details);
                        }
                    },
                    error: function (data, textStatus, jqXHR) {
                        $("#uploadCommissionFormSubmit").show();
                        $("#Commissionloader").hide();
                    }
                });
                return event.preventDefault();
            });
        });
    </script>
@endsection


