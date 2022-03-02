@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
  <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
  <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
   <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Time and Attendance')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Export Time sheets')}}</li>
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
      	
		
          
			 
<div class="col-md-12 col-xs-12 col-md-12" >
<div class="panel panel-info" >
  <div class="panel-heading">
                  <h3 class="panel-title">Export Timesheet</h3>
                  <div class="panel-actions">
                   
                  </div>
                </div>
        <div class="panel-body container-fluid">
          <br>
          <br>
          <br>
          <div class="row row-lg">
            

            <div class="col-xl-12 col-xs-12">

              <div class="example-wrap">
			  	
                <p id="basicExample">
				
				<form method="GET" action="{{url("/cust_rts")}}">
        <div class="col-md-4" style="margin-left:-40px">
      
        <div class="input-group  " >
                    <span class="input-group-addon">
                     From <i class="icon fa fa-calendar" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control datepair-date datepair-start" id="startdate" data-plugin="datepicker" name="from" >
                   
                 
                    
                  </div>
                  </div>
                  <div class="col-md-4">
                     <div class="input-group  " >
                     <span class="input-group-addon">
                     To <i class="icon fa fa-calendar" aria-hidden="true"></i>
                    </span>
                   <input type="text" class="form-control datepair-date datepair-start" id="enddate" data-plugin="datepicker" name="to" >
                 </div>
                 </div>
          
          <div class="col-xl-3">
          <button title="Export to Excel" class="btn btn-success btn-sm" type="submit">Export Report</button>
          
              
          </div>
          </form>
				  {{-- <div class="col-xl-5">
				  <div class="input-group col-xl-5" style="margin-top:-23px; margin-left:15px; ">
                   
                    <span class="input-group-addon">
                      <i class="icon fa fa-calendar" aria-hidden="true"></i>
                    </span>
                    <input id="enddate" type="text" class="form-control datepair-date datepair-end" name="end" data-plugin="datepicker">
                 
                    <span class="input-group-addon">
                      <i class="icon fa fa-clock-o" aria-hidden="true"></i>
                    </span>
                    <input id="endtime" type="text" class="form-control datepair-time datepair-end ui-timepicker-input" data-plugin="timepicker" autocomplete="off">
                 
                    <span style="cursor:pointer;" onclick="datesearch()" title="Search" class="input-group-addon">
                    
					<i class="fa fa-search "></i>
                    </span>
					<span style="cursor:pointer;" onclick="datesearch(1)" title="Export to Excel" class="input-group-addon"><i class="fa fa-file-excel-o"></i>
                    
                    </span>
                  </div>
                  </div> --}}
				  
</p><div class="col-md-12" style="margin-bottom:40px;"></div>

                <div class="example">
				  <div class="pull-right"><b>
				{{-- {{_t('About :total result(s)',['total'=>$attendances->total()])}} --}}  </b> 
				
            </div>
          </div>
        </div>
      </div>
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
  <script type="text/javascript" src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script type="text/javascript">
    $('#atttable').DataTable();
  </script>
@endsection