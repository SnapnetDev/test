<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editLeaveRequestModal" aria-hidden="true" aria-labelledby="editLeaveRequestModal" role="dialog" >
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="editLeaveRequestForm" enctype="multipart/form-data">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Leave Request Interval</h4>
	        </div>
            <div class="modal-body">         
                
            @csrf
            <div class="form-group">
                  <label for="">New Start date</label>
                  
                    <input data-start_date type="text" class="input-sm form-control date-picker" name="start_date" placeholder="From date" id="fromdate" value="" required="" />
                </div>
                </div>

                <input type="hidden" data-id value="" />
       

          <input type="hidden" name="type" value="save_request">
          
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              
                  <div class="form-group">
                    
                <button data-update-leave type="button" class="btn btn-info pull-left">Save Changes</button>
                <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>

                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
      </div>


