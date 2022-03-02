@extends('layouts.master')
@section('stylesheets')
  {{-- <link rel="stylesheet" href="{{ asset('datatables/DataTables-1.10.16/css/dataTables.bootstrap.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('datatables/datatables.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <style type="text/css">
      .nav-link.active:hover
      {
        background-color: #007FFF;
      }
    </style>
@endsection

@section('content')
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">My Trainings</h1>
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
                  {{-- <a href="{{ route('create_training') }}" class="btn btn-info">Create New Training</a> --}}
                </div>
              </div>

              @if(count(\Auth::user()->employees) == 0)
                <div class="panel-body">
                  <div class="col-xs-12 col-xl-12">
                    <!-- Example Tabs -->
                    <div class="example-wrap">
                      <div class="nav-tabs-horizontal" data-plugin="tabs">
                        <ul class="nav nav-tabs" role="tablist">
                          <li class="nav-item" role="presentation"><a class="nav-link active" data-toggle="tab" href="#exampleTabsOne" aria-controls="exampleTabsOne" role="tab">On Going Trainings</a></li>
                          <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#exampleTabsTwo" aria-controls="exampleTabsTwo" role="tab">Recommended Trainings </a></li>
                          <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#exampleTabsThree" aria-controls="exampleTabsThree" role="tab">Completed Trainings</a></li>
                          <li class="dropdown nav-item" role="presentation" style="display: none;">
                            <a class="dropdown-toggle nav-link" data-toggle="dropdown" href="#" aria-expanded="false">Menu </a>
                           <div class="dropdown-menu" role="menu">
                              <a class="dropdown-item" data-toggle="tab" href="#exampleTabsOne" aria-controls="exampleTabsOne" role="tab">On Going Trainings</a>
                              <a class="dropdown-item" data-toggle="tab" href="#exampleTabsTwo" aria-controls="exampleTabsTwo" role="tab">Recommended Trainings</a>
                              <a class="dropdown-item" data-toggle="tab" href="#exampleTabsThree" aria-controls="exampleTabsThree" role="tab">Completed Trainings</a>
                            </div>
                          </li>
                        </ul>
                        <div class="tab-content p-t-20">
                          <div class="tab-pane active" id="exampleTabsOne" role="tabpanel">
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
                                  @foreach ($ongoings as $ongoing)
                                    <tr>
                                      <td>@if($ongoing->trainings){{ $ongoing->trainings->training_name }}@endif</td>
                                      <td>@if($ongoing->trainee){{ $ongoing->trainee->name }}@endif</td>
                                      <td>{{ $ongoing->ongoing_mode }}</td>
                                      <td>{{ $ongoing->duration }} Days</td>
                                      <td>{{ $ongoing->proposed_start_date }}</td>
                                      <td>{{ $ongoing->proposed_end_date }}</td>
                                      <td>@if($ongoing->status){{ $ongoing->status->name }}@endif</td>
                                      <td>
                                          <a class="my-btn btn-sm text-info pull-right" id="{{$ongoing->id}}" data-toggle="tooltip" title="Edit" href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                      </td>
                                    </tr>
                                  @endforeach
                              </tbody>
                            </table>
                          </div>
                          <div class="tab-pane" id="exampleTabsTwo" role="tabpanel">
                            <table class="table table-stripped" id="tr_table">
                              <thead>
                                <tr>
                                  <th>Training</th>
                                  <th>Training Mode</th>
                                  <th>Duration</th>
                                  <th>Department</th>
                                  <th style="text-align: right;">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                  @foreach ($recommendeds as $recommended)
                                    <tr>
                                      <td>{{ $recommended->training_name }}</td>
                                      <td>{{ $recommended->training_mode }}</td>
                                      <td>{{ $recommended->duration }} Days</td>
                                      <td>@if($recommended->department){{ $recommended->department->name }}@endif</td>
                                      <td>
                                          <a class="my-btn btn-sm text-info pull-right" id="{{$recommended->id}}" data-toggle="tooltip" title="Edit" href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                      </td>
                                    </tr>
                                  @endforeach
                              </tbody>
                            </table>
                          </div>
                          <div class="tab-pane" id="exampleTabsThree" role="tabpanel">
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
                                  @foreach ($completeds as $completed)
                                    <tr>
                                      <td>@if($completed->trainings){{ $completed->trainings->training_name }}@endif</td>
                                      <td>@if($completed->trainee){{ $completed->trainee->name }}@endif</td>
                                      <td>{{ $completed->completed_mode }}</td>
                                      <td>{{ $completed->duration }} Days</td>
                                      <td>{{ $completed->proposed_start_date }}</td>
                                      <td>{{ $completed->proposed_end_date }}</td>
                                      <td>@if($completed->status){{ $completed->status->name }}@endif</td>
                                      <td>
                                          <a class="my-btn btn-sm text-info pull-right" id="{{$completed->id}}" data-toggle="tooltip" title="Edit" href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                      </td>
                                    </tr>
                                  @endforeach
                              </tbody>
                            </table>
                          </div>
                          <div class="tab-pane" id="exampleTabsFour" role="tabpanel">

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Example Tabs -->
                  </div>
                  
                {{-- {!! $trainings->appends(Request::capture()->except('page'))->render() !!} --}}
                </div>
              @elseif(count(\Auth::user()->employees) > 0)
                <div class="panel-body">
                  <div class="col-xs-12 col-xl-12">
                    <!-- Example Tabs -->
                    <div class="example-wrap">
                      <div class="nav-tabs-horizontal" data-plugin="tabs">
                        <ul class="nav nav-tabs" role="tablist">
                          <li class="nav-item" role="presentation"><a class="nav-link active" data-toggle="tab" href="#exampleTabsOne" aria-controls="exampleTabsOne" role="tab">On Going Trainings</a></li>
                          <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#exampleTabsTwo" aria-controls="exampleTabsTwo" role="tab">Recommended Trainings </a></li>
                          <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#exampleTabsThree" aria-controls="exampleTabsThree" role="tab">Completed Trainings</a></li>
                          <li class="dropdown nav-item" role="presentation" style="display: none;">
                            <a class="dropdown-toggle nav-link" data-toggle="dropdown" href="#" aria-expanded="false">Menu </a>
                           <div class="dropdown-menu" role="menu">
                              <a class="dropdown-item" data-toggle="tab" href="#exampleTabsOne" aria-controls="exampleTabsOne" role="tab">On Going Trainings</a>
                              <a class="dropdown-item" data-toggle="tab" href="#exampleTabsTwo" aria-controls="exampleTabsTwo" role="tab">Recommended Trainings</a>
                              <a class="dropdown-item" data-toggle="tab" href="#exampleTabsThree" aria-controls="exampleTabsThree" role="tab">Completed Trainings</a>
                            </div>
                          </li>
                        </ul>
                        <div class="tab-content p-t-20">
                          <div class="tab-pane active" id="exampleTabsOne" role="tabpanel">
                            <table class="table table-stripped" id="tr_table">
                              <thead>
                                <tr>
                                  <th>Training Line mgr</th>
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
                                @foreach(\Auth::user()->employees as $line_emp)
                                  @foreach ($ongoings as $ongoing)
                                    <tr>
                                      <td>@if($ongoing->trainings){{ $ongoing->trainings->training_name }}@endif</td>
                                      <td>@if($ongoing->trainee){{ $ongoing->trainee->name }}@endif</td>
                                      <td>{{ $ongoing->ongoing_mode }}</td>
                                      <td>{{ $ongoing->duration }} Days</td>
                                      <td>{{ $ongoing->proposed_start_date }}</td>
                                      <td>{{ $ongoing->proposed_end_date }}</td>
                                      <td>@if($ongoing->status){{ $ongoing->status->name }}@endif</td>
                                      <td>
                                          <a class="my-btn btn-sm text-info pull-right" id="{{$ongoing->id}}" data-toggle="tooltip" title="Edit" href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                      </td>
                                    </tr>
                                  @endforeach
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                          <div class="tab-pane" id="exampleTabsTwo" role="tabpanel">
                            <table class="table table-stripped" id="tr_table">
                              <thead>
                                <tr>
                                  <th>Training</th>
                                  <th>Training Mode</th>
                                  <th>Duration</th>
                                  <th>Department</th>
                                  <th style="text-align: right;">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                  @foreach ($recommendeds as $recommended)
                                    <tr>
                                      <td>{{ $recommended->training_name }}</td>
                                      <td>{{ $recommended->training_mode }}</td>
                                      <td>{{ $recommended->duration }} Days</td>
                                      <td>@if($recommended->department){{ $recommended->department->name }}@endif</td>
                                      <td>
                                          <a class="my-btn btn-sm text-info pull-right" id="{{$recommended->id}}" data-toggle="tooltip" title="Edit" href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                      </td>
                                    </tr>
                                  @endforeach
                              </tbody>
                            </table>
                          </div>
                          <div class="tab-pane" id="exampleTabsThree" role="tabpanel">
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
                                  @foreach ($completeds as $completed)
                                    <tr>
                                      <td>@if($completed->trainings){{ $completed->trainings->training_name }}@endif</td>
                                      <td>@if($completed->trainee){{ $completed->trainee->name }}@endif</td>
                                      <td>{{ $completed->completed_mode }}</td>
                                      <td>{{ $completed->duration }} Days</td>
                                      <td>{{ $completed->proposed_start_date }}</td>
                                      <td>{{ $completed->proposed_end_date }}</td>
                                      <td>@if($completed->status){{ $completed->status->name }}@endif</td>
                                      <td>
                                          <a class="my-btn btn-sm text-info pull-right" id="{{$completed->id}}" data-toggle="tooltip" title="Edit" href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                      </td>
                                    </tr>
                                  @endforeach
                              </tbody>
                            </table>
                          </div>
                          <div class="tab-pane" id="exampleTabsFour" role="tabpanel">

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Example Tabs -->
                  </div>

                  
                {{-- {!! $trainings->appends(Request::capture()->except('page'))->render() !!} --}}
                </div>
              @endif

          </div>


          </div>




          {{-- Budget --}}
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Add Budget </h3>
                <div class="panel-actions">
                  <a href="{{ route('groups.create') }}"></a>
                </div>
              </div>
              <div class="panel-body">
              <div class="table-responsive">
              <table class="table table-stripped" id="tr_table">
                  <thead>
                    <tr>
                      <th>Trainee</th>
                      <th>Purpose</th>
                      <th>Amount</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($budgets)
                      @foreach ($budgets as $budget)
                        <tr>
                          <td>@if($budget->trainee){{ $budget->trainee->name }}@endif</td>
                          <td>{{ $budget->purpose }}</td>
                          <td>&#8358; {{ $budget->amount }}</td>
                          <td>@if($budget->status){{ $budget->status->name }}@endif</td>
                        </tr>
                      @endforeach
                    @endif

                    @if(\Auth::user()->employees)
                      @foreach (\Auth::user()->employees as $emp)
                        <tr>
                          <td>{{$emp->name}}</td>
                          <td>{{$emp->phone}}</td>
                          <td>{{$emp->email}}</td>
                          <td>{{$emp->hiredate}}</td>
                        </tr>
                      @endforeach
                    @endif
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
    });


    //function to process form data
    function approveForm(formid, route)
    {
       formdata= new FormData($('#'+formid)[0]);
       formdata.append('_token','{{csrf_token()}}');
      
        $.ajax(
        {
            // Your server script to process the upload
            url: route,
            type: 'POST',
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data, status, xhr)
            {
                if(data.status=='ok')
                {
                    $('#trainee_id')..empty();     $('#purpose').val('');    $('#amount').val('');    $('#status_id').empty();   
                    //toastr.success(data.success, {timeOut:60000});
                    return;

                    alert(data.success);
                }

                //return toastr.error(data.success, {timeOut:60000});
                if(data.status == 'error')
                {
                     alert(data.success);
                }             

            },
        })

    }


    $(function()
    {
        $("#budgetForm").on('submit',function(e)
        { 
            e.preventDefault();
            approveForm('budgetForm', "{{url('/training/budget/save_budget')}}");
        });
    });

  </script>


@endsection
