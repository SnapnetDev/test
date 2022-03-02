@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <!-- Font Awesome Css -->
    <link href="{{ asset('assets/e-signature/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <link href="{{ asset('assets/e-signature/css/bootstrap-select.css') }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset('assets/e-signature/css/app_style.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('assets/e-signature/css/jquery.signaturepad.css') }}" rel="stylesheet">

    <style>
        #signArea
        {
            width:304px;
            margin: 15px auto;
        }
        .sign-container
        {
            width: 90%;
            margin: auto;
        }
        .sign-preview
        {
            width: 150px;
            height: 50px;
            border: solid 1px #CFCFCF;
            margin: 10px 5px;
        }
        .tag-ingo
        {
            font-family: cursive;
            font-size: 12px;
            text-align: left;
            font-style: oblique;
        }
        .center-text
        {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Separations')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Separations')}}</li>
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
                <!-- First Row -->


                <!-- End First Row -->
                {{-- second row --}}
                <div class="col-ms-12 col-xs-12 col-md-12">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">New Separation Approvals</h3>

                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                    <tr>

                                        <th>Created At</th>
                                        <th>Employee</th>
                                        <th>Approval Type</th>

                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($user_approvals as $approval)
                                        @if($approval->separation)
                                            <tr>
                                                <td>{{date("F j, Y", strtotime($approval->separation->created_at))}}</td>
                                                <td>{{$approval->separation->user->name}}</td>

                                                <td>My Approvals</td>

                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               id="{{$approval->id}}" onclick="approve(this.id,"{{$approval->stage->name}}")"><i
                                                                    class="fa fa-eye" aria-hidden="true"></i>&nbsp;Approve/Reject</a>

                                                                <a style="cursor:pointer;" class="dropdown-item" target="_blank" id=""
                                                                   href="{{ url('separation/view_separation?separation_id='.$approval->separation->id) }}"><i
                                                                        class="fa fa-eye" aria-hidden="true" target="_blank"></i>&nbsp;View Separation</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($dr_approvals as $approval)
                                        @if($approval->separation)
                                            <tr>
                                                <td>{{date("F j, Y", strtotime($approval->separation->created_at))}}</td>
                                                <td>{{$approval->separation->user->name}}</td>

                                                <td>Direct Report Approval</td>

                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               id="{{$approval->id}}" onclick="approve(this.id)"><i
                                                                    class="fa fa-eye" aria-hidden="true"></i>&nbsp;Approve/Reject</a>

                                                            <a style="cursor:pointer;" class="dropdown-item" target="_blank" id=""
                                                               href="{{url('compensation/separation_list').'?month='.date('m-Y',strtotime($approval->separation->for))}}"><i
                                                                    class="fa fa-money" aria-hidden="true" target="_blank"></i>&nbsp;View Payroll</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($ss_approvals as $approval)
                                        @if($approval->separation)
                                            <tr>
                                                <td>{{date("F j, Y", strtotime($approval->separation->created_at))}}</td>
                                                <td>{{$approval->separation->user->name}}</td>

                                                <td>Supervisor of Supervisor Approval</td>

                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               id="{{$approval->id}}" onclick="approve(this.id)"><i
                                                                        class="fa fa-eye" aria-hidden="true"></i>&nbsp;Approve/Reject</a>

                                                            <a style="cursor:pointer;" class="dropdown-item" target="_blank" id=""
                                                               href="{{url('compensation/separation_list').'?month='.date('m-Y',strtotime($approval->separation->for))}}"><i
                                                                        class="fa fa-money" aria-hidden="true" target="_blank"></i>&nbsp;View Separation</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($role_approvals as $approval)
                                        @if($approval->separation)
                                            <tr>
                                                <td>{{date("F j, Y", strtotime($approval->separation->created_at))}}</td>
                                                <td>{{$approval->separation->user->name}}</td>

                                                <td>Role Approvals</td>

                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               id="{{$approval->id}}" onclick="approve(this.id)"><i
                                                                    class="fa fa-eye" aria-hidden="true"></i>&nbsp;Approve/Reject</a>

                                                            <a style="cursor:pointer;" class="dropdown-item" target="_blank" id=""
                                                               href="{{url('compensation/separation_list').'?month='.date('m-Y',strtotime($approval->separation->for))}}"><i
                                                                    class="fa fa-money" aria-hidden="true" target="_blank"></i>&nbsp;View Separation</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($group_approvals as $approval)
                                        @if($approval->separation )
                                            <tr>

                                                <td>{{date("F j, Y", strtotime($approval->separation->created_at))}}</td>
                                                <td>{{$approval->separation->user->name}}</td>

                                                <td>Group Approvals</td>

                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               id="{{$approval->id}}" onclick="approve(this.id)"><i
                                                                    class="fa fa-eye" aria-hidden="true"></i>&nbsp;Approve/Reject</a>

                                                            <a style="cursor:pointer;" class="dropdown-item" target="_blank" id=""
                                                               href="{{url('compensation/separation_list').'?month='.date('m-Y',strtotime($approval->separation->for))}}"><i
                                                                    class="fa fa-download" aria-hidden="true"></i>&nbsp;View Separation</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach

                                    </tbody>

                                </table>
                            </div>


                        </div>
                    </div>
                    {{--  beginning of historical approval --}}
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Approval History</h3>

                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                    <tr>

                                        <th>Employee Name</th>
                                        <th>Approved At</th>
                                        <th>Comment</th>
                                        <th>Status</th>

                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach(Auth::user()->separation_approvals as $approval)
                                        @if($approval->separation && $approval->status!=0)
                                            <tr>



                                                <td>{{$approval->separation->user->name}}</td>
                                                <td>{{date("F j, Y", strtotime($approval->updated_at))}}</td>
                                                <td>{{$approval->comments}}</td>
                                                <td><span class=" tag   {{$approval->status==0?'tag-warning':($approval->status==1?'tag-success':'tag-danger')}}">{{$approval->status==0?'pending':($approval->status==1?'approved':'rejected')}}</span></td>
                                            </tr>

                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- end of historical approval --}}

                </div>

            </div>
        </div>
    </div>
    <!-- End Page -->
    @include('separations.modals.approve_request')
    {{-- Payroll Details Modal --}}
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="leaveDetailsModal" aria-hidden="true"
         aria-labelledby="leaveDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Payroll Details</h4>
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
@endsection
@section('scripts')
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>

    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script type="text/javascript"
            src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ asset('assets/e-signature/js/numeric-1.2.6.min.js') }}"></script>
    <script src="{{ asset('assets/e-signature/js/bezier.js') }}"></script>
    <script src="{{ asset('assets/e-signature/js/jquery.signaturepad.js') }}"></script>

    <script type='text/javascript' src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
    <script src="{{ asset('assets/e-signature/js/json2.min.js') }}"></script>
    <script type="text/javascript">


        $(document).ready(function () {
            $('.select2').select2();
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
            $(document).on('submit', '#approveSeparationForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                // var img_data= html2canvas([document.getElementById('sign-pad')], {
                //     onrendered: function (canvas) {
                //         var canvas_img_data = canvas.toDataURL('image/png');
                //         var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
                //         return img_data;
                //     }
                // });




                        $.ajax({
                            url         : '{{url('separation')}}',
                            data        : formdata ? formdata : form.serialize(),
                            cache       : false,
                            contentType : false,
                            processData : false,
                            type        : 'POST',
                    success: function (data, textStatus, jqXHR) {

                        if (data == 'success') {
                            toastr.success("Changes saved successfully", 'Success');
                            $('#approveSeparationModal').modal('toggle');
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

        function approve(leave_approval_id,stage_name) {
            $(document).ready(function () {
                $('#approval_id').val(leave_approval_id);
                $('#training_title').val("Approve Separation (Stage- "+stage_name);
                $('#approveSeparationModal').modal();
            });

        }

    </script>


    <script>

        $(function(e)
        {

            $(function()
            {
                $('#signArea').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:90});
            });




            //clear signature
            $("#btnCleared").click(function(e)
            {
                $('#signArea').signaturePad().clearCanvas();
            });

        });
    </script>
@endsection
