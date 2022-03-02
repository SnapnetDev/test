@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
  <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
  <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Absence Management')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Absence Management')}}</li>
		  </ol>
		  <div class="page-header-actions">
		    <div class="row no-space w-250 hidden-sm-down">

		      <div class="col-sm-6 col-xs-12">
		        <div class="counter">
		          <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

		        </div>
		      </div>
		      <div class="col-sm-6 col-xs-12">
		        <div class="counter">
		          <span class="counter-number font-weight-medium" id="time"></span>
		        </div>
		      </div>
		    </div>
		  </div>
	</div>
    
      <div class="page-content container-fluid">
      	</div>
      </div>
</div>

    </div>
  	
	</div>
  <!-- End Page -->
  <div class="modal fade in modal-3d-flip-horizontal modal-info" id="attendanceDetailsModal" aria-hidden="true" aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog " >
	      <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Clock In History</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12" id="detailLoader"> 
                  	
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              	
                  
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	    </div>
	  </div>
@endsection
@section('scripts')
<script type="text/javascript">
function datesearch(type=0){
	
	console.log("Hello");
	startdate=$('#startdate').val();
	starttime=$('#starttime').val();
	enddate=$('#enddate').val();
	endtime=$('#endtime').val();
	empname=$('#q').val();

	if(empname!=""){
		addionalsearch="&q="+empname;
	}
	else{
		addionalsearch="";
	}
	if(startdate=="" || starttime=="" || enddate=="" || endtime==""){
		toastr.error("Please fill In all fields");
		
		return ;
	}
	
	if(type==1){
		
	window.location='{{url('attendance/timesearch')}}?startdate='+startdate+'&enddate='+enddate+'&starttime='+starttime+'&enddtime='+endtime+'&type=1'+addtionalsearch;

	return ;
	}
	window.location='{{url('attendance/timesearch')}}?startdate='+startdate+'&enddate='+enddate+'&starttime='+starttime+'&enddtime='+endtime+'&type=0'+addtionalsearch;
}
function viewMore(attendance_id)
{
	// $.get('{{ url('/attendance/getdetails') }}/'+attendance_id,function(data){
    	$("#detailLoader").load('{{ url('/attendance/getdetails') }}/'+attendance_id);
    $('#attendanceDetailsModal').modal();
  // });
}

</script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
  <script src="{{asset('global/vendor/jt-timepicker/jquery.timepicker.min.js')}}"></script>
  <script src="{{asset('global/vendor/datepair/datepair.min.js')}}"></script>
  <script src="{{asset('global/vendor/datepair/jquery.datepair.min.js')}}"></script>
@endsection