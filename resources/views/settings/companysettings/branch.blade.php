
<!-- Page -->
 	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item "><a class="linker" href="{{route('companies')}}">{{__('Companies')}}</a></li>
		    <li class="breadcrumb-item ">{{__($company->name)}}</li>
		    <li class="breadcrumb-item ">{{__('Branches')}}</li>
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
		              <h3 class="panel-title">Branches for {{$company->name}}</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addBranchModal">Add Branch</button>

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
	                        <th >Address:</th>
	                        <th >Email:</th>
	                        <th >Action</th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    	@forelse($company->branches as $branch)
	                    	<tr>
	                    		<td>{{$branch->name}}</td>
	                    		<td>{{$branch->manager?$branch->manager->name:''}}</td>
	                    		<td>{{$branch->address}}</td>
	                    		<td>{{$branch->email}}</td>
	                    		<td>
	                    			<div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                    data-toggle="dropdown" aria-expanded="false">
                      Actione
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                      <a class="dropdown-item" id="{{$branch->id}}" onclick="prepareEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Branch</a>
                      <a class="dropdown-item" id="{{$branch->id}}" href="{{url('users')}}?branch_id={{$branch->id}}" target="_blank"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;Users</a>
                      <a class="dropdown-item" id="{{$branch->id}}" onclick="deleteBranch(this.id);"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Branch</a>

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



  <!-- End Page -->
  {{-- add branch modal --}}
   @include('settings.companysettings.modals.addbranch')
	  {{-- edit branch modal --}}
	  @include('settings.companysettings.modals.editbranch')
	  <script type="text/javascript">
  	$(function() {


  	$(document).on('submit','#addBranchForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('branches.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		            $('#addBranchModal').modal('toggle');
					$( "#ldr" ).load('{{route('branches',['company_id'=>$company->id])}}');
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
  	$(document).on('submit','#editBranchForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('branches.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		            $('#editBranchModal').modal('toggle');
					$( "#ldr" ).load('{{route('branches',['company_id'=>$company->id])}}');
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
  	function prepareEditData(branch_id){
    $.get('{{ url('/settings/branch') }}/'+branch_id,function(data){
    	console.log(data);

     $('#editname').val(data.name);
     $('#editemail').val(data.email);
     $('#editaddress').val(data.address);
     $('#editid').val(data.id);
     $('#editcompany_id').val(data.company_id);
     $('#edituser').val(data.manager_id);
    });
    $('#editBranchModal').modal();
  }
  function deleteBranch(branch_id){

  alertify.confirm('Are you sure you want to delete this branch ?', function(){
  $.get('{{ url('settings/branches/delete') }}/'+branch_id,{
    branch_id:branch_id
  },
    function(data, status){
        if(data=="success"){
           toastr.success('Branch Deleted Successfully');
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

