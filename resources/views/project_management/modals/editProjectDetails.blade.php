<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editProjectDetailsModal" aria-hidden="true" aria-labelledby="editProjectDetailsModal" role="dialog" >
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Project Details</h4>
	        </div>
            <div class="modal-body"> 
            	<form class="form-horizontal" id="editProjectDetailsForm"  method="POST">
            		@csrf
			   <div class="form-group">
		          <h4 class="example-title">Name</h4>
		          <input type="text" name="name" id="editpname" class="form-control">
		        </div>
		        <div class="form-group">
		          <h4 class="example-title">Code</h4>
		          <input type="text" name="code" id="editpcode" class="form-control">
		        </div>
		        <div class="form-group">
		          <h4 class="example-title">Client</h4>
		          <select name="project_manager_id" id="editpm" style="width:100%;" class="form-control" >
                       @foreach ($clients as $client)
                        <option value="{{$client->id}}" {{$client->id==$project->client->id?'selected':''}}>{{$client->name}}</option>
                       @endforeach
                      </select>
		        </div>
		        <div class="form-group">
		          <h4 class="example-title">Project Manager</h4>
		          <select name="project_manager_id" id="editpm" style="width:100%;" class="form-control" >
                        <option></option>
                      </select>
		        </div>
		        <div class="form-group">
		          <h4 class="example-title">Start Date</h4>
		          <input type="text" name="start_date" id="editpstart_date" class="form-control">
		        </div>
		        <div class="form-group">
		          <h4 class="example-title">Estimated End Date</h4>
		          <input type="text" name="end_est_date" id="editpend_est_date" class="form-control">
		        </div>
		        <div class="form-group">
		          <h4 class="example-title">Actual Estimated Date</h4>
		          <input type="text" name="actual_ending_date" id="editpactual_ending_date" class="form-control">
		        </div>
		        <input type="hidden" id="editpprojectid" name="project_id" value="{{$project->id}}">
		        <input type="hidden"  name="type" value="project_member">
	        	<button type="submit" class="btn btn-info pull-left">Save</button>
        	</form> 
              </div>
              <br>      
            </div>
            
	       </div>
	      
	    </div>
	