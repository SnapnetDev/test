@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Leave Request')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Leave Request')}}</li>
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
      	<div class="row" data-plugin="matchHeight" data-by-row="true">
  <!-- First Row -->
  
  
  <!-- End First Row -->
  {{-- second row --}}
  <div class="col-ms-12 col-xs-12 col-md-12">
    <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Department Leave Request Approvals</h3>
		              
		            	</div>
		            <div class="panel-body">
		            	<div class="table-reponsive">
         <table class="table table striped">
         	<thead>
         		<tr>
              <th>Employee</th>
         		<th>Leave Type</th>
            <th>Replacement</th>
            <th>Balance</th>
         		<th>Starts</th>
         		<th>Ends</th>
         		<th>Priority</th>
         		<th>Reason</th>
         		<th>Approval Type</th>
         		<th>With Pay</th>
         		<th>Action</th>
         	</tr>
         	</thead>
         	<tbody>

          
          @foreach($dapprovals as $approval)
            @if($approval->leave_request)
              <tr>

           
            <td>{{$approval->leave_request->user->name}}</td>
            <td>{{$approval->leave_request->leave_name}}</td>
             <td>{{$approval->leave_request->replacement?$approval->leave_request->replacement->name:"Not stated"}}</td>
          <td>{{$approval->leave_request->balance}}</td>
              <td>{{date("F j, Y", strtotime($approval->leave_request->start_date))}}</td>
              <td>{{date("F j, Y", strtotime($approval->leave_request->end_date))}}</td>
              <td><span class=" tag tag-outline  {{$approval->leave_request->priority==0?'tag-success':($approval->leave_request->priority==1?'tag-warning':'tag-danger')}}">{{$approval->leave_request->priority==0?'normal':($approval->leave_request->priority==1?'medium':'high')}}</span></td>
              <td>{{$approval->leave_request->reason}}</td>
              <td>Departmental Approval</td>
              <td>{{$approval->leave_request->paystatus==0?'without pay':'with pay'}}</td>
              <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                    data-toggle="dropdown" aria-expanded="false">
                      Action
                    </button>
                  <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                    <a style="cursor:pointer;"class="dropdown-item" id="{{$approval->id}}" onclick="approve(this.id)"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Approve</a>
                    
                  </div>
                </div>
              </td>

          </tr>
          @endif
        @endforeach
        
         
         	</tbody>
         	
         </table>
         </div> 
		     
         
      </div>
  </div>

    </div>
  	
	</div>
</div>
</div>
  <!-- End Page -->
   @include('leave.modals.approve_request')
   {{-- Leave Request Details Modal --}}
   <div class="modal fade in modal-3d-flip-horizontal modal-info" id="leaveDetailsModal" aria-hidden="true" aria-labelledby="leaveDetailsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Leave Request Details</h4>
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
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript">
  	  $(document).ready(function() {
    $('.input-daterange').datepicker({
    autoclose: true,
    format:'yyyy-mm-dd'
});
    $(document).on('submit','#approveLeaveRequestForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('leave.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		             toastr.success("Changes saved successfully",'Success');
		            $('#approveLeaveRequestModal').modal('toggle');
                location.reload();
					
		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr.error(valchild[0]);
							});  
							});
		        }
		    });
      
		});
    });
      
      function approve(leave_approval_id)
      {
         $(document).ready(function() {
             $('#approval_id').val(leave_approval_id);
          $('#approveLeaveRequestModal').modal();
        });
        
      }

  </script>
@endsection