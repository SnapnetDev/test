@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
 
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
 <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
 <link rel="stylesheet" href="{{ asset('global/vendor/morris/morris.css')}}">
 <link rel="stylesheet" href="../../../global/vendor/ladda/ladda.css">
 <style type="text/css">
  	 .btn[disabled]{
  	 	pointer-events: none;
  	 	cursor: not-allowed;
  	 }
     .row_selected{background-color: #00bcd4 !important; z-index:9999;color:#fff;}
  </style>
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Employee Payroll')}}  in {{date('F Y',strtotime($date))}}</h1>
		  <ol class="breadcrumb"> 
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item "><a href="{{url('/compensation')}}">{{__('Employee Payroll')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Employee Payroll ')}} in {{date('F Y',strtotime($date))}}</li>
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

		<div class="page-content container-fluid bg-white">
			@if($has_been_run==1)
	      	<div class="row " style="padding-top:20px; padding-bottom: 30px;">
	      		<div class="col-md-2">

	      		</div>
	      		<div class="col-md-3 ">

	      			<div id="exampleMorrisDonut" style="height: 250px;"></div>
	      			<form id="monthForm" method="GET" action="{{url('compensation/payroll_list')}}" >
	                 <div class="input-group">


	                    <input type="text" id="" placeholder="mm-yyyy" name="month" class="form-control datepicker">

	                    <span class="input-group-btn">
	                      <button type="submit" class="btn btn-primary"><i class="icon fa fa-search" aria-hidden="true"></i></button>
	                    </span>


	                  </div>
	                  </form>
	      		</div>
	      		<div class="col-md-7">

	                <ul class="list-group list-group-dividered ">
	            	<li class="list-group-item"><strong>Payroll For:	{{date('M-Y',strtotime($date))}}</strong></li>
	                  <li class="list-group-item">Wallet Balance:</li>
	                  <li class="list-group-item">Basic Salary:&#8358;{{number_format( $salary,2)}}</li>
	                 <li class="list-group-item">Allowances:&#8358;{{number_format( $allowances,2)}}</li>
	                  <li class="list-group-item">Deductions:&#8358;{{number_format( $deductions+$income_tax,2)}}</li>
	                  <li class="list-group-item">Total Net Pay :&#8358;{{number_format( ($salary+$allowances-( $deductions+$income_tax)),2)}}</li>
                        <li class="list-group-item"><a href="#" class="btn btn-primary" onclick="viewRequestApproval({{$payroll->id}})">View details</a></li>
	                </ul>
	                <div class="btn-group btn-group-justified">
                        @if($payroll->approved==1)
	                	@if($payroll->payslip_issued==0)
	                    <div class="btn-group" role="group">

	                      <button type="button" id="payslipbtn" class="btn btn-primary btn-outline btn-sm" onclick="issuePayslip({{$payroll->id}})">
	                        <i class="icon fa fa-list-alt" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Issue Payslip</span>
	                      </button>
	                    </div>
	                    @elseif($payroll->payslip_issued==1)
	                    <div class="btn-group" role="group">

	                      <button type="button" id="sndpayslipbtn" class="btn btn-warning btn-outline btn-sm" onclick="sendPayslip({{$payroll->id}})">
	                        <i class="icon fa fa-envelope" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Send Payslip</span>
	                      </button>
	                    </div>
	                    <div class="btn-group" role="group">

	                      <button type="button" id="sndprojectpayslipbtn" class="btn btn-primary btn-outline btn-sm" onclick="sendProjectPayslip({{$payroll->id}})">
	                        <i class="icon fa fa-envelope" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Send Payslip to Selected</span>
	                      </button>
	                    </div>
	                    <div class="btn-group" role="group">

                                    <button type="button" id="disbursebtn" class="btn btn-success btn-outline btn-sm" onclick="disburse({{$payroll->id}})">
                                        <i class="icon fa fa-list-alt" aria-hidden="true"></i>
                                        <br>
                                        <span class="text-uppercase hidden-sm-down">Process Payments</span>
                                    </button>
                                </div>
	                    @endif
                    @else
                            @if($payroll->approved==0)
                            <div class="btn-group" role="group">

                                <button type="button" id="startapprovalbtn" class="btn btn-warning btn-outline btn-sm" onclick="startApproval({{$payroll->id}})">
                                    <i class="icon fa fa-check-square-o" aria-hidden="true"></i>
                                    <br>
                                    <span class="text-uppercase hidden-sm-down">Start Approval</span>
                                </button>
                            </div>
                                @endif
                    @endif

	                    <!--<div class="btn-group" role="group">-->
	                    <!--  <button type="button" class="btn btn-info btn-outline">-->
	                    <!--    <i class="icon fa fa-money" aria-hidden="true"></i>-->
	                    <!--    <br>-->
	                    <!--    <span class="text-uppercase hidden-sm-down">Run Payment</span>-->
	                    <!--  </button>-->
	                    <!--</div>-->
                        @if($payroll->approved==0||$payroll->approved==2)
	                    <div class="btn-group" role="group">
	                      <button type="button" id="rbk-btn"  class="rollback-button btn btn-danger btn-outline btn-sm" onclick="rollbackPayroll({{$payroll->id}})">
	                        <i class="icon fa fa-refresh" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Rollback Payroll</span>
	                      </button>
	                    </div>
                        @endif
	                    <div class="btn-group" role="group">
	                      <a type="button" class="btn btn-success btn-outline btn-sm" href="{{ url('compensation/exportforexcel?payroll_id='.$payroll->id) }}">
	                        <i class="icon fa fa-file-excel-o" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Export Payroll</span>
	                      </a>
	                    </div>
	                    <div class="btn-group" role="group">
	                      <a type="button" class="btn btn-dark btn-outline btn-sm" href="{{ url('compensation/exportford365?payroll_id='.$payroll->id) }}">
	                        <i class="icon fa fa-download" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Export for NAV</span>
	                      </a>
	                    </div>
	              	</div>

	          	</div>
	  		</div>
	  		 <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Employee List</h3>
                  <div class="panel-actions">


                </div>
            	</div>
                <div class="panel-body">
	  			<table class="table table-striped" id="dataTable">
	  				<thead>
	  					<tr>
	  						<th></th>
	  						<th>Employee Number</th>
	  						<th>Employee Name</th>
	  						<th>Employee Email</th>
	  						<th>Grade</th>
	  						<th>Gross pay</th>
	  						<th>Payroll Type</th>
	  						 @if($payroll->payslip_issued==1)
	  						 <th>Payslip</th>
	  						 @endif
	  						<th>Action</th>
	  					</tr>
	  				</thead>
	  				<tbody>
	  					@php
	  						$sn=1;
	  					@endphp
	  					@foreach ($payroll->payroll_details as $detail)
	  						<tr id="{{$detail->id}}">
	  							<td>{{$sn}}</td>
	  						<td>{{$detail->user->emp_num}}</td>
	  						<td>{{$detail->user->name}}</td>
	  						<td>{{$detail->user->email}}</td>
	  						<td>{{$detail->user->user_grade?$detail->user->user_grade->level:''}}</td>
	  						<td>&#8358;{{$detail->gross_pay}}</td>
	  						<td>{{ucfirst($detail->payroll_type)}}</td>
	  						@if($payroll->payslip_issued==1)<td> <a target="_blank"  class="  btn btn-sm btn-dark waves-effect text-center" href="{{ url('compensation/download_payslip?id='.$detail->id) }}"><i class=" icon fa fa-download" aria-hidden="true" title="download payslip"></i>Download Payslip</a></td>@endif
	  						<td> <a onclick="viewMore({{$detail->id}})" class="text-center"><i class="btn btn-sm btn-primary waves-effect icon fa fa-eye" aria-hidden="true" title="view"></i></a></td>
	  					</tr>
	  					@php
	  						$sn++;
	  					@endphp
	  					@endforeach

	  				</tbody>
	  			</table>
		  		</div>
		  	</div>
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Payroll Log</h3>
                  <div class="panel-actions">


                </div>
            	</div>
                <div class="panel-body">
	  			<table class="table table-striped" id="log-table">
	  				<thead>
	  					<tr>
	  						<th></th>
	  						<th>Employee Number</th>
	  						<th>Employee Name</th>
	  						<th>Status</th>

	  						<th>Payroll Type</th>
	  						<th>Issue</th>
	  						{{-- <th>Action</th> --}}
	  					</tr>
	  				</thead>
	  				<tbody>
	  					@php
	  						$sn=1;
	  					@endphp
	  					@foreach ($payroll->payroll_logs as $log)
	  						<tr>
	  							<td>{{$sn}}</td>
	  						<td>{{$log->user?$log->user->emp_num:''}}</td>
	  						<td>{{$log->user?$log->user->name:''}}</td>

	  						<td>{{$log->status==1?'Success':'Error'}}</td>
	  						<td>{{ucfirst($log->payroll_type)}}</td>
	  						<td>{{$log->issue}}</td>

	  					{{--	<td> <a onclick="viewLogMore({{$log->id}})" class="text-center"><i class="btn btn-sm btn-primary waves-effect icon fa fa-eye" aria-hidden="true" title="view"></i></a></td>--}}
	  					</tr>
	  					@php
	  						$sn++;
	  					@endphp
	  					@endforeach

	  				</tbody>
	  			</table>
		  		</div>
		  	</div>
  	<div class="modal fade in modal-3d-flip-horizontal modal-info" id="userPayrollDetailsModal" aria-hidden="true" aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Employee Payroll Details </h4>
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
		  	@elseif($has_been_run==0)
		  	 @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('success') }}
                </div>
            @endif
             @if (session('error'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                    {{ session('error') }}
                </div>
            @endif
		  	<div class="row " style="padding-top:20px; padding-bottom: 30px;">
	      	<div class="col-md-6">
        
          </div>	
            
                
	      		<div class="col-md-2">
                      <button id="run-btn"  type="button" class="btn btn-primary pull-right " onclick="runPayroll();"  data-style="contract">
                        <i class="icon fa fa-list-alt" aria-hidden="true"></i>

                        <span class="text-uppercase hidden-sm-down">Run Payroll</span>
                      </button>
	          	</div>
	      		<div class="col-md-2">
	      			<form id="monthForm" method="GET" action="{{url('compensation/payroll_list')}}" >
	                 <div class="input-group">


	                    <input type="text" id="" placeholder="mm-yyyy" name="month" class="form-control datepicker">
                      
	                    <span class="input-group-btn">
	                      <button title="search month" type="submit" class="btn btn-primary"><i class="icon fa fa-search" aria-hidden="true"></i></button>
	                    </span>


	                  </div>
	                  </form>
	      		</div>

	  		</div>
	  		 <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Employee List</h3>
                  <div class="panel-actions">


                </div>
            	</div>
                <div class="panel-body">
	  			<table class="table table-striped" id="payroll-table">
	  				<thead>
	  					<tr>
	  						<th>Employee Number</th>
	  						<th>Employee Name</th>
	  						<th>Job Role</th>
	  						<th>Department</th>
	  						<th>Grade</th>
	  						<th>Gross pay</th>
	  						<th>Payroll Type</th>
	  					</tr>
	  				</thead>
	  				<tbody>
	  					@foreach ($employees as $employee)
	  						<tr>
	  						<td>{{$employee->emp_num}}</td>
	  						<td>{{$employee->name}}</td>
	  						<td>{{$employee->job?$employee->job->title:''}}</td>
	  						<td>{{$employee->job?$employee->job->department->name:''}}</td>
	  						<td>{{$employee->user_grade?$employee->user_grade->level:''}}</td>
	  						<td>&#8358;{{$employee->user_grade?number_format( $employee->user_grade->basic_pay,2):''}}</td>
	  						<td>{{ucfirst($employee->payroll_type)}}</td>
	  					</tr>
	  					@endforeach

	  				</tbody>
	  			</table>
		  		</div>
		  	</div>
			@endif
	  		</div>
		</div>

      	</div>
</div>
  <!-- End Page -->


  {{-- progress modal --}}
   	<div    class="modal fade in modal-3d-flip-horizontal modal-info" id="userPayrollDetailsModalProgress" aria-hidden="true" aria-labelledby="progressModal" role="dialog" tabindex="-1" data-backdrop='false'>
      <div class="modal-dialog modal-lg" style="margin-top: 12%">
        <div class="modal-content">
          <div class="modal-header" >
            <h4 class="modal-title" id="training_title">Processing... Please wait...</h4>
          </div>
            <div class="modal-body">
                <div class="row row-lg col-xs-12">
                  <div class="col-xs-12" >
                      <div class="example-wrap">
                          <div class="example">
                              <h5>Processing Payroll.. Please Wait...</h5>
                              <div class="progress">
                                  <div class="progress-bar progress-bar-striped active" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar">
                                      <span class="sr-only progressData">0</span>
                                  </div>
                              </div>
                          </div>
                      </div>
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
<div class="modal fade in modal-3d-flip-horizontal modal-info" id="payrollDetailsModal" aria-hidden="true"
     aria-labelledby="payrollDetailsModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="training_title">Payroll Details</h4>
            </div>
            <div class="modal-body">
                <div class="row row-lg col-xs-12">
                    <div class="col-xs-12" id="dtl">

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
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
  <script src="{{ asset('global/vendor/raphael/raphael-min.js')}}"></script>
  <script src="{{ asset('global/vendor/morris/morris.min.js')}}"></script>
  <script src="{{ asset('global/vendor/ladda/spin.min.js')}}"></script>
  <script src="{{ asset('global/vendor/ladda/ladda.min.js')}}"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/material.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

  
{{--  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>--}}
  <script type="text/javascript">
      function viewRequestApproval(payroll_id)
      {
          $(document).ready(function() {
              $("#dtl").load('{{ url('/get_payroll_details') }}/'+payroll_id);
              $('#payrollDetailsModal').modal();
          });

      }
  $(function() {
    
   
     


  	});
  	  $(document).ready(function() {
    $('.datepicker').datepicker({
    autoclose: true,
    format:'mm-yyyy',
     viewMode: "months",
    minViewMode: "months"
});
     $('#payroll-table').DataTable( );

         	 	$('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
    $('#dataTable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );

  $("#dataTable tbody tr").click( function( e ) {
    	console.log($(this).attr('id'));
        if ( $(this).hasClass('row_selected') ) {
            $(this).removeClass('row_selected');
        }
        else {
            table.$('tr.row_selected')//.removeClass('row_selected');
            $(this).addClass('row_selected');
        }
    });
    var table = $('#dataTable').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        "pageLength": 25,
        "ordering": false
    } );

     $('#log-table').DataTable()

    });
  	  @if($has_been_run==1)

  	   new Morris.Donut({
      element: 'exampleMorrisDonut',
      data: [{
        label: "Basic Salary",
        value: {{$salary}}
      }, {
        label: "Allowances",
        value: {{$allowances}}
      }, {
        label: "Deductions",
        value: {{$deductions+$income_tax}}
      }, ],
      // barSizeRatio: 0.35,
      resize: true,
      colors: [Config.colors("red", 500), Config.colors("primary", 500), Config.colors("grey", 400)]
    });

  	   @endif
function viewMore(detail_id)
{

      $("#detailLoader").load('{{ url('/compensation/user_payroll_detail') }}/?payroll_detail_id='+detail_id);
    $('#userPayrollDetailsModal').modal();

}
@if($has_been_run==1)
function startApproval(payroll_id){
    $('#spinner').show();
    toastr.info('Processing ...','Info',{timeOut: 0,closeButton: true,extendedTimeOut:0 });
    $.get('{{ url('/compensation/start_approval') }}/',{ payroll_id:{{$payroll->id}} },function(data){
        if (data=='success') {
            toastr.clear();
            toastr.success("Approval Process Started",'Success');
            location.reload();
        }else{
            toastr.clear();
            toastr.error("Error Starting Approval Process",'Error');
        }
        $('#spinner').hide();
    });
}
function issuePayslip(payroll_id){
	$('#spinner').show();
	toastr.info('Processing ...','Info',{timeOut: 0,closeButton: true,extendedTimeOut:0 });
    $.get('{{ url('/compensation/issuepayslip') }}/',{ payroll_id: {{$payroll->id}}},function(data){
      if (data=='success') {
      	toastr.clear();
    toastr.success("Payslip Issued successfully",'Success');
    location.reload();
      }else{
      	toastr.clear();
        toastr.error("Error issuing payslip",'Error');
      }
      $('#spinner').hide();
    });
  }
  function sendPayslip(payroll_id){
  	alertify.confirm('Are you sure you want to end Payslip?', function () {
     $('#spinner').show();
	toastr.info('Processing ...','Info',{timeOut: 0,closeButton: true,extendedTimeOut:0 });
    $.get('{{ url('/compensation/sendpayslip') }}/',{ payroll_id: {{$payroll->id}}},function(data){
      if (data=='success') {
      	toastr.clear();
    toastr.success("Payslip Sent successfully",'Success');
    location.reload();
      }else{
      	toastr.clear();
        toastr.error("Error Sending payslip",'Error');
      }
      $('#spinner').hide();
    });

 }, function () {
    alertify.error('Payslip is not sent');
  });

  }
  function disburse(payroll_id){
          $('#spinner').show();
          toastr.info('Processing ...','Info',{timeOut: 0,closeButton: true,extendedTimeOut:0 });
          $.get('{{ url('/compensation/disburse') }}/',{ payroll_id: {{$payroll->id}}},function(data){
              if (data=='success') {
                  toastr.clear();
                  toastr.success("Payroll Processed successfully",'Success');
                  location.reload();
              }else{
                  toastr.clear();
                  toastr.error("Error processing payroll",'Error');

              }
              $('#spinner').hide();
          });
      }
  function sendProjectPayslip(payroll_id){
// 	$('#spinner').show();
	var details = $(".row_selected").map(function(){
      toastr.info('Processing ...','Info',{timeOut: 0,closeButton: true,extendedTimeOut:0 });
        return this.id;

    }).toArray();
    console.log(details);
    $.get('{{ url('/compensation/sendselectedpayslip') }}/',{ payroll_id: payroll_id,details:details },function(data){
      if (data=='success') {
      	toastr.clear();
    toastr.success(" Payslip Sent successfully",'Success');
    location.reload();
      }else{
      	toastr.clear();
        toastr.error("Error Sending  payslip",'Error');
      }
    });
    // $.get('{{ url('/compensation/sendprojectpayslip') }}/',{ payroll_id: {{$payroll->id}}},function(data){
    //   if (data=='success') {
    // toastr.success("Project Payslip Sent successfully",'Success');
    // location.reload();
    //   }else{
    //     toastr.error("Error Sending Project payslip",'Error');
    //   }
    //   $('#spinner').hide();
    // });
  }
  function rollbackPayroll(payroll_id){

      alertify.confirm('Are you sure you want to  Roll Back?', function () {
  	$('#rbk-btn').html('loading');
  	$('#rbk-btn').attr('disabled',true);
toastr.info('Processing ...','Info',{timeOut: 0,closeButton: true,extendedTimeOut:0 });
    $.get('{{ url('/compensation/rollback') }}/',{ payroll_id: {{$payroll->id}} },function(data){

      if (data=='success') {
      	toastr.clear();
    toastr.success("Rollback Successful",'Success');
    $('#rbk-btn').html('Rollback');
  	$('#rbk-btn').attr('disabled',false);
    location.reload();
      }else{
      	toastr.clear();
        toastr.error("Error Rolling Back",'Error');
        $('#rbk-btn').html('Rollback');
  	$('#rbk-btn').attr('disabled',false);
      }


    });
      }, function () {
          alertify.error('Error Rolling Back');
      });

  }

  @endif


  function reportProgress(data){

      setInterval(function(){
          formData={type:'getProgress',_token:'{{csrf_token()}}',thread_id:data.thread_id};
          $.post('{{url('compensation')}}',formData,function(data){
              $('#progressData').text(data.message+'%');
              $('.progress-bar').css('width',data.message+'%');
              if(data.message>=95){
                  window.location.reload();
              }

          })
      },5000);
  }

  function runPayroll(){
  	$('#run-btn').html('loading');
  	$('#run-btn').attr('disabled',true);
  	 toastr.info('Processing ...','Info',{timeOut: 0,closeButton: true,extendedTimeOut:0 });

      $('#userPayrollDetailsModalProgress').modal('show',{backdrop:false});

    $.get('{{url('compensation/runpayroll')}}',{month:'{{date('m-Y',strtotime($date))}}'},function(data){

      if (data.status=='success') {
      	 toastr.clear();
    toastr.success("Processing Payroll.. , Please Wait...",'Info');
          reportProgress(data);
     $('#run-btn').html('Run Payroll');
  	$('#run-btn').attr('disabled',false);
  	$('#')
    // location.reload();
      }else{
      	toastr.clear();
        toastr.error("Error Processing Payroll",'Error');
         $('#run-btn').html('Run Payroll');
  	$('#run-btn').attr('disabled',false);
      }

    });

  }
//   oTable = $('#dataTable').dataTable( );



/* Get the rows which are currently selected */
function fnGetSelected( oTableLocal )
{
    return oTableLocal.$('tr.row_selected');
}

  </script>

<script>
    function calcDeductions() {
        alertify.confirm('Are you sure you want to Calculate?', function () {
            alertify.success('Processing this request. Please wait...');
            $.ajax({
                url: '{{route('calculate.lateness')}}',
                type: 'GET',
                success: function (data, textStatus, jqXHR) {
                    alertify.success('Successfully Calculated Lateness to Specific Salary Component');

                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                },
                error: function (data, textStatus, jqXHR) {
                    alertify.error('Something went wrong')
                }
            });
        }, function () {
            alertify.error('Cancelled')
        })
    }

</script>

@endsection
