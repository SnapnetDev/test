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
      <h1 class="page-title">Training Budgets</h1>
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
                <h3 class="panel-title">Edit Budget</h3>
                <div class="panel-actions">
                  <a href="{{ route('training.budget') }}" class="btn btn-info">List Budget</a>
                </div>
              </div>


                <form class="form-horizontal" method="POST" action="{{ route('save_budget') }}">
                {{ csrf_field() }}                
                <input type="hidden" class="form-control" placeholder="" id="id" name="id" value="{{$id}}">

                  <div class="panel panel-info panel-line" style="">

                    <div class="panel-body">
                      <div class="form-group">
                          <div class="col-md-6">
                          <label for="trainee_id">* Trainee</label>
                              <select class="form-control" name="trainee_id" id="trainee_id" required>
                                @if($one_trainee)<option value="{{$one_trainee->id}}"> {{$one_trainee->name}} </option> @else <option value=""></option> @endif
                                @if($trainee)
                                    @foreach($trainee as $trainee)
                                        <option value="{{$trainee->id}}"> {{$trainee->name}} </option>                            
                                    @endforeach
                                @endif
                              </select>
                          </div>

                          <div class="col-md-6">
                              <label for="purpose" class="label-marg">* Purpose</label>
                              <input type="text" class="form-control" placeholder="" id="purpose" name="purpose" value="{{$budget->purpose}}" required="">
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-6">
                              <label for="amount" class="label-marg">* Amount <i class="units">in &#8358;</i></label>
                              <input type="text" class="form-control" placeholder="" id="amount" name="amount" value="{{$budget->amount}}" required="">
                          </div>

                          <div class="col-md-6">
                          <label for="status_id" class="label-marg">Status</label>
                              <select class="form-control" name="status_id" id="status_id">
                                @if($one_status)<option value="{{$one_status->id}}"> {{$one_status->name}} </option> @else <option value=""></option> @endif
                                @if($status)
                                    @foreach($status as $status)
                                        <option value="{{$status->id}}"> {{$status->name}} </option>                            
                                    @endforeach
                                @endif
                              </select>
                          </div>
                      </div>

                      
                    </div>
                    <div class="panel-footer" style="margin-bottom: 20px; margin-right: 15px">
                      <button type="submit" class="btn btn-success pull-right" onclick="return confirm('Are you sure you want to UPDATE Budget Details?')"> Save Budget </button>
                    </div> <br>
                  </div>
              </form>

          </div>


          </div>


          {{-- Filters --}}
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Budgets</h3>
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
