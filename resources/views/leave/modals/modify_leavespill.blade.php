<div class="modal fade in modal-3d-flip-horizontal modal-info" id="modifyLeaveSpillModal" aria-hidden="true" aria-labelledby="modifyLeaveSpillModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="modifyLeaveSpillForm" enctype="multipart/form-data">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Modify Leave Spill</h4>
	        </div>
            <div class="modal-body">             	
            
            @csrf
           
          <div class="form-group">
            <label for="">New Days</label>
            <input type="text" name="newdays" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="">Reason for Modification</label>
            <textarea class="form-control" id="comment" name="comment" required style="height: 100px;resize: none;" placeholder="Comment" ></textarea>
          </div>
          <input type="hidden" name="type" value="save_leave_spillover_modification">
          <input type="hidden" name="leavespill_id" id="leavespill_id" >
          
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              
                  <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Save Changes</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>