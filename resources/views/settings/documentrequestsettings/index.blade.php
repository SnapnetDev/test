<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Document Request Settings')}}</li>
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
		              <h3 class="panel-title">Document Request Types</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addDocumentRequestTypeModal">Add Document Request Type</button>

              			</div>
		            	</div>
		            <div class="panel-body">

	                  <table id="exampleTablePagination" data-toggle="table"
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
		                    <thead>
		                      <tr>
		                        <th >Name:</th>
		                        <th >Workflow:</th>
                                  <th >Created By:</th>
                                  <th>Action</th>
		                      </tr>
		                    </thead>
		                    <tbody>
                                @foreach($document_request_types as $drt)
                                    <tr>
                                        <td>{{$drt->name}}</td>
                                        <td>{{$drt->workflow?$drt->workflow->name:''}}</td>
                                        <td>{{$drt->user?$drt->user->name:''}}</td>
                                        <td><a class="" title="edit" class="btn btn-icon btn-info" id="{{$drt->id}}" onclick="prepareEditData(this.id);"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$drt->id}}" onclick="deletedt(this.id);"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
	   @include('settings.documentrequestsettings.modals.adddocumentrequesttype')
	  {{-- edit IP modal --}}
	   @include('settings.documentrequestsettings.modals.editdocumentrequesttype')
<script type="text/javascript">
  $(function() {

      $('#addDocumentRequestTypeForm').submit(function(e){
          e.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{url('document_requests')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  toastr["success"]("Changes saved successfully",'Success');
                  $('#addDocumentRequestTypeModal').modal('toggle');
                  $( "#ldr" ).load('{{url('document_requests/settings')}}');
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

      $('#editDocumentRequestTypeForm').submit(function(e){
          e.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{url('document_requests')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  toastr["success"]("Changes saved successfully",'Success');
                  $('#editDocumentRequestTypeModal').modal('toggle');
                  $( "#ldr" ).load('{{url('document_requests/settings')}}');
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
  function prepareEditData(document_type_id){
      $.get('{{ url('/document_requests/document_request_type') }}/'+document_request_type_id,function(data){
          // console.log(data);
          $('#document_request_type_id').val(data.id);
          $('#editname').val(data.name);
          $('#editworkflow_id').val(data.workflow_id);
      });
      $('#editDocumentRequestTypeModal').modal();
  }
  function deletedt(document_request_type_id){
      alertify.confirm('Are you sure you want to delete this Document Request Type?', function () {


          $.get('{{ url('document_requests/delete_document_request_type') }}',{document_request_type_id:document_request_type_id},function(data){
              if (data=='success') {
                  toastr["success"]("Document Request Type deleted successfully",'Success');

                  location.reload();
              }else{
                  toastr["error"]("Error deleting Document Request Type",'Success');
              }

          });
      }, function () {
          alertify.error('Document Request Type not deleted');
      });
  }

</script>
