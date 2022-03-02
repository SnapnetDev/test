<div class="modal fade in modal-3d-flip-horizontal modal-info" id="recallLeaveRequestModal" aria-hidden="true" aria-labelledby="recallLeaveRequestModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="recallLeaveRequestForm" enctype="multipart/form-data">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Recall Leave Request</h4>
	        </div>
            <div class="modal-body">

            @csrf

          <div class="form-group">
            <label for="">New Resumption Date</label>
              <input data-start_date="0d"  type="text" class="input-sm form-control date-picker" name="end_date" placeholder="New End date" id="end_date" value="" required />
          </div>

          <div class="form-group">
            <label for="">Reason</label>
            <textarea class="form-control" id="reason" name="recall_reason" style="height: 100px;resize: none;" placeholder="Comment" ></textarea>
          </div>
          <input type="hidden" name="type" value="save_recall_leave">
          <input type="hidden" name="leave_request_id" id="recall_id" >

            </div>
            <div class="modal-footer">
              <div class="col-xs-12">

                  <div class="form-group">

                    <button type="submit" class="btn btn-info pull-left">Recall</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>
