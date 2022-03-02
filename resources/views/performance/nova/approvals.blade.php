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
            <h1 class="page-title">Performance Approvals</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">Performance Approvals</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-350 hidden-sm-down"></div>
            </div>
        </div>

        <div class="page-content container-fluid">
            <div class="row">
                <div class="col-lg-12 masonry-item">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Performance Approvals {{ $mp->name }}</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable" >
                                    <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>User</th>
                                        <th>Approve As</th>
                                        <th>Score</th>
                                        <th>User Comment</th>
                                        <th>Manager Comment</th>
                                        <th>SoS Comment</th>
                                        <th>Line Executive Comment</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sos as $item)
                                        <tr>
                                           <td>{{ $item->user->emp_num }}</td>
                                           <td>{{ $item->user->name }}</td>
                                           <td>Approve as SoS</td>
                                           <td>{{ $item->score }}</td>
                                           <td>{{ $item->user_comment }}</td>
                                           <td>{{ $item->manager_comment }}</td>
                                           <td>{{ $item->sos_comment }}</td>
                                           <td>{{ $item->line_executive_comment }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                                       @if($item->sos_response=='')
                                                            <a style="cursor:pointer;" class="dropdown-item" onclick="return sendApproval({{$item->id}},'sos','approve')"
                                                               href="#">
                                                                Approve</a>
                                                            <a style="cursor:pointer;" class="dropdown-item" onclick="return sendApproval({{$item->id}},'sos','reject')"
                                                               href="#">
                                                                Reject</a>
                                                           @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach($line_executive as $item2)
                                        <tr>
                                           <td>{{ $item2->user->emp_num }}</td>
                                           <td>{{ $item2->user->name }}</td>
                                           <td>Approve as Line Executive</td>
                                           <td>{{ $item2->score }}</td>
                                           <td>{{ $item2->user_comment }}</td>
                                           <td>{{ $item2->manager_comment }}</td>
                                           <td>{{ $item2->sos_comment }}</td>
                                           <td>{{ $item2->line_executive_comment }}</td>
                                           <td>{{ $item2->status }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        Respond
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                                        @if($item->line_executive_response=='')
                                                            <a style="cursor:pointer;" class="dropdown-item" onclick="return sendApproval({{$item2->id}},'line_executive','approve')"
                                                               href="#">
                                                               Approve</a>

                                                            <a style="cursor:pointer;" class="dropdown-item" onclick="return sendApproval({{$item2->id}},'line_executive','reject')"
                                                               href="#">
                                                               Reject</a>
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
    </div>

    <div class="modal fade in modal-3d-flip-horizontal modal-info modal-md" id="sendApprovalModal" aria-hidden="true"
         aria-labelledby="enterDetails" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Comment on KPI</h4>
                </div>
                <div class="modal-body">
                    <p id="textbox"></p>
                    <form id="sendApprovalForm">
                        @csrf
                        <input type="hidden" name="np_user" id="np_user">
                        <input type="hidden" name="approve_as" id="approve_as">
                        <input type="hidden" name="response" id="response">

                        <div class="form-group">
                            <label class="form-control-label" for="inputBasicEmail">Your Comment</label>
                            <textarea class="form-control" name="comment" id="comment" cols="30" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
       function sendApproval(np_user,approve_as, response){
           $("#sendApprovalModal").modal();
           $("#textbox").html('You have chosen to '+response+' this KPI Score. Enter your reason below');

           $("#np_user").val(np_user);
           $("#approve_as").val(approve_as);
           $("#response").val(response);
       }

       $('#sendApprovalForm').on('submit', function (event) {
           event.preventDefault();
           var form = $(this);
           var formdata = false;
           if (window.FormData) {
               formdata = new FormData(form[0]);
           }
           $.ajax({
               url: '{{route('performance.kpi.approval.send')}}',
               data: formdata ? formdata : form.serialize(),
               cache: false,
               contentType: false,
               processData: false,
               type: 'POST',
               success: function (data, textStatus, jqXHR) {
                   toastr.success("Changes saved successfully", 'Success');
                   $('#sendApprovalModal').modal('toggle');
                  /* setTimeout(function(){
                       window.location.reload();
                   },2000);*/

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

    </script>
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/vendor/jt-timepicker/jquery.timepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/datepair.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/jquery.datepair.min.js')}}"></script>
@endsection