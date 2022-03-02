		<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item "><a class="linker" href="{{route('companies')}}">{{__('Companies')}}</a></li>
		    <li class="breadcrumb-item ">{{__($company->name)}}</li>
		    <li class="breadcrumb-item ">{{__('Departments')}}</li>
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
		              <h3 class="panel-title">Departments</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addDepartmentModal">Add Department</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            	<br>
                	
	                  <table id="exampleTablePagination" data-toggle="table" 
	                  data-query-params="queryParams" data-mobile-responsive="true"
	                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
	                    <thead>
	                      <tr>
	                        <th >Name:</th>
	                        <th >Manager:</th>
	                        <th >Action</th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@forelse($departments as $department)
	                    	<tr>
	                    		<td>{{$department->name}}</td>
	                    		<td>
	                    		@if($department->manager)
	                    		{{$department->manager->name}}
	                    		@else
	                    		None Selected
	                    		@endif
	                    		</td>
	                    		<td>
	                    			<div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                    data-toggle="dropdown" aria-expanded="false">
                      Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                      <a class="dropdown-item" id="{{$department->id}}" onclick="prepareEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Department</a>
                       <a class="dropdown-item" id="" href="{{url('joblist/'.$department->id)}}" target="_blank"><i class="fa fa-briefcase" aria-hidden="true"></i>&nbsp;Jobs</a>
                      <a class="dropdown-item" id="{{$department->id}}" onclick="deleteDepartment(this.id);"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Department</a>
                      
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
   @include('settings.companysettings.modals.adddepartment')
	  {{-- edit department modal --}}
	  @include('settings.companysettings.modals.editdepartment')
	  

  <script type="text/javascript">
  	$(function() {
  

  	$(document).on('submit','#addDepartmentForm',function(event){
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
  	$(document).on('submit','#editDepartmentForm',function(event){
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
  	function prepareEditData(department_id){
    $.get('{{ url('/settings/department') }}/'+department_id,function(data){
    	console.log(data);
     $('#editname').val(data.name);
     $('#editid').val(data.id);
     $('#editcompany_id').val(data.company_id);
     $('#edituser').val(data.manager_id);
    });
    $('#editDepartmentModal').modal();
  }
  function deleteDepartment(department_id){
  
  alertify.confirm('Are you sure you want to delete this department ?', function(){ 
  $.get('{{ url('settings/departments/delete') }}/'+department_id,{
    department_id:department_id
  }, 
    function(data, status){
        if(data=="success"){
           toastr.success('Department Deleted Successfully');
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
