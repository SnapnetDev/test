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
            <h1 class="page-title">{{__('My Commissions : ')}}{{$user->name}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Commissions')}}</li>
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
                            <h3 class="panel-title"> My Commissions history</h3>
                            <div class="panel-actions">
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                    <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Project Name</th>
                                        <th>Project Amount</th>
                                        <th>Project Status</th>
                                        <th>Project Project Status</th>
                                        <th>My Commission</th>
                                        <th>Payment Status</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Project Name</th>
                                        <th>Project Amount</th>
                                        <th>Project Status</th>
                                        <th>Project Project Status</th>
                                        <th>My Commission</th>
                                        <th>Payment Status</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($commissions as $staff_commission)
                                        <tr>
                                            <td>{{$staff_commission->opportunity->client_id}}</td>
                                            <td>{{$staff_commission->opportunity->project_name}}</td>
                                            <td>{{number_format($staff_commission->opportunity->project_amount,2)}}</td>
                                            <td>{{$staff_commission->opportunity->project_status}}</td>
                                            <td>{{$staff_commission->opportunity->payment_status}}</td>
                                            <td>{{number_format($staff_commission->commission,2)}}</td>
                                            <td>{{$staff_commission->payment_status}}</td>
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