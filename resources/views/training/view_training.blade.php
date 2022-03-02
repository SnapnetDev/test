@extends('layouts.master')
@section('stylesheets')
  {{-- <link rel="stylesheet" href="{{ asset('datatables/DataTables-1.10.16/css/dataTables.bootstrap.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('datatables/datatables.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('global/vendor/alertify/alertify.css') }}" rel="stylesheet" />
@endsection

@section('content')
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">Recommended Trainings</h1>
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
          {{-- Left Div --}}
          <div class="col-md-9">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">View Training</h3>
                <div class="panel-actions">
                  <a href="{{ route('training') }}" class="btn btn-info">List Training</a>
                </div>
              </div>


                <form class="form-horizontal" method="POST" action="{{ route('save_training') }}">
                {{ csrf_field() }}                
                <input type="hidden" class="form-control" placeholder="" id="id" name="id" value="{{$id}}">

                  <div class="panel panel-info panel-line" style="">

                    <div class="panel-body">
                      <div class="form-group">
                          <div class="col-md-6">
                              <label for="training_name" class="label-marg">* Training Name</label>
                              <input type="text" class="form-control" placeholder="" id="training_name" name="training_name" value="{{$train->training_name}}" disabled="">
                          </div>

                          <div class="col-md-6"> 
                          <label for="training_mode" class="label-marg">* Training Mode</label>
                              <select class="form-control" name="training_mode" id="training_mode" disabled>
                                @if($train)<option value="{{$train->training_mode}}"> {{$train->training_mode}} </option> @else <option value=""> </option> @endif
                              </select>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-6">
                              <label for="duration" class="label-marg">Duration</label>
                              <input type="text" class="form-control" placeholder="" id="duration" name="duration" value="{{$train->duration}}" disabled>
                          </div>

                          <div class="col-md-6">
                          <label for="department_id" class="label-marg">Department</label>
                              <select class="form-control" name="department_id" id="department_id" disabled>
                                @if($one_dept)<option value="{{$one_dept->id}}"> {{$one_dept->name}} </option> @else <option value=""> </option> @endif
                              </select>
                          </div>                         
                      </div>

                      <div class="form-group">
                          <div class="col-md-12">
                              <label for="remark" class="label-marg">Remark</label>
                              <textarea rows="2" class="form-control" placeholder="" id="remark" name="remark" disabled>{{$train->remark}}</textarea>
                          </div>
                      </div>

                      
                    </div>


                  </div>
              </form>

          </div>


          </div>


          {{-- Filters --}}
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Users for Training</h3>
                <div class="panel-actions">
                  <a href="#"></a>
                </div>
              </div>
              <table class="table table-stripped" id="tr_table">
                  <thead>
                    <tr>
                      <th>Trainees</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($train->users as $train_user)
                        <tr>
                          <td>{{ $train_user->name }}</td>
                      @endforeach
                  </tbody>
                </table>
              
              </div>
            </div>
            
          </div>
        </div>



      </div>
   
@endsection
@section('scripts')
  {{-- <script{{ asset('datatables/DataTables-1.10.16/js/dataTables.bootstrap.min.js')}}"></script> --}}
  <script src="{{ asset('datatables/datatables.min.js')}}"></script>
  <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
  <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('global/vendor/alertify/alertify.js')}}"></script>

  <script type="text/javascript">
    $(function() 
    {

        $('.select2').select2();
        $('.input-daterange').datepicker(
        {
          autoclose: true
        });


        $('.tr_table').DataTable();

        $('#proposed_start_date').datepicker(
        {
          autoclose: true, format: "yyyy-mm-dd"
        });

        $('#proposed_end_date').datepicker(
        {
          autoclose: true, format: "yyyy-mm-dd"
        });
    });



    function deleteTrainingUser(user_id)
    {
      alertify.confirm("Are You Sure You Want To Remove User From Training?",
        function()
        {
          $.get('{{ url('/training/delete_training_user') }}/',{ user_id: user_id, training_id:{{$train->id}} },
              function(data)
              {
              if (data=='success') 
              {
                toastr.success("User in Training deleted successfully", 'Success');
              }
              else
              {
                toastr.error("Error deleting Training user", 'danger');
              }
             
            });
        },
        function()
        {
          alertify.error('Cancelled Delete');
        }); 
      
    } 
  </script>


@endsection