<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addMemberModal" aria-hidden="true" aria-labelledby="addMemberModal" role="dialog" >
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add New Member</h4>
	        </div>
            <div class="modal-body"> 
            	<form class="form-horizontal" id="addMemberForm"  method="POST">
            		@csrf
		   <div class="form-group">
	          <h4 class="example-title">Member</h4>
	          <select required class="form-control" name="project_members[]" style="width:100%;" id="editmembers" multiple></select>
	        </div>
	        <input type="hidden" id="editmprojectid" name="project_id" value="{{$project->id}}">
	        <input type="hidden"  name="type" value="project_member">
        	<button type="submit" class="btn btn-info pull-left">Save</button>
        	</form> 
              </div>
              <br>      
            </div>
            
	       </div>
	      
	    </div>
	