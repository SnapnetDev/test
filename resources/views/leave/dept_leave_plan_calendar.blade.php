@extends('layouts.master')
@section('stylesheets')


<link rel="stylesheet" href="{{ asset('global/vendor/fullcal/fullcalendar.min.css') }}"/>
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
      <h1 class="page-title">Leave Plan Calendar for {{Auth::user()->job?Auth::user()->job->department->name:''}}</h1>
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
                  <h3 class="panel-title">Leave Plan Calendar</h3>
                  
              </div>
                <div class="panel-body">
                  
                  <div id="calendar"></div>
                </div>
  </div>
  </div>
  </div>
  <!-- Site Action -->
 {{-- Add Location Modal --}}
    @include('leave.modals.dept_leave_plan_day')
  <!-- End Add User Form -->
  @endsection
@section('scripts')

 
<script src="{{ asset('global/vendor/fullcal/lib/moment.min.js') }}"></script>
<script src="{{ asset('global/vendor/fullcal/fullcalendar.min.js') }}"></script>
{{-- {!! $calendar->script() !!} --}}
<script type="text/javascript">
$(function(){
    $('#calendar').fullCalendar({
         noEventsMessage:'{{__('No Plan For today')}}',
     allDayText:'{{__('Plan for Today')}}',
     eventLimit: true,
      defaultView: 'month',
          header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
      },
      events: {
        url: '{{url('leave/dept_leave_plan_calendar_json')}}',
        error: function() {
          $('#script-warning').show();
        },
          color: '#263238',     // an option!
          textColor: '#ffffff' // an option!
        
      },
      eventClick: function(eventObj) {
        
         
      
    },
     dayClick: function(date, jsEvent, view) {
       $.get("{{url('leave/dept_leave_day_view?date=')}}"+ date.format(),function(response){
         $('#leave_plan_cont').empty();
          $('#leave_plan_cont').html(response);
          $('#deptLeavePlanDayListModal').modal('toggle');
    // alert('Clicked on: ' + date.format());

    // alert('Current view: ' + view.name);

    });

  }
    
    });

     $(document).on('submit','#swapShiftForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('swap_shift')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

                toastr.success("Swap successfully applied for",'Success');
                $('#swapShiftModal').modal('toggle');
          
            },
            error:function(data, textStatus, jqXHR){
               
                toastr.error("A Shift swap has previously been applied for this day");
             
            }
        });
      
    });

});
</script>
@endsection