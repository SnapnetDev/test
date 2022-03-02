<div class="modal fade in modal-3d-flip-horizontal modal-info " id="addProjectDetailsModal" aria-hidden="true" aria-labelledby="addProjectDetailsModal" role="dialog" >
	    <div class="modal-dialog modal-lg ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add Project Details</h4>
	        </div>
			<form class="form-horizontal" id="addProjectDetailsForm"  method="POST">
            <div class="modal-body"> 
            	
            		@csrf
					<div class="row">
					<div class="col-md-6"><div class="form-group">
		          <h4 class="example-title">Name</h4>
		          <input type="text" name="name" id="pname" class="form-control">
		        </div></div>
			   
				<div class="col-md-6"><div class="form-group">
		          <h4 class="example-title">Fund Code</h4>
		          <input type="text" name="fund_code" id="pfundcode" class="form-control">
		        </div></div>
			</div>
				<div class="row">
				<div class="col-md-6"><div class="form-group">
					<h4 class="example-title">ClientName</h4>
					<input type="text" name="client_name" id="pclientname" class="form-control">
				  </div></div>
                
		        <div class="col-md-6"><div class="form-group">
		          <h4 class="example-title">Project Manager</h4>
		          <select name="project_manager_id" id="editpm" style="width:100%;" class="form-control" >
                        @foreach($users as $user)
						<option value="{{$user->id}}">{{$user->name}}</option>
						@endforeach
                      </select>
		        </div></div>
			</div>
		        <div class="row">
				<div class="col-md-6"><div class="form-group">
		          <h4 class="example-title">Start Date</h4>
		          <input type="text" name="start_date" id="editpstart_date" class="form-control datepicker">
		        </div></div>
		        
				<div class="col-md-6"><div class="form-group">
		          <h4 class="example-title">Estimated End Date</h4>
		          <input type="text" name="end_est_date" id="pend_est_date" class="form-control datepicker">
		        </div></div>
			</div>
				<div class="row">
				<div class="col-md-12"><div class="form-group">
					<h4 class="example-title">Description</h4>
					
					<textarea name="description" id="pdescription" cols="30" rows="10" class="form-control"></textarea>
				  </div></div>
				</div>
              </div>
			  <div class="modal-footer">
				  <input type="hidden" name="type" value="project">
				<button type="submit" class="btn btn-info pull-left ">Save</button>
			  </div>
			</form> 
			
			</div>     
            </div>
            
	       </div>
	      
	    
	