@extends('layouts.master')
@section('stylesheets')
    <link href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.min.css')}}">
    <link href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">Ongoing Trainings</h1>
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
          <div class="col-md-12">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @elseif(Session::has('error'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Training Details</h3>
                <div class="panel-actions">
                </div>
              </div>

              <div class="panel-body">
                <table class="table table-stripped" id="tr_table">
                  <thead>
                    <tr>
                      <th>Training</th>
                      <th>Trainee</th>
                      <th>Training Mode</th>
                      <th>Duration</th>
                      <th>Start</th>
                      <th>End</th>
                      <th>Status</th>
                      <th style="text-align: right;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($trainings as $training)
                        <tr>
                          <td>@if($training->trainings){{ $training->trainings->training_name }}@endif</td>
                          <td>@if($training->trainees){{ $training->trainees->count() }}@endif</td>
                          <td>{{ $training->training_mode }}</td>
                          <td>{{ $training->duration }} Days</td>
                          <td>{{ date('F j, Y',strtotime($training->proposed_start_date)) }}</td>
                          <td>{{ date('F j, Y',strtotime($training->proposed_end_date)) }}</td>
                          <td>@if($training->status){{ $training->status->name }}@endif</td>
                          <td>
                              <a class="my-btn btn-sm text-info pull-right" id="{{$training->id}}" data-toggle="tooltip" title="Edit" href="{{route('edit_ongoing_training', $training->id)}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                          </td>
                        </tr>
                      @endforeach

                      <form id="addTrainingForm" class="form-horizontal" method="POST" action="{{ route('save_start_training') }}">
                      {{ csrf_field() }} 
                        <tr>
                          <td>
                            <select class="form-control" name="training_id" id="training_ids" required>
                                <option value="">  </option>
                                @if($train)
                                    @foreach($train as $train)
                                        <option value="{{$train->id}}"> {{$train->training_name}} </option>                            
                                    @endforeach
                                @endif
                              </select>
                          </td>
                          <td>
                            <select class="form-control select2" name="trainee_id[]" multiple id="trainee_id" required>
                                <option value="">  </option>
                                @if($trainees)
                                    @foreach($trainees as $trainee)
                                        <option value="{{$trainee->id}}"> {{$trainee->name}} </option>                            
                                    @endforeach
                                @endif
                              </select>
                          </td>
                          <td><input type="text" class="form-control" placeholder="" id="training_mode" name="training_mode" required=""></td>
                          <td><input type="text" class="form-control" placeholder="" id="duration" name="duration" required=""></td>
                          <td><input type="date" class="form-control" placeholder="" id="proposed_start_date" name="proposed_start_date" required=""></td>
                          <td><input type="date" class="form-control" placeholder="" id="proposed_end_date" name="proposed_end_date" required=""></td>
                          <td>
                            <select class="form-control" name="status_id" id="status_id">
                                <option value="">  </option>
                                @if($train_status)
                                    @foreach($train_status as $train_status)
                                        <option value="{{$train_status->id}}"> {{$train_status->name}} </option>                            
                                    @endforeach
                                @endif
                            </select>
                          </td>
                          <td>
                              <button type="submit" data-toggle="tooltip" title="Add" id="" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus" aria-hidden="true"></i>
                              </button>
                          </td>
                        </tr>
                      </form>
                  </tbody>
                </table>
              {{-- {!! $trainings->appends(Request::capture()->except('page'))->render() !!} --}}
            </div>
          </div>


          </div>




          {{-- Budget --}}
          
            
          </div>
        </div>



      </div>







   
@endsection
@section('scripts')
  <script type="text/javascript" src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

  <script type="text/javascript">
      $(function() 
      {          
        $('.select2').select2();
          $(document).on('change','#training_ids', function(event)
          {
            console.log($(this).val());  
            var id = $(this).val();    
             $('#training_mode').empty();  
             $('#duration').val('');
              $.get('{{url('getTraineeDetails')}}?id=' +id, function(data)
              {  //success data
                $.each(data, function(index, trainingObj)
                {                      
                  $('#training_mode').val(trainingObj.training_mode);
                  $('#duration').val(trainingObj.duration);
                   console.log(trainingObj);
                });
              });        
          });


          $('.datepicker').datepicker({    autoclose: true });

          //$('#tr_table').DataTable();
       });
  </script>


@endsection
