@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  a.list-group-item:hover {
    text-decoration: none;
    background-color: #3f51b5;
}
</style>
@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Shift Schedules</h1>
      
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
      <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Shift Schedules</h3>
                  <div class="panel-actions">
                    <div class="panel-actions">
                      <button class="btn btn-info" data-toggle="modal" data-target="#addShiftScheduleModal">Schedule Shifts</button>

                    </div>
                  </div>
                </div>
                <div class="panel-body">
      <table class="table table-striped" >
        <thead>
          <tr>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Prepared on</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($shift_schedules as $shift_schedule)
          <tr>
            <td>{{date("F j, Y", strtotime($shift_schedule->start_date))}}</td>
            <td>{{date("F j, Y", strtotime($shift_schedule->end_date))}}</td>
            <td>{{date("F j, Y", strtotime($shift_schedule->created_at))}}</td>
            <td>
              <div class="btn-group" role="group">
                  <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                  data-toggle="dropdown" aria-expanded="false">
                    Action
                  </button>
                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                  <a style="cursor:pointer;"class="dropdown-item" id="{{$shift_schedule->id}}" href="{{ route('shift_schedule.show',['shift_schedule_id'=>$shift_schedule->id]) }}"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View More</a>
                  
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
  <!-- Site Action -->
   @include('attendance.modals.addShiftSchedule')
    
   @endsection
  <!-- End Add User Form -->

@section('scripts')
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
  $('.datepicker').datepicker({
    autoclose: true
});
  $(document).on('submit','#addShiftScheduleForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('schedule_shifts')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
              console.log(data);
                if(data=='exists')
                  {
                     toastr.info("Schedule cannot be created as schedule already exist during this period",'Info');
                
              
                  }
                  if(data=='success'){
                     toastr.success("Schedule Created successfully",'Success');
                
                $('#addShiftScheduleModal').modal('toggle');
          $( "#ldr" ).load('{{route('shift_schedules')}}');
                  }
                
            },
            error:function(data, textStatus, jqXHR){
               jQuery.each( data['responseJSON'], function( i, val ) {
                jQuery.each( val, function( i, valchild ) {
                toastr.error(valchild[0]);
              });  
              });
            }
        });
      
    });
  $(document).ready(function() {
    $('#vacancytable').DataTable( {
        "paging":   false,
        "searching": false,
        "info":     false
    } );
} );

  function showRequests() {
    $('#requestsModal').modal();
  }
  function showUsers() {
    $('#usersModal').modal();
  }
</script>
@endsection