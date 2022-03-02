@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Confirmation')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Confirmation')}}</li>
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
                            <h3 class="panel-title">Confirmation Approvals</h3>

                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped">
                                    <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Hiredate</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($user_approvals as $approval)
                                        @if($approval->confirmation)
                                            <tr>
                                                <td>{{$approval->confirmation->user->name}}</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->user->hiredate))}}</td>


                                                <td>My Approvals</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->created_at))}}</td>
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
                                                                    class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Approve/Reject</a>
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                                href="{{ url('confirmation/view_confirmation?confirmation_id='.$approval->confirmation->id) }}" ><i
                                                                        class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Confirmation</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($dr_approvals as $approval)
                                        @if($approval->confirmation)
                                            <tr>
                                                <td>{{$approval->confirmation->user->name}}</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->user->hiredate))}}</td>


                                                <td>Direct Report Approvals</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->created_at))}}</td>
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
                                                                        class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Approve/Reject</a>
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               href="{{ url('confirmation/view_confirmation?confirmation_id='.$approval->confirmation->id) }}" ><i
                                                                        class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Confirmation</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($ss_approvals as $approval)
                                        @if($approval->confirmation)
                                            <tr>
                                                <td>{{$approval->confirmation->user->name}}</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->user->hiredate))}}</td>


                                                <td>Supervisor of Supervisor Approvals</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->created_at))}}</td>
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
                                                                        class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Approve/Reject</a>
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               href="{{ url('confirmation/view_confirmation?confirmation_id='.$approval->confirmation->id) }}" ><i
                                                                        class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Confirmation</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($role_approvals as $approval)
                                        @if($approval->confirmation)
                                            <tr>
                                                <td>{{$approval->confirmation->user->name}}</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->user->hiredate))}}</td>


                                                <td>Role Approvals</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->created_at))}}</td>
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
                                                                        class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Approve/Reject</a>
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               href="{{ url('confirmation/view_confirmation?confirmation_id='.$approval->confirmation->id) }}" ><i
                                                                        class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Confirmation</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($group_approvals as $approval)
                                        @if($approval->confirmation)
                                            <tr>
                                                <td>{{$approval->confirmation->user->name}}</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->user->hiredate))}}</td>


                                                <td>Group Approvals</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->created_at))}}</td>
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
                                                                        class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Approve/Reject</a>
                                                            <a style="cursor:pointer;" class="dropdown-item"
                                                               href="{{ url('confirmation/view_confirmation?confirmation_id='.$approval->confirmation->id) }}" ><i
                                                                        class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Confirmation</a>

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
                                        <th>Employee</th>

                                        <th>Hire Date</th>
                                        <th>Comment</th>
                                        <th>Process Started On</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach(Auth::user()->confirmation_approvals as $approval)
                                        @if($approval->confirmation && $approval->status!=0)
                                            <tr>
                                                <td>{{$approval->confirmation->user->name}}</td>
                                                <td>{{date("F j, Y", strtotime($approval->confirmation->user->hiredate))}}</td>

                                                <td>{{$approval->comments}}</td>

                                                <td>{{date("F j, Y", strtotime($approval->confirmation->created_at))}}</td>
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
    @include('confirmation.modals.approve_request')
    {{-- Document Request Details Modal --}}
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="leaveDetailsModal" aria-hidden="true"
         aria-labelledby="leaveDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Document Request Details</h4>
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
            $(document).on('submit', '#approveConfirmationForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{url('confirmation')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        if (data == 'success') {
                            toastr.success("Changes saved successfully", 'Success');
                            $('#approveConfirmationModal').modal('toggle');
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

        function approve(confirmation_approval_id) {
            $(document).ready(function () {
                $('#approval_id').val(confirmation_approval_id);
                $('#approveConfirmationModal').modal();
            });

        }

    </script>
@endsection
