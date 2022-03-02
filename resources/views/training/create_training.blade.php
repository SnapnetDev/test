@extends('layouts.master')
@section('stylesheets')
  {{-- <link rel="stylesheet" href="{{ asset('datatables/DataTables-1.10.16/css/dataTables.bootstrap.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('datatables/datatables.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">Trainings</h1>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
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
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">New Training</h3>
                <div class="panel-actions">
                  <a href="{{ route('training') }}" class="btn btn-info">List Training</a>
                </div>
              </div>


                <form class="form-horizontal" method="POST" action="{{ route('save_training') }}">
                {{ csrf_field() }}
                  <div class="panel panel-info panel-line" style="">

                    <div class="panel-body">
                      <div class="form-group">
                          <div class="col-md-6">
                              <label for="training_name" class="label-marg">* Training Name</label>
                              <input type="text" class="form-control" placeholder="" id="training_name" name="training_name" required="">
                          </div>

                          <div class="col-md-6"> 
                          <label for="training_mode" class="label-marg">* Training Mode</label>
                              <select class="form-control" name="training_mode" id="training_mode" required>
                                <option value="">  </option>
                                <option value="Classroom"> Classroom </option> 
                                <option value="Hands-on"> Hands-on </option>
                                <option value="Online"> Online </option>
                              </select>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-6">
                              <label for="duration" class="label-marg">Duration</label>
                              <input type="text" class="form-control" placeholder="" id="duration" name="duration">
                          </div>

                          <div class="col-md-6">
                          <label for="department_id" class="label-marg">Department</label>
                              <select class="form-control" name="department_id" id="department_id">
                                <option value="">  </option>
                                @if($dept)
                                    @foreach($dept as $dept)
                                        <option value="{{$dept->id}}"> {{$dept->name}} </option>                            
                                    @endforeach
                                @endif
                              </select>
                          </div>                         
                      </div>

                      <div class="form-group">
                          <div class="col-md-12">
                              <label for="remark" class="label-marg">Remark</label>
                              <textarea rows="2" class="form-control" placeholder="" id="remark" name="remark"></textarea>
                          </div>
                      </div>

                      
                    </div>
                    <div class="panel-footer" style="margin-bottom: 20px; margin-right: 15px">
                      <button type="submit" class="btn btn-success pull-right"> Create Training </button>
                    </div> <br>
                  </div>
              </form>

          </div>


          </div>


          {{-- Filters --}}
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Training</h3>
                <div class="panel-actions">
                  <a href="#"></a>
                </div>
              </div>
              
              </div>
              <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Support</h3>
              </div>

              <div class="panel-body">
                Need help? Email us at support@snapnet.com.ng
              </div>
            </div>
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Group Help</h3>
              </div>

              <div class="panel-body">
                <ul>
                  <li></li>
                </ul>
              </div>
            </div>
            </div>
            
          </div>
        </div>



      </div>
   
@endsection
@section('scripts')
  {{-- <script type="text/javascript" src="{{ asset('datatables/DataTables-1.10.16/js/dataTables.bootstrap.min.js')}}"></script> --}}
  <script type="text/javascript" src="{{ asset('datatables/datatables.min.js')}}"></script>
  <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

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
  </script>


@endsection
