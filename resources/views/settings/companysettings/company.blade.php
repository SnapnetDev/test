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
		              <h3 class="panel-title">Companies</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addCompanyModal">Add Company</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            
	                  <table id="exampleTablePagination" data-toggle="table" 
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th >Name:</th>
		                        <th >Email:</th>
		                        <th >Address:</th>
		                        <th >Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($companies as $company)
		                    	<tr>
		                    		<td>{{$company->name}}</td>
		                    		<td>{{$company->email}}</td>
		                    		<td>{{$company->address}}</td>
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$company->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Company</a>
				                      <a class="dropdown-item linker" id="{{$company->id}}" href="{{ route('departments',['company_id'=>$company->id]) }}"><i class="fa fa-puzzle-piece" aria-hidden="true"></i>&nbsp;Departments</a>
				                      <a class="dropdown-item linker" id="{{$company->id}}"  href="{{ route('branches',['company_id'=>$company->id]) }}"><i class="fa fa-arrows" aria-hidden="true"></i>&nbsp;Branches</a>
				                    </div>
				                  </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse
		                    	
		                    </tbody>
	                  </table>
	          		</div>
	          		<div class="panel-footer">
	          			<form>
	          				<div class="form-group">
	          					<h4>Select Parent Company</h4>
	          					<select class="form-control" onchange="changeParentCompany(this.value)">
	          						@forelse($companies as $company)
	          						<option value="{{$company->id}}" {{$company->is_parent==1?'selected':''}}>{{$company->name}}</option>
	          						@empty
	          						<option value="0">Please Create a company</option>
	          						@endforelse
	          						
	          					</select>
	          				</div>
	          				<div class="form-group">
	          					<button class="btn btn-info" >Save Changes</button>
	          				</div>
	          			</form>
	          		</div>
		          </div>
	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>
	  {{-- Add Company Modal --}}
	   @include('settings.companysettings.modals.addcompany')
	  {{-- edit company modal --}}
	   @include('settings.companysettings.modals.editcompany')
	  <!-- End Page -->
	    <script type="text/javascript">
	    	

	    	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {
		    
		    var input = $(this).parents('.input-group').find(':text'),
		        log = label;
		    
		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
	    
		});

		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#img-upload').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
		}

		$("#imgInp").change(function(){
		    readURL(this);
		});

		$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {
		    
		    var input = $(this).parents('.input-group').find(':text'),
		        log = label;
		    
		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
	    
		});

		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#img-uploade').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
		}

		$("#imgInpe").change(function(){
		    readURL(this);
		});


  	$(function() {
  

  	$(document).on('submit','#addCompanyForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('companies.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           $('#addCompanyModal').modal('toggle');
					$( "#ldr" ).load('{{route('companies')}}');

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
  	$(document).on('submit','#editCompanyForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('companies.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#editCompanyModal').modal('toggle');
					$( "#ldr" ).load('{{route('companies')}}');
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
  	function prepareEditData(company_id){
    $.get('{{ url('/settings/companies') }}/'+company_id,function(data){
    	console.log(data);
     $('#editname').val(data.name);
     $('#editid').val(data.id);
     $('#editemail').val(data.email);
     $('#editaddress').val(data.address);
     $('#editbiometric').val(data.biometric_serial);
     $('#edituser').val(data.user_id);
     $('#img-uploade').attr('src',"{{url('')}}/uploads/logo"+data.logo);
    });
    $('#editCompanyModal').modal();
  }
  function changeParentCompany(company_id){
  	event.preventDefault();
    $.get('{{ url('/settings/companies/parent') }}/'+company_id,function(data){
    	
    	if (data=='success') {
    		toastr["success"]("Changes saved successfully",'Success');
    	}else{
    		toastr["error"]("No company was selected",'Error');
    	}
     });
  }
  
  </script>

