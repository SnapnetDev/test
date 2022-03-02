<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editWorkExperienceModal" aria-hidden="true" aria-labelledby="editWorkExperienceModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="editWorkExperienceForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Work Experience</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	
                    <div class="form-group">
                      <h4 class="example-title">Organization</h4>
                       <input type="text"  required placeholder="Organization" id="editworganization" name="organization"   class="form-control">
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Position</h4>
                  		 <input type="text"  required placeholder="Position" id="editwposition" name="position"   class="form-control">
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Start Date</h4>
                      <input type="text" class="form-control datepicker" id="editwstart_date" name="start_date">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">End Date</h4>
                      <input type="text" class="form-control" id="editwend_date" name="end_date">
                    </div>
                  	
                  	 <input type="hidden" name="user_id" value="{{$user->id}}">
                     <input type="hidden" name="work_experience_id" id="work_experience_id">
                     <input type="hidden" name="type" value="work_experience">
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                  <div class="form-group"> 
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>