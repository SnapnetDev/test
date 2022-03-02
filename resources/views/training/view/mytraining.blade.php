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
                <h3 class="panel-title">Traning Details</h3>
                <div class="panel-actions">
                  <a href="{{ route('create_training') }}" class="btn btn-info">Recommend Traning</a>
                </div>
              </div>

              <div class="panel-body">

                @if(count($trainings)>0)
                <table class="table table-stripped" id="tr_table">
                  <thead>
                    <tr>
                      <th>Trainee</th>
                      <th>Suggester</th>
                      <th>Training Mode</th>
                      <th>Duration</th>
                      <th>Start</th>
                      <th>End</th>
                      <th>Approver</th>
                      <th>Status</th>
                      <th style="text-align: center;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($trainings as $training)
                        <tr>
                          <td>@if($training->trainee){{ $training->trainee->name }}@endif</td>
                          <td>@if($training->suggester){{ $training->suggester->name }}@endif</td>
                          <td>{{ $training->training_mode }}</td>
                          <td>{{ $training->duration }} Days</td>
                          <td>{{ $training->start }}</td>
                          <td>{{ $training->end }}</td>
                          <td>@if($training->approver){{ $training->approver->name }}@endif</td>
                          <td>@if($training->status){{ $training->status->name }}@endif</td>
                          <td>
                              <a data-toggle="tooltip" title="Delete" id="{{$training->id}}" class="my-btn btn-sm text-danger pull-right" onclick="deletesubject(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>
                              </a>
                              <a data-toggle="tooltip" title="View" href="{{route('view_training', $training->id)}}" class="my-btn btn-sm text-success pull-right"><i class="fa fa-eye" aria-hidden="true"></i></a>
                              <a class="my-btn btn-sm text-info pull-right" id="{{$training->id}}" data-toggle="tooltip" title="Edit" href="{{route('edit_training', $training->id)}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                </table>
                @else
                  <i class=""> No Recommended Training for You. </i>
                @endif
              {{-- {!! $trainings->appends(Request::capture()->except('page'))->render() !!} --}}
            </div>
          </div>


          </div>




          {{-- Budget --}}
          <div class="col-md-3">
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Your Budget</h3>
                <div class="panel-actions">
                  <a href="{{ route('groups.create') }}"></a>
                </div>
              </div>

              @if(count($budgets)>0)
                <table class="table table-stripped" id="tr_table">
                    <thead>
                      <tr>
                        <th>Purpose</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                        @foreach ($budgets as $budget)
                          <tr>
                            <td>{{ $budget->purpose }}</td>
                            <td>&#8358; {{ $budget->amount }}</td>
                            <td>@if($budget->status){{ $budget->status->name }}@endif</td>
                          </tr>
                        @endforeach
                    </tbody>
                  </table>
                @else

                  <i class=""> No Training Budget for You. </i>

                @endif

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

  </script>


@endsection
