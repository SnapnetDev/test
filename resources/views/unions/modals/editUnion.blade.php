<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editUnionModal" aria-hidden="true" aria-labelledby="editUnionModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Union</h4>
	        </div>
          <form class="form-horizontal" id="editUnionForm"  method="POST">
            <div class="modal-body">         
                
                  	@csrf
                   
                  	<div class="form-group">
                  		<h4 class="example-title">Name</h4>
                  		<input type="text" name="name"  id="editname" class="form-control">
                  	</div>
                    
                    <div class="form-group">
                      <h4 class="example-title">Formula</h4>
                      <input type="text" name="formula"  id="editformula" class="form-control">
                    </div>
                    
                    <div class="form-group">
                      <label class="example-title" for="constant">Members</label>
                      
                      <select  name="user_id[]" style="width:100%;" id="editmembers" class="form-control" multiple></select>
                    </div>
                    <input type="hidden" name="type" value="save_union">
                    <input type="hidden" name="union_id" id="editunionid">
                          
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
             </form>
	       </div>
	      
	    </div>
	  </div>