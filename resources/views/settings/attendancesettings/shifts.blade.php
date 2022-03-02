<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Attendance Settings')}}</li>
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
		              <h3 class="panel-title">Business Hours</h3>
		              <div class="panel-actions">
                			

              			</div>
		            	</div>
		            <div class="panel-body">
		            
	                  <table id="exampleTablePagination" data-toggle="table" 
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th >Company:</th>
		                        <th >Start Time:</th>
		                        <th >End Time:</th>
		                        <th >Action:</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@foreach($companies as $company)
		                    	<tr>
		                    		<td>{{$company->name}}</td>
		                    		<td>{{date("h:i A",strtotime($company->workingperiod->sob))}}</td>
		                    		<td>{{date("h:i A",strtotime($company->workingperiod->cob))}}</td>
		                    		<td><a class="dropdown-item" title="edit" class="btn btn-info" id="{{$company->workingperiod->id}}" onclick="prepareEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
		                    	</tr>
		                    	@endforeach
		                    	
		                    </tbody>
	                  </table>
	          		</div>
	          		</div>
	          		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Overtime</h3>
		              <div class="panel-actions">
              			</div>
		            	</div>
		            <div class="panel-body">
		            
	                  <table id="exampleTablePagination2" data-toggle="table" 
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th >Type:</th>
		                        <th >Rate:</th>
		                        <th >End Time:</th>
		                        <th >Action:</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@foreach($companies as $company)
		                    	<tr>
		                    		<td>{{$company->name}}</td>
		                    		<td>{{date("h:i A",strtotime($company->workingperiod->sob))}}</td>
		                    		<td>{{date("h:i A",strtotime($company->workingperiod->cob))}}</td>
		                    		<td><a class="dropdown-item" title="edit" class="btn btn-info" id="{{$company->workingperiod->id}}" onclick="prepareEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
		                    	</tr>
		                    	@endforeach
		                    	
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
{{-- edit Business Hours modal --}}
	   @include('settings.attendancesettings.modals.editworkingperiod')
<script type="text/javascript">
	 $('.clockpicker').clockpicker();
	 $(document).on('submit','#editWorkingPeriodForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('working_periods.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		             toastr.success("Changes saved successfully",'Success');
		            $('#editWorkingPeriodModal').modal('toggle');
					$( "#ldr" ).load('{{route('attendancesettings')}}');
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
  	
  function prepareEditData(working_period_id){
    $.get('{{ url('/settings/working_period') }}/'+working_period_id,function(data){
    	console.log(data);
     $('#editid').val(data.id);
     $('#editsob').val(data.sob);
     $('#editcob').val(data.cob);
    });
    $('#editWorkingPeriodModal').modal();
  }
</script>