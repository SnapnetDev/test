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
                  <h3 class="panel-title">Timesheets</h3>
                  <div class="panel-actions">
                    <div class="panel-actions">
                      <button class="btn btn-info" data-toggle="modal" data-target="#generateTimesheet">Generate Timesheet</button>

                    </div>
                  </div>
                </div>
                <div class="panel-body">
      <table class="table table-striped" >
        <thead>
          <tr>
            <th>Month</th>
            <th>Year</th>
            <th>Prepared on</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($timesheets as $timesheet)
          <tr>
            <td>{{ date('F', mktime(0, 0, 0, $timesheet->month, 10))}}</td>
            <td>{{$timesheet->year}}</td>
            <td>{{date("F j, Y", strtotime($timesheet->created_at))}}</td>
            <td>
              <div class="btn-group" role="group">
                  <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                  data-toggle="dropdown" aria-expanded="false">
                    Action
                  </button>
                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                  <a style="cursor:pointer;"class="dropdown-item" id="{{$timesheet->id}}" href="{{ route('timesheets.show',['timesheet_id'=>$timesheet->id]) }}"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View More</a>
                  
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
  @endsection
    
  
  <!-- End Add User Form -->

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
</script>
@endsection