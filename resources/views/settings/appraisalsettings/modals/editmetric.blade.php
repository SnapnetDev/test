<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editMetricModal" aria-hidden="true" aria-labelledby="editMetricModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="editMetricForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Metric</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                  		<label class="example-title">Name</label>
                  		<input type="text" name="name" id="editmname" class="form-control">
                  	</div>
                    <div class="form-group">
                      <label class="example-title">Description</label>
                      <textarea class="form-control" id="editmdescription" name="description" rows="3"></textarea>
                    </div> 
                    <div class="form-group">
                      <label class="example-title">Fillable</label>
                    	<select class="form-control" id="editmfillable" name="fillable" >
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    	</select>
                    </div>
                    <div class="form-group">
                      <label class="example-title">Manager Appraises</label>
                      <select class="form-control" id="editmmanager_appraises" name="manager_appraises" >
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="example-title">Employee Appraises</label>
                      <select class="form-control" id="editmemployee_appraises" name="employee_appraises" >
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="example-title">Status</label>
                      <select class="form-control" id="editmactive" name="active" >
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                      </select>
                    </div>
                    
                    
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                  <div class="form-group">
                    {{ csrf_field() }}
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