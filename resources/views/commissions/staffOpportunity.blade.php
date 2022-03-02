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
            <h1 class="page-title">{{__('Staff Commission for ')}} {{ $opportunity->project_name }} with {{ $opportunity->client_id }}</h1>
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
                                <button class="btn btn-outline btn-primary" data-target="#exampleNiftyFadeScale"
                                        data-toggle="modal" type="button">Add Commission to Staff
                                </button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                    <thead>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Expected</th>
                                        <th>Commission</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Expected</th>
                                        <th>Commission</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($staff_commissions as $staff_commission)
                                        <tr>
                                            <td><a style="text-decoration: none;" href="{{ url('user/commissions',$staff_commission->user->id) }}">{{$staff_commission->user->name}}</a></td>
                                            <td>{{number_format($staff_commission->expected_commission,2)}}</td>
                                            <td>{{number_format($staff_commission->commission,2)}}</td>
                                            <td>{{$staff_commission->payment_status}}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        {{-- <a class="dropdown-item"   href="#" role="menuitem">Edit Commission</a>--}}
                                                        @if($staff_commission->payment_status!='paid')
                                                            <a class="dropdown-item" href="{{ url('pay-staff-commission',$staff_commission->id) }}" role="menuitem">Pay Commission</a>
                                                        @endif
                                                        @if($staff_commission->payment_status!='paid')
                                                            <a class="dropdown-item" href="#" onclick="deleteCommission({{ $staff_commission->id }})" role="menuitem">Delete Commission</a>
                                                        @endif
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
        @include('commissions.modal.add_commission')
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
        function deleteCommission(id){
            var txt;
            var r = confirm("Are you sure you want to delete this commission?");
            if (r == true) {
                deleteCom(id)
            } else {
                toastr.error('It was not approved');
            }
        }

        function deleteCom(id){
            var token = '{{csrf_token()}}';
            senddata={'_token':token,'commission_id':id,'type':'delete'};
            $.ajax({
                url: '{{url('commissions')}}',
                type: 'POST',
                data: senddata,
                success: function (data, textStatus, jqXHR) {
                    toastr.success('Successfully Deleted Commission');
                    console.log(data)
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                },
                error: function (data, textStatus, jqXHR) {

                }
            });
        }
        $(function () {
            $(document).on('submit', '#addCommissionForm', function (event) {
                $("#addCommissionFormSubmit").hide();
                $("#loader").show();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{ url('/commissions') }}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        if (data.status==='success'){
                            toastr.success(data.details);
                            $('#addCommissionForm').trigger("reset");
                            $("#addCommissionFormSubmit").show();
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
        });
    </script>
@endsection
