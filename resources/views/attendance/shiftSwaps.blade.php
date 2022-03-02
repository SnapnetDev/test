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
      <h1 class="page-title">Shift Swaps Requests</h1>
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
                  <h3 class="panel-title">Shift Swaps Requests</h3>
                  <div class="panel-actions">
                    
                  
                </div>
                <div class="panel-body">
                  
                  <table class="table table-striped" >
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Initiator</th>
                      <th>To swap with</th>
                      <th>Initiated on</th>
                      <th>Current Shift</th>
                      <th>Intending Shift</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($shiftswaps as $shiftswap)
                    <tr>
                      <td>{{date("F j, Y", strtotime($shiftswap->date))}}</td>
                      <td>{{$shiftswap->owner->name}}</td>
                      <td>{{$shiftswap->swapper->name}}</td>
                      <td>{{date("F j, Y", strtotime($shiftswap->created_at))}}</td>
                      <td>{{$shiftswap->userShiftSchedule->shift->type}}</td>
                      <td>{{$shiftswap->newShift->type}}</td>
                      <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                            data-toggle="dropdown" aria-expanded="false">
                              Action
                            </button>
                          <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                            <a style="cursor:pointer;"class="dropdown-item text-success" id="{{$shiftswap->id}}" href="{{ route('shiftSwap.approve',['shift_swap_id'=>$shiftswap->id]) }}"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Approve</a>
                            <a style="cursor:pointer;"class="dropdown-item text-danger" id="{{$shiftswap->id}}" href="{{ route('shiftSwap.cancel', ['shift_swap_id'=>$shiftswap->id]) }}"><i class="fa fa-close" aria-hidden="true"></i>&nbsp;Reject</a>
                            
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
  <!-- Site Action -->
 {{-- Add Location Modal --}}
     @include('attendance.modals.shiftSwapModal')
  <!-- End Add User Form -->
  @endsection
@section('scripts')


<script src="{{ asset('global/vendor/fullcal/lib/moment.min.js') }}"></script>
<script src="{{ asset('global/vendor/fullcal/fullcalendar.min.js') }}"></script>
{{-- {!! $calendar->script() !!} --}}
<script type="text/javascript">
$(function(){
    $('#calendar').fullCalendar({
     

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