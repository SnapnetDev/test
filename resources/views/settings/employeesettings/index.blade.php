	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Employee Settings')}}</li>
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
        		{{-- roles table --}}
		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Roles</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" onclick="addRole();">Add Role</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="rolestable" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped datatable" >
		                    <thead>
		                      <tr>
		                        <th style="width: 80%">Role:</th>
		                        <th style="width: 20%">Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($roles as $role)
		                    	<tr>
		                    		<td>{{$role->name}}</td>
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$role->id}}" onclick="editRole(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Role</a>
				                       <a class="dropdown-item" id="{{$role->id}}" onclick="deleteRole(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Delete Role</a>

				                    </div>
				                  </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse

		                    </tbody>
	                  </table>
	          		</div>

		          </div>
		          {{-- end of roles table --}}
        		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Grades</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addGradeModal">Add Grade</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="exampleTablePagination" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped datatable">
		                    <thead>
		                      <tr>
		                        <th style="width: 20%">Level:</th>
		                        <th style="width: 20%">Grade Category</th>
		                        <th style="width: 20%">Monthly Gross:</th>
		                        <th style="width: 20%">Leave Length:</th>
								<th style="width: 20%">Description:</th>
		                        <th style="width: 20%">Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($grades as $grade)
		                    	<tr>
		                    		<td>{{$grade->level}}</td>
		                    		<td>{{$grade->grade_category?$grade->grade_category->name:''}}</td>
		                    		<td>{{$grade->basic_pay}}</td>
		                    		<td>{{$grade->leave_length}}</td>
									<td>{{$grade->description}}</td>
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$grade->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Grade</a>
				                       <a class="dropdown-item" id="{{$grade->id}}" onclick="deleteGrade(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Delete Grade</a>

				                    </div>
				                  </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse

		                    </tbody>
	                  </table>
	          		</div>

		          </div>
		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Grade Nomenclatue</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addGradeCategoryModal">Add Grade Nomenclature</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="exampleTablePagination" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped datatable">
		                    <thead>
		                      <tr>
		                        <th style="width: 80%">Name:</th>
		                        <th style="width: 20%">Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($grade_categories as $grade_category)
		                    	<tr>
		                    		<td>{{$grade_category->name}}</td>
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$grade_category->id}}" onclick="prepareEditGCData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Grade Nomeclature</a>
				                       <a class="dropdown-item" id="{{$grade_category->id}}" onclick="deleteGradeCategory(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Delete Grade Nomenclature</a>

				                    </div>
				                  </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse

		                    </tbody>
	                  </table>
	          		</div>

		          </div>

		          {{-- qualification table --}}
		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Qualifications</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addQualificationModal">Add Qualification</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="exampleTablePagination" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th style="width: 80%">Name:</th>
		                        <th style="width: 20%">Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse($qualifications as $qualification)
		                    	<tr>
		                    		<td>{{$qualification->name}}</td>
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$qualification->id}}" onclick="prepareEditQData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Qualification</a>
				                       <a class="dropdown-item" id="{{$qualification->id}}" onclick="deleteQualification(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Delete Qualification</a>

				                    </div>
				                  </div></td>
		                    	</tr>
		                    	@empty
		                    	@endforelse

		                    </tbody>
	                  </table>
	          		</div>

		          </div>
		          {{-- end of qualifiaction table --}}

