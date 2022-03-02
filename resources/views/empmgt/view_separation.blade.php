@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
      <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
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
      <h1 class="page-title">Separations</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{url('separation')}}">Separations</a></li>
        <li class="breadcrumb-item active">Separation</li>
      </ol>
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
        <div class="panel panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title">Separation Details</h3>
              <div class="panel-actions">

              </div>
            </div>
            <div class="panel-body">
              {{-- <div class="ribbon ribbon-clip ribbon-reverse ribbon-danger">
                        <span class="ribbon-inner"><a href="#" id="{{$joblisting->id}}" onclick="deleteJobListing(this.id)" style="color: #fff;"> Add Job to Favorites</a></span>
                      </div>
                      <div class="ribbon ribbon-clip ribbon-primary">
                        <span class="ribbon-inner"><a href="#" onclick="prepareEditData({{$joblisting->id}});" style="color: #fff;">Apply for Job</a></span>
                      </div> --}}




            </div>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Employee Name</li>
              <li class="list-group-item ">
                {{$separation->user->name}}
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Employee Staff ID</li>
              <li class="list-group-item ">
                {{$separation->user->emp_num}}
              </li>
            </ul>
             <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Hire Date</li>
              <li class="list-group-item ">
               {{ date("F j, Y", strtotime($separation->hiredate)) }}
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Separation Date</li>
              <li class="list-group-item ">
              {{ date("F j, Y", strtotime($separation->date_of_separation)) }}
              </li>
            </ul>
             <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Time in Employment</li>
              <li class="list-group-item ">
              {{ $diff = Carbon\Carbon::parse($separation->hiredate)->diffForHumans(Carbon\Carbon::parse($separation->date_of_separation),true,false,5) }}
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Reason for Separation</li>
              <li class="list-group-item ">
                {{$separation->separation_type->name}}
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Comment</li>
              <li class="list-group-item ">
                  {{$separation->comment}}
              </li>
            </ul>
             <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Exit Interview Form</li>
              <li class="list-group-item ">
                  @if($sp->employee_fills_form==1)
                      <a style="cursor:pointer;"class="dropdown-item" id="" href="{{url('separation/download_exit_interview_form?separation_id='.$separation->id)}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Document</a>
                      @else
              <a href="{{ file_exists(public_path('uploads/separation'.$separation->exit_interview_form))?asset('uploads/separation'.$separation->exit_interview_form):''}}" target="_blank" class="btn btn-info">View</a>
              @endif
              </li>
            </ul>
            <ul class="list-group list-group-bordered list-group-full">
                <li class="list-group-item bg-grey-300">Exit Check out Form</li>
              <li class="list-group-item ">
                  @if($sp->use_approval_process==1)
                      <a style="cursor:pointer;"class="dropdown-item" id="" href="{{url('separation/download_exit_clearance_form?separation_id='.$separation->id)}}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Document</a>
                  @else
                <a href="{{ file_exists(public_path('uploads/separation'.$separation->exit_checkout_form))?asset('uploads/separation'.$separation->exit_checkout_form):''}}" target="_blank" class="btn btn-info">View</a>
              @endif
              </li>
            </ul>

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
  <script type="text/javascript">
  $(document).ready(function() {
    $('.input-daterange').datepicker({
    autoclose: true
});
$('.select2').select2();
    var selected = [];

     $('.active-toggle').change(function() {
       var id = $(this).attr('id');
        var isChecked = $(this).is(":checked");
        console.log(isChecked);
        $.get(
          '{{ route('workflows.alter-status') }}',
          { id: id, status: isChecked },
          function(data) {
            if(data=="enabled"){
              toastr.success('Enabled!', 'Workflow Status');
            }
            if(data=="disabled"){
              toastr.error('Disabled!', 'Workflow Status')
            }else{
              toastr.error(data, 'Workflow Status');

            }


          }
        );

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
  </script>
@endsection
