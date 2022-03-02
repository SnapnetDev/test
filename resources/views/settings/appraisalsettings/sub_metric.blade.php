	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Companies')}}</li>
		    <li class="breadcrumb-item active">{{__('You are Here')}}</li>
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
      	<div class="row">
        	<div class="col-md-12 col-xs-12">
        		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Appraisal Sub Metrics for {{$metric->name}}</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addSubMetricModal">Add Sub Metric</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            
	                  <table id="exampleTablePagination" data-toggle="table" 
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th >Name:</th>
		                        <th >Description:</th>
		                        <th >Has Target:</th>
		                        <th >Weight:</th>
		                        <th>Target</th>
		                        <th >Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($sub_metrics as $sub_metric)
		                    	<tr>
		                    		<td>{{$sub_metric->name}}</td>
		                    		<td>{{$sub_metric->description}}</td>
		                    		<td>{{$sub_metric->has_target == 1 ? 'Yes' : ($sub_metric->has_target == 0? 'No':'') }}</td>
		                    		<td>{{$sub_metric->weight}}</td>
		                    		<td>{{$sub_metric->target}}</td>
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$sub_metric->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Sub Metric</a>
				                      
				                      <a class="dropdown-item " id="{{$sub_metric->id}}"  onclick="deleteSubMetric({{$sub_metric->id}})"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Sub Metric</a>
				                    </div>
				                  </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse
		                    	
		                    </tbody>
	                  </table>
	          		</div>
	          		
		          </div>
	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>
	  {{-- Add Metric Modal --}}
	   @include('settings.appriasalsettings.modals.addmetric')
	  {{-- edit metric modal --}}
	   @include('settings.appraisalsettings.modals.editmetric')
	  <!-- End Page -->
	    <script type="text/javascript">
	    	

	    	
	

		

		


		



  	$(function() {
  

  	$(document).on('submit','#addSubMetricForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('settings/appriasal')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           $('#addMetricModal').modal('toggle');
					$( "#ldr" ).load('{{url('appraisal_setting')}}');

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
  	$(document).on('submit','#editMetricForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('appraisal_setting')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#editMetricModal').modal('toggle');
					$( "#ldr" ).load('{{url('appraisal_settings')}}');
		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr["error"](valchild[0]);
							});  
							});
		        }
		    });
      
		});
  	});
  	function prepareEditData(metric_id){
    $.get('{{ url('/appraisal_setting/metric') }}/'+metric_id,function(data){
    	console.log(data);
     $('#editid').val(data.id);
     $('#editmname').val(data.name);
     $('#editmdescription').val(data.description);
     $('#editmfillable').val(data.fillable);
     $('#editmmanager_appraises').val(data.manager_appraises);
     $('#editmemployee_appraises').val(data.employee_appraises);
     $('#editmactive').val(data.active);
     
    });
    $('#editMetricModal').modal();
  }
  function deleteMetric(metric_id){
  	event.preventDefault();
  	alertify.confirm('Are you sure you want to delete this metric some appraisals may depend on it?', function () {
    $.get('{{ url('/appraisal_setting/delete_metric') }}/'+metric_id,function(data){
    	
    	if (data=='success') {
    		toastr["success"]("Changes saved successfully",'Success');
    	}else{
    		toastr["error"]("No metric was selected",'Error');
    	}
     });
     alertify.success('Metric deleted succesfully');
              location.reload();
            }, function () {
              alertify.error('Metric not deleted');
            });
  }
  
  </script>