{{--                Employee Probation Policy Setting--}}
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Probation Policy</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tr>
                                <td>Probation Period</td>
                                <td>
                                    <select class="form-control" id="probation_period">
                                        <option value="">-Select Probation Period-</option>
                                        <option value="3" {{@$probation_policies->probation_period=='3' ? 'selected' :''}} > 3 months</option>
                                        <option value="6" {{@$probation_policies->probation_period=='6' ? 'selected' :''}}> 6 months</option>
                                        <option value="9" {{@$probation_policies->probation_period=='9' ? 'selected' :''}}> 9 months</option>
                                        <option value="12" {{@$probation_policies->probation_period=='12' ? 'selected' :''}}> 12 months</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Notify Role</td>
                                <td>

                                    @php $notify_roles=explode(',',@$probation_policies->notify_roles); @endphp

                                    <select class="form-control" id="notifyrole" multiple >
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{in_array($role->id,$notify_roles) ? 'selected' :''}}>{{$role->name}}  </option>
                                            @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Probation Reminder</td>
                                <td><input type="checkbox" value="yes" {{@$probation_policies->probation_reminder=='yes' ? 'checked' :''}} id="probation_reminder"></td>
                            </tr>
                            <tr>
                                <td>Automatic Probation Status Change</td>
                                <td><input type="checkbox" value="yes" {{@$probation_policies->automatic_probation=='yes' ? 'checked' :''}} id="automatic_probation"></td>
                            </tr>

                            <tr>

                                <td colspan="2">
                                    <button class="btn  btn-info pull-right" id="save_probation_settings">Save Probation Settings</button>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>

	        	</div>
	    	</div>


		</div>




	  {{-- Add Grade Modal --}}
	   @include('settings.employeesettings.modals.addgrade')
	  {{-- edit grade modal --}}
	   @include('settings.employeesettings.modals.editgrade')
	    {{-- Add Grade Modal --}}
	   @include('settings.employeesettings.modals.addgradecategory')
	  {{-- edit grade modal --}}
	   @include('settings.employeesettings.modals.editgradecategory')
	   {{-- Add Qualification Modal --}}
	   @include('settings.employeesettings.modals.addqualification')
	  {{-- edit Qualification modal --}}
	   @include('settings.employeesettings.modals.editqualification')
	  <!-- End Page -->
	    <script type="text/javascript">
  	$(function() {
  $('.datatable').DataTable();

        $('#probation_reminder, #automatic_probation').bootstrapToggle({  on: 'Enable',    off: 'Disable',   onstyle:'info',   offstyle:'default'     });
        $('#notifyrole').select2({tag:true});
        $('#save_probation_settings').click(function () {
            probation_period=$('#probation_period').val();
            notify_roles=$('#notifyrole').val();
            if(notify_roles==''){
                return toastr.error('Please select roles to notify');
            }
            probation_reminder=$('#probation_reminder').is(':checked') ? 'yes' : 'no';
            automatic_probation=$('#automatic_probation').is(':checked') ? 'yes' : 'no';
            formData={
                probation_period:probation_period,
                probation_reminder:probation_reminder,
                automatic_probation:automatic_probation,
                notify_roles:notify_roles,
                _token:'{{csrf_token()}}',
                type:'saveProbationPolicy'
            }

            postData(formData,'{{url('probation')}}');
        })
  	$('#addGradeForm').submit(function(event){

		console.log(1);
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('grades.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){
		        	console.log(2);
		            toastr.success("Changes saved successfully",'Success');
		           $('#addGradeModal').modal('toggle');
				// 	$( "#ldr" ).load('{{route('employeesettings')}}');
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
      	return event.preventDefault();
		});

  	$('#addGradeCategoryForm').submit(function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('grade_categories.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           $('#addGradeCategoryModal').modal('toggle');
				// 	$( "#ldr" ).load('{{route('employeesettings')}}');
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

  		$('#editGradeCategoryForm').submit(function(event){

		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('grade_categories.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#editGradeCategoryModal').modal('toggle');
				// 	$( "#ldr" ).load('{{route('employeesettings')}}');
				location.reload();
		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr["error"](valchild[0]);
							});
							});
		        }
		    });
        return event.preventDefault();
		});

  });
  	$(function() {
  	$('#editGradeForm').submit(function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('grades.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#editGradeModal').modal('toggle');
				// 	$( "#ldr" ).load('{{route('employeesettings')}}');
				location.reload();
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



$(function() {
  	$(document).on('submit','#addQualificationForm',function(event){

		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('qualifications.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		           $('#addQualificationModal').modal('toggle');
				// 	$( "#ldr" ).load('{{route('employeesettings')}}');
				location.reload();

		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr["error"](valchild[0]);
							});
							});
		        }
		    });
        return event.preventDefault();
		});
  });
  	$(function() {
  	$(document).on('submit','#editQualificationForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('qualifications.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#editQualificationModal').modal('toggle');
				// 	$( "#ldr" ).load('{{route('employeesettings')}}');
				location.reload();
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

  	function addRole() {
  		$( "#ldr" ).load('{{route('roles.create')}}');
  	}
  	function editRole(role_id) {
  		$( "#ldr" ).load('{{url('roles')}}'+'/'+role_id+'/edit');
  	}
  	function prepareEditData(grade_id){
    $.get('{{ url('/settings/grades') }}/'+grade_id,function(data){
    	console.log(data);
     $('#editlevel').val(data.level);
     $('#editgc').val(data.grade_category_id);
     $('#editbasic_pay').val(data.basic_pay);
     $('#editleave_length').val(data.leave_length);
     $('#editid').val(data.id);
    });
    $('#editGradeModal').modal();
  }
  function deleteGrade(grade_id){
    $.get('{{ url('/settings/grades/delete') }}/'+grade_id,function(data){
    	if (data=='success') {
 		toastr["success"]("Grade deleted successfully",'Success');
 		$( "#ldr" ).load('{{route('employeesettings')}}');
    	}else{
    		toastr["error"]("Error deleting grade",'Success');
    	}

    });
  }

  function prepareEditGCData(grade_category_id){
    $.get('{{ url('/settings/grade_categories') }}/'+grade_category_id,function(data){
    	console.log(data);
     $('#editgcname').val(data.name);
     $('#editgcid').val(data.id);
    });
    $('#editGradeCategoryModal').modal();
  }
  function deleteGradeCategory(grade_category_id){
    $.get('{{ url('/settings/grade_categories/delete') }}/'+grade_category_id,function(data){
    	if (data=='success') {
 		toastr["success"]("Grade Nomenclature deleted successfully",'Success');
 		$( "#ldr" ).load('{{route('employeesettings')}}');
    	}else{
    		toastr["error"]("Error deleting grade Nomenclature",'Success');
    	}

    });
  }

  function prepareEditQData(qualification_id){
    $.get('{{ url('/settings/qualifications') }}/'+qualification_id,function(data){
    	console.log(data);
     $('#editname').val(data.name);
     $('#editqid').val(data.id);
    });
    $('#editQualificationModal').modal();
  }
  function deleteQualification(qualification_id){
    $.get('{{ url('/settings/qualifications/delete') }}/'+qualification_id,function(data){
    	if (data=='success') {
 		toastr["success"]("Qualification deleted successfully",'Success');
 		$( "#ldr" ).load('{{route('employeesettings')}}');
    	}else{
    		toastr["error"]("Error deleting qualification",'Success');
    	}

    });
  }
  function deleteRole(role_id){
  		$.ajax({
    type: 'POST',
    dataType: 'json',
    data: {
        id: role_id,
        _method: 'DELETE',
        _token:'{{csrf_token()}}'
    },
    url: "{{ url('/roles/delete') }}",
    success: function (data) {
        if (data=='success') {
 		toastr.success("Role deleted successfully",'success');
 		$( "#ldr" ).load('{{route('employeesettings')}}');
    	}else{
    // 		toastr["error"]("Error deleting Role",'error');
    		location.reload();
    	}
    }
	});
   //  $.post('{{ url('/roles/delete') }}/'+role_id,{_method:'delete'},function(data){
   //  	if (data=='success') {
 		// toastr.success("Role deleted successfully",'Success');
 		// $( "#ldr" ).load('{{route('employeesettings')}}');
   //  	}else{
   //  		toastr["error"]("Error deleting grade",'Success');
   //  	}

   //  });
  }


  </script>

