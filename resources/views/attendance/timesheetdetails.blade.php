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
      <h1 class="page-title">TimeSheet</h1>
      
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
                  <h3 class="panel-title">Timesheet for {{ date('F', mktime(0, 0, 0, $timesheet->month, 10))}} {{$timesheet->year}}</h3>
                  <div class="panel-actions">
                    <a class="btn btn-success btn-lg" title="Export to excel" href="{{ url("timesheet-excel/$timesheet->id") }}"><i class="fa fa-file-excel-o"></i></a>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
      <table class="table table-striped" >
        <thead>
          <tr>
            <th>User</th>
            <th>Total Hours</th>
            <th>Weekday Hours</th>
            <th>Basic Work Hours</th>
            <th>Overtime Weekday Hours</th>
            <th>Overtime Holiday Hours</th>
            <th>Overtime Saturday Hours</th>
            <th>Overtime Sunday Houts</th>
            <th>Expected Work Hours</th>
            <th>Expected Work Days</th>
          </tr>
        </thead>
        <tbody>
          @foreach($timesheet->timesheetdetails as $detail)
          <tr>
            <td>{{$detail->user->name}}</td>
             <td>{{$detail->total_hours}}</td>
             <td>{{$detail->weekday_hours}}</td>
             <td>{{$detail->basic_work_hours}}</td>
             <td>{{$detail->overtime_weekday_hours}}</td>
             <td>{{$detail->overtime_holiday_hours}}</td>
             <td>{{$detail->overtime_saturday_hours}}</td>
             <td>{{$detail->overtime_sunday_hours}}</td>
              <td>{{$detail->expected_work_hours}}</td>
               <td>{{$detail->expected_work_days}}</td>
            <td>
              <div class="btn-group" role="group">
                  <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                  data-toggle="dropdown" aria-expanded="false">
                    Action
                  </button>
                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                  <a style="cursor:pointer;"class="dropdown-item" id="{{$detail->user_id}}" onclick="viewMore(this.id)"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View More</a>
                  
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
  <!-- Site Action -->
<div class="modal fade in modal-3d-flip-horizontal modal-info" id="attendanceDetailsModal" aria-hidden="true" aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">User Timesheet details for {{ date('F', mktime(0, 0, 0, $timesheet->month, 10))}} {{$timesheet->year}}</h4>
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