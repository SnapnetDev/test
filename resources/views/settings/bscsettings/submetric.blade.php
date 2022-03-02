		<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item "><a class="linker" href="{{route('companies')}}">{{__('Metrics')}}</a></li>
		    <li class="breadcrumb-item ">{{__($metric->name)}}</li>
		    <li class="breadcrumb-item ">{{__('Sub Metrics')}}</li>
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
		              <h3 class="panel-title">Sub Metrics</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addSubmetricModal">Add Sub Metrics</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            	<br>
                	
	                  <table id="exampleTablePagination" data-toggle="table" 
	                  data-query-params="queryParams" data-mobile-responsive="true"
	                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
	                    <thead>
	                      <tr>
	                          <th>Name</th>
				                <th>Created By</th>
				                <th>Created at</th>
				                <th>Actions</th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@forelse($submetrics as $submetric)
	                    	<tr>
	                    		<td>{{$submetric->name}}</td>
			            		<td>{{$submetric->user->name}}</td>
			            		<td>{{date("F j, Y, g:i a", strtotime($submetric->created_at))}}</td>
	                    		<td>
	                    			<div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                    data-toggle="dropdown" aria-expanded="false">
                      Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                      <a class="dropdown-item" id="{{$submetric->id}}" onclick="prepareEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Sub Metric</a>
                      <a class="dropdown-item" id="{{$submetric->id}}" onclick="deleteDepartment(this.id);"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Sub Metric</a>
                      
                    </div>
                  </div>
	                    		</td>
	                    	</tr>
	                    	@empty
	                    	@endforelse
	                    	
	                    </tbody>
	                  </table>
		          		</div>
		          </div>
	        	</div>
	    	</div>
		</div>

    
  </div>
  <!-- End Page -->
   {{-- add department modal --}}
   @include('settings.bscsettings.modals.addsubmetric')
	  {{-- edit department modal --}}
	  @include('settings.bscsettings.modals.editsubmetric')
	  

  <script type="text/javascript">
  	$(function() {
  

  	$(document).on('submit','#addSubmetricForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('departments.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           setTimeout(function(){
            window.location.reload();
           },2000);
           return; 
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
  	$(document).on('submit','#editSubmetricForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('departments.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           setTimeout(function(){
            window.location.reload();
           },2000);
           return;
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
  	function prepareEditData(submetric_id){
    $.get('{{ url('/settings/department') }}/'+submetric_id,function(data){
    	console.log(data);
     $('#editname').val(data.name);
     $('#editid').val(data.id);
     $('#editcompany_id').val(data.company_id);
     $('#edituser').val(data.manager_id);
    });
    $('#editDepartmentModal').modal();
  }
  function deleteSUbmetric(submetric_id){
  
  alertify.confirm('Are you sure you want to delete this submetric ?', function(){ 
  $.get('{{ url('settings/departments/delete') }}/'+submetric_id,{
    submetric_id:submetric_id
  }, 
    function(data, status){
        if(data=="success"){
           toastr.success('Submetric Deleted Successfully');
           setTimeout(function(){
            window.location.reload();
           },2000);
           return; 
        }
        toastr.error(data);
    });
    });

}
  
  </script>
