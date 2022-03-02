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
      <h1 class="page-title">Performance Management</h1>
      
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
                  <h3 class="panel-title">Employee List</h3>
                  <div class="panel-actions">
                    <div class="panel-actions">
                      

                    </div>
                  </div>
                </div>
                <div class="panel-body">
      <table class="table table-striped" id="emptable" >
        <thead>
          <tr>
            <th style="width:50%;">Employee Name</th>
            <th style="width:30%;">Employee ID</th>
            <th style="width:20%;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->name}}</td>
            <td>{{$user->emp_num}}</td>
            <td>
              <div class="btn-group" role="group">
                  <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                  data-toggle="dropdown" aria-expanded="false">
                    Action
                  </button>
                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                  <a style="cursor:pointer;"class="dropdown-item" id="{{$user->id}}" href="{{ url('performances/employee?id='.$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Performance</a>
                  
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
  @endsection
  <!-- Site Action -->

    
  
  <!-- End Add User Form -->

@section('scripts')
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#emptable').DataTable();
} );

  
</script>
@endsection