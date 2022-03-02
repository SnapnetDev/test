<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Employee Settings')}}</li>
		    <li class="breadcrumb-item ">{{__('Role')}}</li>
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
        		@if($role)
        		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Edit Role - {{$role->name}}</h3>
		              <div class="panel-actions">
              			</div>
	            	</div>
		            <div class="panel-body">
		            	<form id="editRoleForm" method="Post">
		            	<div class="form-group">
		            		<h4 class="example-title">Role Name</h4>
                  		<input type="text" name="name" class="form-control" value="{{$role->name}}">
		            	</div>
		            	<div class="form-group">
		            		<h4 class="example-title">Manages</h4>
		            		<select name="manages" class="form-control manages">
		            			<option value="none" {{$role->manages=='none'?'selected':''}}>None</option>
		            			<option value="dr" {{$role->manages=='dr'?'selected':''}}>Direct Reports</option>
								<option value="ss" {{$role->manages=='ss'?'selected':''}}>Supervisor of Supervisor</option>
		            			<option value="all" {{$role->manages=='all'?'selected':''}}>All</option>
		            		</select>
		            	</div>

	                  <div class="nav-tabs-vertical {{$role->manages=='none'?'hide':'block'}}" data-plugin="tabs">
		                  <ul class="nav nav-tabs m-r-25" role="tablist">
		                  	@php $i=0; @endphp
		                  	@foreach($permissioncategories as $category)
		                    <li class="nav-item" role="presentation"><a class="nav-link {{$i==0?'active':''}} " data-toggle="tab" href="#tab_{{$category->id}}" aria-controls="tab_{{$category->id}}" role="tab" aria-expanded="false">{{$category->name}}</a></li>
		                    @php $i++; @endphp
		                    @endforeach
		                  </ul>
		                  <div class="tab-content p-y-15">
		                  	@php $i=0; @endphp
		                  	@foreach($permissioncategories as $category)
		                    <div class="tab-pane {{$i==0?'active':''}} " id="tab_{{$category->id}}" role="tabpanel" aria-expanded="false">
		                     <div class="row">

		                     	@forelse ($category->permissions as $permission)
		                     	<div class="col-md-4">
					                  <div class="pull-xs-left m-r-20">
					                    <input type="checkbox" id="check_{{$permission->id}}" name="permission_list[]" value="{{$permission->id}}"  class="switch" {{ $role->permissions->contains('id',$permission->id)?'checked':'' }} />
					                  </div>
					                  <label class="p-t-3" for="check_{{$permission->id}}">{{$permission->name}}</label>
		                     		</div>
		         				@empty
		                     	@endforelse


		                     </div>
		                    </div>
		                    @php $i++; @endphp
		                     @endforeach

		                  </div>
		                  <input type="hidden" name="role_id" value="{{$role->id}}">
		              </div>
	                	@csrf
		            		<button type="submit" class="btn btn-info pull-right btn-lg">Save</button>
						</form>
	                	</div>


	          		</div>
		          </div>
		          @else
		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Add Role</h3>
		              <div class="panel-actions">
              			</div>
	            	</div>
		            <div class="panel-body">
            		<form id="addRoleForm" method="post">
		            <div class="form-group">
		            		<h4 class="example-title">Role Name</h4>
                  		<input type="text" name="name" class="form-control" value="">
		            	</div>
		            	<div class="form-group">
		            		<h4 class="example-title">Manages</h4>
		            		<select name="manages" class="form-control manages">
		            			<option value="none">None</option>
		            			<option value="dr">Direct Reports</option>
								<option value="ss" >Supervisor of Supervisor</option>
		            			<option value="all">All</option>
		            		</select>
		            	</div>
	                  <div class="nav-tabs-vertical hide" data-plugin="tabs" >
		                  <ul class="nav nav-tabs m-r-25" role="tablist">
		                  	@php $i=0; @endphp
		                  	@foreach($permissioncategories as $category)
		                    <li class="nav-item " role="presentation"><a class="nav-link  {{$i==0?'active':''}}" data-toggle="tab" href="#tab_{{$category->id}}" aria-controls="tab_{{$category->id}}" role="tab" aria-expanded="false">{{$category->name}}</a></li>
		                    @php $i++; @endphp
		                    @endforeach
		                  </ul>
		                  <div class="tab-content p-y-15">
		                  	@php $i=0; @endphp
		                  	@foreach($permissioncategories as $category)
		                    <div class="tab-pane {{$i==0?'active':''}}" id="tab_{{$category->id}}" role="tabpanel" aria-expanded="false">
		                     <div class="row">
		                     	@forelse ($category->permissions as $permission)
		                     		<div class="col-md-4">
					                  <div class="pull-xs-left m-r-20">
					                    <input type="checkbox" id="check_{{$permission->id}}" name="permission_list[]" value="{{$permission->id}}" class="switch"   />
					                  </div>
					                  <label class="p-t-3" for="check_{{$permission->id}}">{{$permission->name}}</label>
		                     		</div>

		         				@empty
		                     	@endforelse
		                     </div>
		                    </div>
		                    @php $i++; @endphp
		                     @endforeach

		                  </div>
	                	</div>
	                	@csrf
	                	<button type="submit" class="btn btn-info pull-right btn-lg">Save</button>
						</form>
	          		</div>
		          </div>
		          @endif
	        	</div>
	    	</div>

		</div>
	  </div>
	  <script type="text/javascript">
  $(function() {
    $('.switch').bootstrapToggle({
      on: 'Yes',
      off: 'No',
      onstyle:'info',
      offstyle:'default'
    });

});

  $(function() {
  	$('#addRoleForm').submit(function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('roles.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Role Created successfully",'Success');

					$( "#ldr" ).load('{{route('employeesettings')}}');

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
  	$(function() {
  	$('#editRoleForm').submit(function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('roles.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Role Changes saved successfully",'Success');


					$( "#ldr" ).load('{{route('employeesettings')}}');
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
  	$('.manages').change(function(event){
  		state=$(this).val();
  		if (state=='none') {
  			$('div.nav-tabs-vertical').addClass('hide');
  			$('div.nav-tabs-vertical').removeClass('block');
  		}else{
  			$('div.nav-tabs-vertical').removeClass('hide');
  			$('div.nav-tabs-vertical').addClass('block');

  		}

  	});
  	});
  	function deleteRole(role_id){
  		$.ajax({
    type: 'POST',
    dataType: 'json',
    data: {
        id: id,
        _method: 'DELETE'
    },
    url: "{{ url('/roles/delete') }}/'+role_id",
    success: function (data) {
        if (data=='success') {
 		toastr.success("Role deleted successfully",'Success');
 		$( "#ldr" ).load('{{route('employeesettings')}}');
    	}else{
    		toastr.error("Error deleting grade",'Error');
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
