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
            <h1 class="page-title">{{__('Shares Allocations')}} {{ isset($search_title) ? $search_title : ' ' }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Shares Allocations')}}</li>
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
                <div class="col-lg-4 col-xs-12">
                    <div class="card card-block p-30 bg-success">
                        <div class="counter counter-md text-xs-left">
                            <div class="counter-label text-uppercase m-b-5"><b>{{__('Total Shares Vested')}}</b>
                            </div>
                            <div class="counter-number-group m-b-10">
                                <span class="counter-number" style="color: white">{{ number_format($vested) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-12">
                    <div class="card card-block p-30 bg-info">
                        <div class="counter counter-md text-xs-left">
                            <div class="counter-label text-uppercase m-b-5"><b>{{__('Total Shares Pending')}}</b>
                            </div>
                            <div class="counter-number-group m-b-10">
                                <span class="counter-number" style="color: white">{{ number_format($pending) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-12">
                    <div class="card card-block p-30 bg-primary">
                        <div class="counter counter-md text-xs-left">
                            <div class="counter-label text-uppercase m-b-5"><b>{{__('Total Shares')}}</b>
                            </div>
                            <div class="counter-number-group m-b-10">
                                <span class="counter-number" style="color: white">{{ number_format($total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col col-lg-12">


                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Shares</h3>
                            <div class="panel-actions">
                                <button class="btn btn-success" data-target="#bulkImportModal"
                                        data-toggle="modal" type="button">Bulk Import
                                </button>
                                <button class="btn btn-primary" data-target="#exampleNiftyFadeScale"
                                        data-toggle="modal" type="button">Allocate Shares
                                </button>&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Number of Shares</th>
                                    <th>Amount Vested</th>
                                    <th>Amount to Vest</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Staff</th>
                                    <th>Number of Shares</th>
                                    <th>Amount Vested</th>
                                    <th>Amount to Vest</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td><a style="text-decoration: none;" href="{{ url('/user-shares',$user->id) }}">{{$user->name}}</a></td>
                                        <td>{{number_format($user->allshares)}}</td>
                                        <td>{{number_format($user->vesteds)}}</td>
                                        <td>{{number_format($user->pendings)}}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu"
                                                     x-placement="bottom-start"
                                                     style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item" style="cursor:pointer;"
                                                       href="{{ url('/user-shares',$user->id) }}">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Details</a>
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
        <!-- Modal -->
        <div class="modal fade modal-fade-in-scale-up" id="exampleNiftyFadeScale" aria-hidden="true"
             aria-labelledby="exampleModalTitle" role="dialog" >
            <div class="modal-dialog modal-simple">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Allocate New Share</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/shares-allocations') }}" method="Post">
                            {{ csrf_field() }}
                            <div class="example"  style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Select Staff</h4>
                                <div class="form-group">
                                    <select style="width: 100%;" class="form-control" data-plugin="select2" data-select2-id="1" tabindex="-1" aria-hidden="true"
                                            name="user_id" id="user_id" required>
                                        @foreach($users2 as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="example"  style="margin-bottom: 0px; margin-top: 0px">
                                        <h4 class="example-title">Number of Shares</h4>
                                        <input type="number" class="form-control focus" id="inputFocus" name="no_of_shares" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="example"  style="margin-bottom: 0px; margin-top: 0px">
                                        <h4 class="example-title">Years Vested</h4>
                                        <input type="number" class="form-control focus" id="inputFocus" name="years_vested" min="0" required>
                                    </div>
                                </div>
                            </div>


                            <div class="example"  style="margin-bottom: 10px; margin-top: 0px">
                                <h4 class="example-title">Share Start Date</h4>
                                <div class="input-group">
                                <span class="input-group-addon">
                                  <i class="icon wb-calendar" aria-hidden="true"></i>
                                </span>
                                    <input type="text" class="form-control" data-plugin="datepicker" name="date" required
                                           autocomplete="off">
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade modal-fade-in-scale-up" id="bulkImportModal" aria-hidden="true"
             aria-labelledby="exampleModalTitle" role="dialog" >
            <div class="modal-dialog modal-simple">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Upload Shares Allocation</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form class="form-horizontal" id="uploadExcelForm" method="POST"
                                      action="{{url('shares/excel-template')}}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="form-row">
                                        <h4><a href="{{ url('shares/excel-template') }}">Download Template</a></h4>
                                        <div class="form-group col-md-12">
                                            <input type="file" name="template" class="form-control-file">
                                            <input type="hidden" name="import_shift">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <button type="submit" id="submit_button" class="btn btn-info pull-left ">Upload</button>
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <div id="loader" style="display: none;" class="loader vertical-align-middle loader-ellipsis"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        function viewMore(id) {
            $("#detailLoader").load('{{ url('/shares-getdetails') }}/' + id);
            $('#viewMoreModal').modal();
        }
    </script>

    <script>
        $("#startdate2").datepicker( {
            format: " yyyy", // Notice the Extra space at the beginning
            viewMode: "years",
            minViewMode: "years"
        });

        $(function (){

            $(document).on('submit', '#uploadExcelForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $("#submit_button").hide();
                $("#loader").show();
                $.ajax({
                    url: '{{url('shares/excel-template')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        toastr.success("Changes saved successfully", 'Success');
                        $("#submit_button").show();
                        $("#loader").hide();
                        $('#bulkImportModal').modal('toggle');
                    },
                    error: function (data, textStatus, jqXHR) {
                        $("#loader").hide();
                        $("#submit_button").show();
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr.error(valchild[0]);
                            });
                        });
                    }
                });

            });

            setInterval(function(){
                $('#time').html(new Date(new Date().getTime()).toLocaleTimeString());
            },1000);
        });
    </script>
@endsection