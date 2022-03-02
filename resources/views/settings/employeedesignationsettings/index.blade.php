<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Employee Designation Settings')}}</li>
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
		              <h3 class="panel-title">Locations</h3>
		              <div class="panel-actions">
		              	<button class="btn btn-info" data-toggle="modal" data-target="#addLocationModal">Add Location</button>
            			</div>
	            	</div>
		            <div class="panel-body">
		            
	                  <table id="exampleTablePagination" data-toggle="table" 
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th style="width: 40%">Name:</th>
		                        <th style="width: 40%">Address:</th>
		                        <th style="width: 20%">Action:</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($locations as $location)
		                    	<tr>
		                    		<td>{{$location->name}}</td>
		                    		<td>{{$location->address}}</td>
		                    		<td>
		                    			<div class="btn-group" role="group">
		                    			<button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
		                    			<div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$location->id}}" onclick="prepareLocationEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Location</a>
				                      <a class="dropdown-item " id="{{$location->id}}"  onclick="deleteLocation(this.id)"><i class="fa fa-trash" aria-hidden="true" ></i>&nbsp;Delete Location</a>
				                    </div>
				                </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse
		                    	
		                    </tbody>
	                  </table>
	          		</div>
	          		</div>        		
		          {{-- end of location table --}}
	          		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Staff Categories</h3>
		              <div class="panel-actions">
                			
		              	<button class="btn btn-info" data-toggle="modal" data-target="#addStaffCategoryModal">Add Staff Category</button>
              			</div>
		            	</div>
		            <div class="panel-body">
		            
	                  <table id="exampleTablePagination" data-toggle="table" 
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th style="width: 40%">Name:</th>
		                        <th style="width: 40%">Payroll Type:</th>
		                        <th style="width: 20%">Action:</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($staffcategories as $staffcategory)
		                    	<tr>
		                    		<td>{{$staffcategory->name}}</td>
		                    		<td>{{$staffcategory->payroll_type==1?'Normal Payroll':'TMSA Payroll'}}</td>
		                    		<td><div class="btn-group" role="group">
		                    			<button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown2"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
		                    			<div class="dropdown-menu" aria-labelledby="exampleIconDropdown2" role="menu">
				                      <a class="dropdown-item" id="{{$staffcategory->id}}" onclick="prepareStaffCategoryEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Staff Category</a>
				                      <a class="dropdown-item " id="{{$staffcategory->id}}"  onclick="deleteStaffCategory(this.id)"><i class="fa fa-trash" aria-hidden="true" ></i>&nbsp;Delete Staff Category</a>
				                    </div>
				                </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse
		                    	
		                    </tbody>
	                  </table>
	          		</div>
	          		</div>
	          		{{-- end of staff category table --}}
	          		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Positions</h3>
		              <div class="panel-actions">
                			
		              	<button class="btn btn-info" data-toggle="modal" data-target="#addPositionModal">Add Position</button>
              			</div>
		            	</div>
		            <div class="panel-body">
		            
	                  <table id="exampleTablePagination" data-toggle="table" 
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th style="width: 80%">Name:</th>
		                        <th style="width: 20%">Action:</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($positions as $position)
		                    	<tr>
		                    		<td>{{$position->name}}</td>
		                    		<td><div class="btn-group" role="group">
		                    			<button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown3"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
		                    			<div class="dropdown-menu" aria-labelledby="exampleIconDropdown3" role="menu">
				                      <a class="dropdown-item" id="{{$position->id}}" onclick="preparePositionEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Position</a>
				                      <a class="dropdown-item " id="{{$position->id}}"  onclick="deletePosition(this.id)"><i class="fa fa-trash" aria-hidden="true" ></i>&nbsp;Delete Position</a>
				                    </div>
				                </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse
		                    	
		                    </tbody>
	                  </table>
	          		</div>
	          		</div>
	          		{{-- end of Position --}}
	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>

	   {{-- Add Location Modal --}}
	   @include('settings.employeedesignationsettings.modals.addlocation')
	  {{-- edit Location modal --}}
	   @include('settings.employeedesignationsettings.modals.editlocation')
	   {{-- Add Staff Category Modal --}}
	   @include('settings.employeedesignationsettings.modals.addstaffcategory')
	  {{-- edit Staff Category modal --}}
	   @include('settings.employeedesignationsettings.modals.editstaffcategory')
	   {{-- Add Position Modal --}}
	   @include('settings.employeedesignationsettings.modals.addposition')
	  {{-- Edit Position modal --}}
	   @include('settings.employeedesignationsettings.modals.editposition')
