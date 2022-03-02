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
        <div class="row" data-plugin="matchHeight" data-by-row="true">
            <!-- First Row -->
            <div class="col-xl-4 col-md-6 col-xs-12 info-panel">
                <div class="card card-shadow">
                    <div class="card-block bg-white p-20">
                        <button type="button" class="btn btn-floating btn-sm btn-success" >
                            <i class="fa  fa-check-square-o"></i>
                        </button>
                        <span class="m-l-15 font-weight-400 ">Total Confirmed Staff</span>
                        <div class="content-text text-xs-center m-b-0">

                            <span class=" font-weight-100">{{$successful_confirmations->count()}} </span>
                            <p class="blue-grey-400 font-weight-100 m-0">Persons</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-xs-12 info-panel">
                <div class="card card-shadow">
                    <div class="card-block bg-white p-20">
                        <button type="button" class="btn btn-floating btn-sm btn-warning">
                            <i class="fa fa-question"></i>
                        </button>
                        <span class="m-l-15 font-weight-400 ">Pending Confirmations</span>
                        <div class="content-text text-xs-center m-b-0">
                            <i class="text-success icon wb-triangle-down font-size-20">
                            </i>
                            <span class=" font-weight-100">{{$pending_confirmations->count()}}</span>
                            <p class="blue-grey-400 font-weight-100 m-0">Persons</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-xs-12 info-panel">
                <div class="card card-shadow">
                    <div class="card-block bg-white p-20">
                        <button type="button" class="btn btn-floating btn-sm btn-success" data-toggle="modal" data-target="#requests">
                            <i class="fa fa-square-o"></i>
                        </button>
                        <span class="m-l-15 font-weight-400 ">Total Unconfirmed Staff</span>
                        <div class="content-text text-xs-center m-b-0">
                            <i class="text-danger  font-size-20">
                            </i>
                            <span class=" font-weight-100">{{$total_unconfirmed_staff->count()}}</span>
                            <p class="blue-grey-400 font-weight-100 m-0"> Persons</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

          <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Employees to be Confirmed</h3>
                  <div class="panel-actions">


                  </div>

              </div>

              <div class="panel-body">
            <table class="table table-stripped uitable" id="confirmation_table">
              <thead>
                <tr>
                  <th>Name</th>
                   <th>Initiator</th>
                   <th>Hire Date</th>
                   <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($confirmations as $confirmation)
                    <tr>
                      <td>{{ $confirmation->user->name }}</td>
                        <td>{{ $confirmation->initiator->name }}</td>
                      <td>{{ date("F j, Y", strtotime($confirmation->user->hiredate)) }}</td>
                      <td>{{$confirmation->status==1?'Confirmed':'Pending' }}</td>

                      <td>

                 <span  data-toggle="tooltip" title="View"><a href="{{ url('confirmation/view_confirmation?confirmation_id='.$confirmation->id) }}"  class="btn-sm btn btn-info   "><i class="fa fa-eye" aria-hidden="true"></i></a></span>
            </td>

                    </tr>
                  @endforeach

              </tbody>

            </table>

          </div>
        </div>
                <div class="panel panel-info panel-line">
                    <div class="panel-heading main-color-bg">
                        <h3 class="panel-title">Unconfirmed Employees</h3>
                        <div class="panel-actions">


                        </div>

                    </div>

                    <div class="panel-body">
                        <table class="table table-stripped uitable" id="staff_table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Staff ID</th>
                                <th>Hire Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($total_unconfirmed_staff as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{$user->emp_num}}</td>
                                    <td>{{ date("F j, Y", strtotime($user->hiredate)) }}</td>
                                    <td>{{$user->my_status}}</td>

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
                                                   id="{{$user->id}}" onclick="confirmStaff({{$user->id}});"><i
                                                            class="fa fa-eye" aria-hidden="true"></i>Start Confirmation</a>

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
      $('#confirmation_table').DataTable();
      $('#staff_table').DataTable();
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
      $(document).on('submit','#addSeparationForm',function(event){
          event.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{route('separation.store')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  if(data=='success'){
                      toastr.success("User has been designated as separated successfully",'Success');
                      $('#addSeparationModal').modal('toggle');
                      location.reload();
                  }
                  if(data=='failed'){
                      toastr.error("You have no set the HireDate",'Wrong Hire date');
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

  });
  function confirmStaff(user_id){

      alertify.confirm("Are you sure you want to begin this confirmation process ", function () {


          $.get('{{ url('confirmation/start_confirmation_process') }}',{user_id:user_id},function(data){
              if (data=='success') {
                  toastr["success"]("Confirmation process started successfully",'Success');

                  location.reload();
              }else{
                  toastr["error"]("Error starting Confirmation process. Please Create workflow!",'Error');
              }

          });
      }, function () {
          alertify.error('Confirmation Requirement not started');
      });
  }
  </script>
@endsection
