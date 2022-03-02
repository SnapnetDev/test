@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
      <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
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
      <h1 class="page-title">360 Review Nominees List</h1>
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

          <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
                 @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('error') }}
                </div>
            @endif
            <div class="panel panel-info ">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Nomination List</h3>
              </div>


              <div class="panel-body">

              <table class="table table-striped" id="userstable">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $sn=1;
                  @endphp
                  @foreach ($nominations as $nomination)
                    <tr>
                      <td>{{$sn}}</td>
                    <td>{{$nomination->user->name}}</td>
                        <td>{{$nomination->user->job?$nomination->user->job->department->name:''}}</td>
                    <td>{{$nomination->user->job?$nomination->user->job->title:''}}</td>
                    <td><button class="btn btn-icon btn-success" onclick="approveNomination({{$nomination->id}})" id="approve_{{$nomination->id}}"><i class="fa fa-check"></i></button>
                        <button class="btn btn-icon btn-danger" onclick="rejectNomination({{$nomination->id}})" id="reject_{{$nomination->id}}"><i class="fa fa-close"></i></button>

                    </td>

                    </tr>
                  @php
                    $sn++;
                  @endphp
                  @endforeach

                </tbody>
              </table>
              </div>


        </div>


          </div>
          </div>

  </div>


</div>
@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
 <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('#userstable').DataTable();
    $('.input-daterange').datepicker({
    autoclose: true
});
$('.select2').select2();
    var selected = [];


 $('#user').select2({
        ajax: {
         delay: 250,
         processResults: function (data) {
              return {
        results: data
          };
        },
        url: function (params) {
        return '{{url('bsc/usersearch')}}';
        }
        }
    });
} );
  function approveNomination(nomination_id) {
      alertify.confirm('Are you sure you want to approve this nomination?', function () {

              $.get('{{ url('/e360/approve_nomination') }}/',{ nomination_id: nomination_id },function(data){
              });
          alertify.success('Nomination approved successfully');
         $('#approve_'+ nomination_id).closest('tr').remove();

      }, function () {
          alertify.error('Nomination could not be approved');
      });

  }
  function rejectNomination(nomination_id) {
      alertify.confirm('Are you sure you want to reject this nomination?', function () {

          $.get('{{ url('/e360/reject_nomination') }}/',{ nomination_id: nomination_id },function(data){
          });
          alertify.success('Nomination rejected successfully');
          $('#reject_'+ nomination_id).closest('tr').remove();
      }, function () {
          alertify.error('Nomination could not be rejected');
      });

  }
  </script>
@endsection