<script type="text/javascript">
	 $('.input-daterange').datepicker({
    autoclose: true
});
	 $(document).on('submit','#addLocationForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('locations.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		            $('#addLocationModal').modal('toggle');
					$( "#ldr" ).load('{{route('employeedesignationsettings')}}');
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
	 $(document).on('submit','#editLocationForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('locations.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		            $('#editLocationModal').modal('toggle');
					$( "#ldr" ).load('{{route('employeedesignationsettings')}}');
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
	 $(document).on('submit','#addStaffCategoryForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('staffcategories.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		            $('#editStaffCategoryModal').modal('toggle');
					$( "#ldr" ).load('{{route('employeedesignationsettings')}}');
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
	 $(document).on('submit','#editStaffCategoryForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('staffcategories.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		            $('#editStaffCategoryModal').modal('toggle');
					$( "#ldr" ).load('{{route('employeedesignationsettings')}}');
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
	 $(document).on('submit','#addPositionForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('positions.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		            $('#editPositionModal').modal('toggle');
					$( "#ldr" ).load('{{route('employeedesignationsettings')}}');
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
	 $(document).on('submit','#editPositionForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('positions.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		            $('#editPositionModal').modal('toggle');
					$( "#ldr" ).load('{{route('employeedesignationsettings')}}');
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
  	
  function prepareLocationEditData(location_id){
    $.get('{{ url('/settings/location') }}/'+location_id,function(data){
    	console.log(data);
     $('#editlid').val(data.id);
     $('#editlname').val(data.name);
     $('#editladdress').val(data.address);
    });
    $('#editLocationModal').modal();
  }
  function prepareStaffCategoryEditData(staffcategory_id){
    $.get('{{ url('/settings/staffcategory') }}/'+staffcategory_id,function(data){
    	console.log(data);
     $('#editscid').val(data.id);
     $('#editscname').val(data.name);
     $('#editscpayrolltype').val(data.payroll_type);
    });
    $('#editStaffCategoryModal').modal();
  }
  function preparePositionEditData(position_id){
    $.get('{{ url('/settings/position') }}/'+position_id,function(data){
    	console.log(data);
     $('#editpid').val(data.id);
     $('#editpname').val(data.name);
    });
    $('#editPositionModal').modal();
  }
  function deleteLocation(location_id){
  
  alertify.confirm('Are you sure you want to delete this location ?', function(){ 
  $.get('{{ url('settings/locations/delete') }}/'+location_id,{
    location_id:location_id
  }, 
    function(data, status){
        if(data=="success"){
           toastr.success('Location Deleted Successfully');
           $( "#ldr" ).load('{{route('employeedesignationsettings')}}');
        }else{
        	toastr.error(data);
        }
    });
    });

}
function deleteStaffCategory(staffcategory_id){
  
  alertify.confirm('Are you sure you want to delete this staff Category ?', function(){ 
  $.get('{{ url('settings/staffcategories/delete') }}/'+staffcategory_id,{
    staffcategory_id:staffcategory_id
  }, 
    function(data, status){
        if(data=="success"){
           toastr.success('Staff Category Deleted Successfully');
          $( "#ldr" ).load('{{route('employeedesignationsettings')}}');
        }else{
        	toastr.error(data);
        }
        
    });
    });

}
function deletePosition(position_id){
  
  alertify.confirm('Are you sure you want to delete this position ?', function(){ 
  $.get('{{ url('settings/positions/delete') }}/'+position_id,{
    position_id:position_id
  }, 
    function(data, status){
        if(data=="success"){
           toastr.success('Position Deleted Successfully');
           $( "#ldr" ).load('{{route('employeedesignationsettings')}}');
        }else{
        	toastr.error(data);
        }
    });
    });

}
</script>