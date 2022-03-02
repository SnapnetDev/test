@extends('layouts.master')
@section('stylesheets')


<link rel="stylesheet" href="{{ asset('global/vendor/fullcal/fullcalendar.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
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
      <h1 class="page-title">Project Timesheet Calendar </h1>
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
                  <h3 class="panel-title">Project Timesheet Calendar</h3>
                  
              </div>
                <div class="panel-body">
                    <div class="col-md-2">
                       <button class="btn btn-primary text-center" data-toggle="modal"
                       data-target="#addMultipleDaysModal">Add for multiple days</button>
                       <br>
                       <br>
                        <ul class="list-group list-group-dividered list-group-bordered">
                            <li class="list-group-item " style="background: #1A237E;color:#ffffff;">Total Hours Worked:{{$total_hours}} Hours</li>
                            <li class="list-group-item bg-light-green-900 tx-white" style="color:#ffffff;">Leave:{{$leave_hours}} Hours</li>
                            <li class="list-group-item bg-orange-900" style="color:#ffffff;">Holiday:{{$holiday_hours}} Hours</li>
                            
                        </ul>
                        <br>
                        <ul class="list-group list-group-dividered list-group-bordered">
                          @foreach($timesheet_sum as $sum)
                          <li class="list-group-item" id="hours_worked"><strong>{{$sum['name']}}({{$sum['fund_code']}}): {{$sum['sum']}} Hours({{$sum['sum'] > 0?round(($sum['sum']/$total_hours)*100,2):0}}%)</strong></li>
                          @endforeach
                      </ul>
                    </div>
                    <div class="col-md-10">
                  <div id="calendar"></div>
                </div>
  </div>
        </div>
      
  </div>
  </div>
  <!-- Site Action -->
 {{-- Add Location Modal --}}
    @include('leave.modals.comp_leave_plan_day')
    @include('project_management.modals.modify_project_timesheet')
    @include('project_management.modals.modify_project_timesheet_detail')
    @include('project_management.modals.multiple_days_timesheet_detail')
  <!-- End Add User Form -->
  @endsection
@section('scripts')

 
<script src="{{ asset('global/vendor/fullcal/lib/moment.min.js') }}"></script>
<script src="{{ asset('global/vendor/fullcal/fullcalendar.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
{{-- {!! $calendar->script() !!} --}}
<script type="text/javascript">
function datePicker() {
            $('.period_daterange').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        }
$(function(){
  datePicker();
    $('#calendar').fullCalendar({
        height:800,
         noEventsMessage:'{{__('No Plan For today')}}',
     allDayText:'{{__('Plan for Today')}}',
     eventLimit: true,
      defaultView: 'month',
          header: {
       
      },
      events: {
        url: '{{url('projects/timesheet_calendar_json')}}',
        error: function() {
          $('#script-warning').show();
        },
          color: '#263238',     // an option!
          textColor: '#ffffff' // an option!
        
      },
      eventClick: function(eventObj) {

        if(eventObj.id>0){
         
          $('#detail_id').val(eventObj.id);
          $('#detail_project_id').val(eventObj.project_id);
          $('#detail_lin_code').val(eventObj.lin_code);
          $('#detail_hour').val(eventObj.hours);
          var intro = document.querySelector('.delete-detail'); 
          intro.setAttribute('id',eventObj.id);
          $('#modifyTimesheetDetailModal').modal('toggle');
        }
       
        
        
      
    },
     dayClick: function(date, jsEvent, view) {
       $.get("{{url('leave/comp_leave_day_view?date=')}}"+ date.format(),function(response){
         $('#leave_plan_cont').empty();
          $('#leave_plan_cont').html(response);
          $('#timesheet_title').html(date.format());
          $('#timesheet_date').val(date.format());
          $('#modifyTimesheetModal').modal('toggle');
    // alert('Clicked on: ' + date.format());

    // alert('Current view: ' + view.name);

    });

  },
  
    
    });

    $(document).on('submit', '#modifyTimesheetForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{route('projects.store')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');
                $('#modifyTimesheetModal').modal('toggle');
                
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });
    $(document).on('submit', '#modifyTimesheetDetailForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{route('projects.store')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');
                $('#modifyTimesheetDetailModal').modal('toggle');
                
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    $(document).on('submit', '#addMultipleDaysForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{route('projects.store')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');
                $('#addMultipleDaysModal').modal('toggle');
                
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

});
$(document).ready(function () {
$('#addComponent').on('click', function () {
    $('.projectDiv').clone().appendTo('#plancont').removeClass('projectDiv');
 
});
$(document).on('click', ".remComponent", function () {
    if ($('#plancont').text() == '') {
        return toastr.info('You cannot remove the only component');
    }
    $(this).parents('li').remove();
});
});
function deletedetail(detail_id){
  alertify.confirm('Are you sure you want to remove this entry?', function () {

$.get('{{ url('/projects/delete_timesheet_detail') }}/',{ detail_id: detail_id },function(data){
if (data=='success') {
toastr.success("Entry removed successfully",'Success');
location.reload();
}else{
 toastr.error("Error removing Entry",'Success');
}

});
}, function () {
alertify.error('Entry not deleted');
});
}
</script>
@endsection