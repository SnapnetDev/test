@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
  <style media="screen">
    .form-cont{
      border: 1px solid #cccccc;
      padding: 10px;
      border-radius: 5px;
    }
    #stgcont {
      list-style: none;
    }
    #stgcont li{
      margin-bottom: 10px;
    }
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Confirmations</h1>
      <div class="page-header-actions">
    <div class="row no-space w-250 hidden-sm-down">

      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="counter">
          <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

        </div>
      </div>
    </div>
  </div>
    </div>
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-4" id="details">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Employee Details</h3>
                        <div class="panel-actions">
                            <div class="panel-actions">



                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tr>
                                <th style="width:20%">Employee Name</th>
                                <td style="width:80%">{{$confirmation->user->name}}</td>
                            </tr>
                            <tr>
                                <th>Line Manager</th>
                                <td>{{$confirmation->user->plmanager?$confirmation->user->plmanager->name:''}}</td>
                            </tr>
                            <tr>
                                <th>Hiredate</th>
                                <td>{{date("F j, Y", strtotime($confirmation->user->hiredate))}}</td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td>
                                    @if($confirmation->user->job)
                                        {{$confirmation->user->job->department->name}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Employee Designation</th>
                                <td>
                                    @if($confirmation->user->job)
                                        {{$confirmation->user->job->title}}
                                    @endif
                                </td>
                            </tr>


                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4" id="requirements">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Confirmation Requirements</h3>
                        <div class="panel-actions">
                            <div class="panel-actions">


                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th >Name</th>
                                <th >Uploaded</th>
                                <th >Required</th>
                                @if(Auth::user()->id==$confirmation->user->id)
                                <th >Action</th>
                                    @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($confirmation_requirements as $requirement)
                                <tr>
                                    <th >{{$requirement->name}}</th>
                                    <th ><span class=" tag tag-outline  {{$confirmation->requirements->contains('id',$requirement->id)?'tag-success':'tag-warning'}}">{{$confirmation->requirements->contains('id',$requirement->id)?'uploaded':'pending'}}</span></th>
                                    <th>{{$requirement->compulsory==1?'Yes':'No'}}</th>
                                    @if(Auth::user()->id==$confirmation->user->id)
                                    <td ><div class="btn-group" role="group">
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="exampleIconDropdown1"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu"
                                                 aria-labelledby="exampleIconDropdown1" role="menu">
                                                
                                                 @if($confirmation->requirements->contains('id',$requirement->id))
                                                    <a style="cursor:pointer;"class="dropdown-item" id="" href="{{url('confirmation/download?confirmation_id='.$confirmation->id.'&requirement_id='.$requirement->id)}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Requirement Document</a>
                                                @endif
                                                <a style="cursor:pointer;" class="dropdown-item"
                                                   id="{{$requirement->id}}" onclick="upload(this.id)"><i
                                                            class="fa fa-upload" aria-hidden="true"></i>&nbsp;&nbsp;Upload
                                                    requirement</a>

                                            </div>
                                        </div></td>
                                        @endif
                                </tr>
                            @endforeach

                            </tbody>



                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4" id="approvals">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Confirmation Approvals</h3>
                        <div class="panel-actions">
                            <div class="panel-actions">


                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                <div class="table-responsive">

                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Stage</th>
                            <th>Approved By</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Time in Stage</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($confirmation->approvals as $approval)
                            <tr>
                                <td>{{$approval->stage->name}}</td>
                                <td>{{$approval->approver_id>0?$approval->approver->name:""}}</td>
                                <td>{{$approval->comment}}</td>
                                <td><span class=" tag   {{$approval->status==0?'tag-warning':($approval->status==1?'tag-success':'tag-danger')}}">{{$approval->status==0?'pending':($approval->status==1?'approved':'rejected')}}</span></td>
                                <td>{{ $approval->created_at==$approval->updated_at?\Carbon\Carbon::parse($approval->created_at)->diffForHumans():\Carbon\Carbon::parse($approval->created_at)->diffForHumans($approval->updated_at) }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div
            </div>
                </div>
        </div>


  </div>


</div>
    @include('confirmation.modals.uploadrequirementfile')
 @endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
      // $('#confirmation_table').DataTable();
      // $('#staff_table').DataTable();
    $('.input-daterange').datepicker({
    autoclose: true
});

{{--
    $('#gtable tbody').on('click', 'tr', function () {
        var id = this.id;
        var index = $.inArray(id, selected);

        // if ( index === -1 ) {
        //     selected.push( id );
        // } else {
        //     selected.splice( index, 1 );
        // }

        $(this).toggleClass('selected');
    }); --}}
} );
  $(function() {
      $('.datepicker').datepicker();
      $('.select2').select2();
      $('#separation_type').select2({
          ajax: {
              delay: 250,
              processResults: function (data) {
                  return {
                      results: data
                  };
              },
              url: function () {
                  return '{{url('separation/separation_types')}}/';
              }
          },
          tags: true
      });



  $('#UploadConfirmationRequirementFileForm').submit(function(e){
      e.preventDefault();
      var form = $(this);
      var formdata = false;
      if (window.FormData){
          formdata = new FormData(form[0]);
      }
      $.ajax({
          url         : '{{url('confirmation')}}',
          data        : formdata ? formdata : form.serialize(),
          cache       : false,
          contentType : false,
          processData : false,
          type        : 'POST',
          success     : function(data, textStatus, jqXHR){

              toastr["success"]("Changes saved successfully",'Success');
              $('#UploadConfirmationRequirementFileModal').modal('toggle');
              location.reload();
          },
          error:function(data, textStatus, jqXHR){
              jQuery.each( data['responseJSON'], function( i, val ) {
                  jQuery.each( val, function( i, valchild ) {
                      toastr["error"](valchild[0]);
                  });
              });
          }
      });

  });



  });
  function upload(requirement_id) {
      $(document).ready(function () {
          $('#requirement_id').val(requirement_id);
          $('#UploadConfirmationRequirementFileModal').modal();
      });

  }
  </script>
@endsection
