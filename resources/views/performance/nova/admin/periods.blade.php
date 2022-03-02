@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
    <style>
        td.details-control {
            background: url('http://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('http://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
        }
    </style>
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">KPI Setup</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">KPI Setup</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-350 hidden-sm-down"></div>
            </div>
        </div>

        <div class="page-content container-fluid">
            <div class="row">
                <div class="col-lg-3 masonry-item">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">KPI Measurement Periods &nbsp;<button onclick="createNewMP()" type="button" class="btn btn-icon btn-success btn-outline btn-xs">
                                    <i class="fa fa-plus" aria-hidden="true"></i></button></h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group list-group-full h-500" data-plugin="scrollable">
                                <div data-role="container">
                                    <div data-role="content" id="table1">
                                        <input type="text" id="search1" name="example-input3-group2" class="form-control" placeholder="Search">
                                        <br>
                                        @foreach($kpi_periods as $period)
                                            <a href="javascript:void(0)" onclick="loadUsers({{$period->id}})" class="list-group-item" style="padding-bottom: 0px; margin-bottom: -10px;">
                                                <h5 class="list-group-item-heading mt-0 mb-0">
                                                    {{ $period->name }}
                                                </h5>
                                                <dl class="dl-horizontal row">
                                                    <dt class="col-sm-3">From:</dt>
                                                    <dd class="col-sm-9">{{ $period->from }}</dd>
                                                    <dt class="col-sm-3">To:</dt>
                                                    <dd class="col-sm-9">{{ $period->to }}</dd>
                                                    <dt class="col-sm-3">Status:</dt>
                                                    <dd class="col-sm-9">{{ $period->status }}</dd>
                                                </dl>
                                            </a>
                                            <div class="col-lg-6">
                                            <button onclick="return editMP({{collect($period)->only(['id','name','from','to','status'])}})" type="button" class="btn btn-primary btn-block btn-round btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                            </div>
                                            <div class="col-lg-6">
                                            <button onclick="return loadMPReport({{$period->id}})" type="button" class="btn btn-success btn-block btn-round btn-xs"><i class="fa fa-bar-chart" aria-hidden="true"></i> Report</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 col-xs-12 col-md-9" id="measurement_period_kpis"></div>
            </div>

            <br>


        </div>
    </div>


    <div class="modal fade in modal-3d-flip-horizontal modal-info modal-md" id="addMeasurementPeriodModal" aria-hidden="true"
         aria-labelledby="enterDetails" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="titl">New Performance Measurement Period</h4>
                </div>
                <div class="modal-body">
                    <form id="addMeasurementPeriodForm">
                        @csrf
                        <input type="hidden" name="type" id="type">
                        <input type="hidden" name="id" id="editid">
                        <div class="col col-md-12">
                            <div class="form-group">
                                <label class="form-control-label" for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="editname" autocomplete="off">
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date">From</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="icon fa fa-calendar" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" class="form-control datepair-date datepair-start" id="editfrom"
                                           data-plugin="datepicker" name="from" autocomplete="off" value="" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="date2">To</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="icon fa fa-calendar" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" class="form-control datepair-date datepair-start" id="editto"
                                           data-plugin="datepicker" name="to" autocomplete="off" value="" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col col-md-6">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                        <div class="col col-md-3" id="activate" style="display: none;">
                            <div class="form-group">
                                <button type="button" onclick="changeStatus('active')" class="btn btn-success">Activate Period</button>
                            </div>
                        </div>
                        <div class="col col-md-3" id="disable" style="display: none;">
                            <div class="form-group">
                                <button type="button" onclick="changeStatus('disabled')" class="btn btn-danger">Disable Period</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $('#addMeasurementPeriodForm').on('submit', function (event) {
            event.preventDefault();
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{route('performance.measurement.period.add')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    if (data.status=='success'){
                        toastr.success(data.details, 'Success');
                        $('#addMeasurementPeriodModal').modal('toggle');
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                    }
                    else if (data.status=='pending'){
                        toastr.error(data.details, 'Error');
                    }

                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr.error(valchild[0]);
                        });
                    });
                }
            });

        });

        function createNewMP() {
            $("#titl").html('Add Performance Measurement Period');
            $("#addMeasurementPeriodModal").modal();
            $("#type").val('new');
            $("#editname").val('');
            $("#disable").hide();
            $("#activate").hide();
        }

        function editMP(mp) {
           /* for(var data in mp){
                $(`#${data}`).val(mp[data]);
            }*/
            $("#addMeasurementPeriodModal").modal();
            $("#titl").html('Edit Performance Measurement Period');
             $("#type").val('edit');
             $("#editname").val(mp.name);
             $("#editfrom").val(mp.from);
             $("#editto").val(mp.to);
             $("#editid").val(mp.id);

             if (mp.status==='pending') {
                 $("#activate").show();
                 $("#disable").hide();
             }
             if (mp.status==='active'){
                 $("#disable").show();
                 $("#activate").hide();
             }
        }

        function changeStatus(status) {
            mp=$("#editid").val();
            $.ajax({
                url: '{{url('performance/measurement/period/status')}}?measurement_period='+mp+'&status='+status,
                type: 'GET',
                success: function (data, textStatus, jqXHR) {
                    if (data.status==='success'){
                        toastr.success(data.details, 'Success');
                    }
                    else{

                    }

                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                },
                error: function (data, textStatus, jqXHR) {
                    toastr.error('Error', 'Error');
                }
            });
        }

        function loadUsers(period) {
            url = '{{url('load/kpi-users')}}?measurement_period='+period,
                $("#measurement_period_kpis").load(url, function() {
                    console.log('done');
                });
        }
        function loadMPReport(period) {
            url = '{{url('load/kpi-report')}}?measurement_period='+period,
                $("#measurement_period_kpis").load(url, function() {
                    console.log('done');
                });
        }

        $(document).ready(function(){
            $("#search1").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#table1 a").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    <script src="{{asset('global/js/Plugin/table.min.js')}}"></script>
@endsection