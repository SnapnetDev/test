<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Confirmation Settings')}}</li>
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
                        <h3 class="panel-title">Confirmation Requirements</h3>
                        <div class="panel-actions">

                        </div>
                    </div>
                    <form id="ConfirmationPolicyForm" enctype="multipart/form-data">
                        @csrf
                    <div class="panel-body">
                        <div class="form-group">
                            <h4>Approval Workflow</h4>
                            <select class="form-control" name="workflow_id" >
                                @forelse($workflows as $workflow)
                                    <option value="{{$workflow->id}}" {{$workflow_id==$workflow->id?'selected':''}}>{{$workflow->name}}</option>
                                @empty
                                    <option value="0">Please Create a Workflow</option>
                                @endforelse

                            </select>
                        </div>
                        <input type="hidden" name=" type" value="confirmation_policy">
                    </div>
                    <div class="panel-footer">
                        <div class="form-group">
                            <button class="btn btn-info">Save Changes</button>
                        </div>
                    </div>
                    </form>
                </div>

		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Confirmation Requirements</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addConfirmationRequirementModal">Add Confirmation Requirement</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="exampleTablePagination" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th >Name:</th>
		                        <th >Compulsory:</th>
                                  <th >For Approval:</th>
                                  <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
                                @foreach($confirmation_requirements as $confirmation)
                                    <tr>
                                        <td>{{$confirmation->name}}</td>
                                        <td>{{$confirmation->compulsory==1?'Yes':'No'}}</td>
                                        <td>{{$confirmation->is_approval_requirement?'Yes':'No'}}</td>
                                        <td><a class="" title="edit" class="btn btn-icon btn-info" id="{{$confirmation->id}}" onclick="prepareEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$confirmation->id}}" onclick="deletedt(this.id);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </td>
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
{{-- Add IP Modal --}}
	   @include('settings.confirmationsettings.modals.addconfirmationrequirement')
	  {{-- edit IP modal --}}
	   @include('settings.confirmationsettings.modals.editconfirmationrequirement')
<script type="text/javascript">
  $(function() {

      $('#addConfirmationRequirementForm').submit(function(e){
          e.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{url('confirmation')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  toastr["success"]("Changes saved successfully",'Success');
                  $('#addConfirmationRequirementModal').modal('toggle');
                  $( "#ldr" ).load('{{url('confirmation/settings')}}');
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

      $('#editConfirmationRequirementForm').submit(function(e){
          e.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{url('confirmation')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  toastr["success"]("Changes saved successfully",'Success');
                  $('#editConfirmationRequirementModal').modal('toggle');
                  $( "#ldr" ).load('{{url('confirmation/settings')}}');
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

      $('#ConfirmationPolicyForm').submit(function(e){
          e.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{url('confirmation')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  toastr["success"]("Changes saved successfully",'Success');


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
  function prepareEditData(confirmation_requirement_id){
      $.get('{{ url('/confirmation/confirmation_requirement') }}',{confirmation_requirement_id:confirmation_requirement_id},function(data){
          // console.log(data);
          $('#editid').val(data.id);
          $('#editname').val(data.name);
          $('#editcompulsory').val(data.compulsory);
          $('#editis_approval_requirement').val(data.is_approval_requirement);
      });
      $('#editConfirmationRequirementModal').modal();
  }
  function deletedt(confirmation_requirement_id){
      alertify.confirm('Are you sure you want to delete this Confirmation Requirement?', function () {


          $.get('{{ url('confirmation/delete_confirmation_requirement') }}',{confirmation_requirement_id:confirmation_requirement_id},function(data){
              if (data=='success') {
                  toastr["success"]("Confirmation Requirement deleted successfully",'Success');

                  location.reload();
              }else{
                  toastr["error"]("Error deleting Confirmation Requirement",'Success');
              }

          });
      }, function () {
          alertify.error('Confirmation Requirement not deleted');
      });
  }

</script>
