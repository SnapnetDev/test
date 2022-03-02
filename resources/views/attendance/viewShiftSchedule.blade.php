@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
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
      <h1 class="page-title">Shift Schedule from {{date("F j, Y", strtotime($shift_schedule->start_date))}} to 
      {{date("F j, Y", strtotime($shift_schedule->end_date))}}</h1>
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
                  <h3 class="panel-title">User Schedules
                  <div class="panel-actions">
                    
                  </div>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
      <table class="table table-striped" >
        <thead>
          <tr>
            <th>User</th>
            <th>Shift</th>
            <th>Start Time</th>
            <th>End Time</th>
          </tr>
        </thead>
        <tbody>
          @foreach($shift_schedule->usershiftschedules as $schedule)
          <tr>
            <td><a href="{{ route('shift_schedule.user',['user_id'=>$schedule->user->id]) }}">{{$schedule->user->name}}</a></td>
             <td>{{$schedule->shift->type}}</td>
             <td>{{date(' h:i:s a',strtotime($schedule->shift->start_time))}}</td>
             <td>{{date(' h:i:s a',strtotime($schedule->shift->end_time))}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    </div>
  </div>
  </div>
  </div>
  <!-- Site Action -->

  <!-- End Add User Form -->
  @endsection
@section('scripts')
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript">
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
  function viewMore(detail_id)
{
  
      $("#detailLoader").load('{{ url('/usertimesheets') }}/'+detail_id);
    $('#attendanceDetailsModal').modal();
  
}
</script>
@endsection