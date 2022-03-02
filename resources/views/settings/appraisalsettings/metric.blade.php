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
		              <h3 class="panel-title">Appraisal Metrics</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addMetricModal">Add Metric</button>

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
		                        <th >Fillable:</th>
		                        <th >Manager<br>Approves:</th>
		                        <th >Employee<br>Approves:</th>
		                        <th>Active</th>
		                        <th >Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($metrics as $metric)
		                    	<tr>
		                    		<td>{{$metric->name}}</td>
		                    		<td>{{$metric->description}}</td>
		                    		<td>{{$metric->fillable == 1 ? 'Yes' : ($metric->fillable == 0? 'No':'') }}</td>
		                    		<td>{{$metric->manager_approves == 1 ? 'Yes' : ($metric->manager_approves == 0? 'No':'') }}</td>
		                    		<td>{{$metric->employee_approves == 1 ? 'Yes' : ($metric->employee_approves == 0? 'No':'') }}</td>
		                    		<td>{{$metric->active == 1 ? 'In Use' : ($metric->active == 0? 'Not In Use':'') }}</td>
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$metric->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Metric</a>
				                      @if($metric->fillable==0)
				                      <a class="dropdown-item linker" id="{{$metric->id}}" href="{{ url('appriasalsettings/sub_metric?metric_id='.$metric->id) }}"><i class="fa fa-puzzle-piece" aria-hidden="true"></i>&nbsp;Sub Metrics</a>
				                      @endif
				                      <a class="dropdown-item " id="{{$metric->id}}"  onclick="deleteMetric({{$metric->id}})"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Metric</a>
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
  

  	$(document).on('submit','#addMetricForm',function(event){
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

