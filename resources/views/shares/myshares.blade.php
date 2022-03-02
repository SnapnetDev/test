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
            <h1 class="page-title">{{__('My Shares : ')}} : {{ $user->name }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('My Shares')}}</li>
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
                            <div class="counter-label text-uppercase m-b-5"><b>Shares Vested</b>
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
                            <div class="counter-label text-uppercase m-b-5"><b>Shares Pending</b>
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
                            <div class="counter-label text-uppercase m-b-5"><b>Total Shares</b>
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
                            <h3 class="panel-title">My Shares Breakdown</h3>
                            <div class="panel-actions">
                                <button class="btn btn-primary" data-target="#exampleNiftyFadeScale"
                                        data-toggle="modal" type="button">Estimate Shares Dividend
                                </button>


                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                <thead>
                                <tr>
                                    <th>Number of SHARES</th>
                                    <th>Start Date</th>
                                    <th>Vest Duration</th>
                                    <th>Years Vested</th>
                                    <th>Shares Vested</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Number of Shares</th>
                                    <th>Start Date</th>
                                    <th>Vest Duration</th>
                                    <th>Years Vested</th>
                                    <th>Shares Vested</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($shares as $share)
                                    <tr>
                                        <td>{{number_format($share->no_of_shares)}}</td>
                                        <td>{{$share->start_date}}</td>
                                        <td>{{$share->years_vested}}</td>
                                        <td>{{$share->shares_vested->where('status','vested')->count()}}</td>
                                        <td>{{number_format($share->shares_vested->where('status','vested')->sum('no_of_shares'))}}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu"
                                                     x-placement="bottom-start"
                                                     style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item" style="cursor:pointer;" id="{{$share->id}}"
                                                       onclick="viewMore(this.id)">
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
             aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-simple">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Estimate Shares Dividend</h4>
                    </div>
                    <div class="modal-body">

                        <div class="example">
                            <form class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label">Total Shares: </label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" placeholder="Total Shares"
                                               value="{{number_format($total)}}" readonly>
                                        <input type="hidden" id="totalShares" value="{{ $total }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label">Total Vested Shares: </label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" placeholder="Total Vested Shares"
                                               value="{{number_format($vested)}}" readonly>
                                        <input type="hidden" id="vestedShares" value="{{ $vested }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label">Dividend per Share &#8358; : </label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" id="price" placeholder="Price Per Share" value="7">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label">Year </label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="icon fa fa-calendar"
                                                                           aria-hidden="true"></i></span>
                                            <input type="text" class="form-control datepair-date datepair-start"
                                                   id="startdate2" data-plugin="datepicker" name="date" autocomplete="off"
                                                   value="{{ isset($year) ? $year : '' }}" onchange="updateDate()">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label">Total Vested Shares by end of <span
                                                id="year">{{$year}}</span>: </label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control"
                                               value="{{number_format($vested_end_of_year)}}" readonly id="vestedSharesEndOfYear2">
                                        <input type="hidden" id="vestedSharesEndOfYear" value="{{ $vested_end_of_year }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label">Amount: </label>
                                    <div class="col-md-8">
                                        <h4>&#8358; <span id="result">{{ number_format($vested_end_of_year*7,2) }}</span>
                                        </h4>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="button" class="btn btn-primary" onclick="callc()">Calculate</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>




        <!-- End Page -->
        <div class="modal fade in modal-3d-flip-horizontal modal-info" id="viewMoreModal" aria-hidden="true"
             aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="training_title">Shares Vested Timeline</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row row-lg col-xs-12">
                            <div class="col-xs-12" id="detailLoader">

                            </div>
                            <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-xs-12">


                            <!-- End Example Textarea -->
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
        $(function () {
            setInterval(function () {
                $('#time').html(new Date(new Date().getTime()).toLocaleTimeString());
            }, 1000);
        });



        function updateDate() {
            var newyear = $('#startdate2').val();
            $('#year').html(newyear);
            $.get('{{ url('/end-of-year/shares') }}?user_id={{ $user->id }}'+'&year='+newyear, function (data) {
                console.log(data);
                $('#vestedSharesEndOfYear').val(data);
                $('#vestedSharesEndOfYear2').val(data);
            });
        }

        function callc(){
            var vestedendofyear = $('#vestedSharesEndOfYear').val();
            var price = $('#price').val();
            var total = price * vestedendofyear;
            var totaln = total.toLocaleString();
            $('#result').html(totaln);
            $('#resultval').val(totaln);
        }

        $("#startdate2").datepicker({
            format: " yyyy", // Notice the Extra space at the beginning
            viewMode: "years",
            minViewMode: "years"
        });
    </script>

@endsection