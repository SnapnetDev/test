@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Payroll')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Payroll')}}</li>
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
            <div class="row" data-plugin="matchHeight" data-by-row="true">
                @foreach($sections as $section)
                <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
                    <div class="card card-shadow card-shadow1">
                        <div class="card-block bg-white p-30">
                            <a href="{{url('compensation/payroll_list?section='.$section->id)}}" type="button" class="btn btn-floating btn-sm btn-dark">
                            <i class="fa fa-money"></i>
                            </a>
                            <span class="m-l-15 font-weight-400">{{$section->name}}</span>
                        <div class="content-text text-xs-center m-b-0">

                        </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- End Page -->
   
    {{-- Payroll Details Modal --}}
    
@endsection
@section('scripts')
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script type="text/javascript"
            src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript">


        $(document).ready(function () {
            $('.input-daterange').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#approval').on('change', function () {
                type = $(this).val();

                if (type == 1) {

                    $('#comment').attr('required', false);

                }
                if (type == 2) {
                    $('#comment').attr('required', true);
                }


            });
            $(document).on('submit', '#approvePayrollForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('compensation.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        if (data == 'success') {
                            toastr.success("Changes saved successfully", 'Success');
                            $('#approvePayrollModal').modal('toggle');
                            location.reload();
                        } else {
                            toastr.error(data);
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
        });

        function approve(leave_approval_id) {
            $(document).ready(function () {
                $('#approval_id').val(leave_approval_id);
                $('#approvePayrollModal').modal();
            });

        }

    </script>
@endsection
