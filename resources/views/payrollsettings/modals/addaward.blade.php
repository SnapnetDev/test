<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addAwardModal" aria-hidden="true" aria-labelledby="addAwardModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">

	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add Award</h4>
	        </div>
          <form class="form-horizontal" id="AddAwardForm"  method="POST">
            <div class="modal-body">

                  	@csrf

                  	<div class="form-group">
                  		<h4 class="example-title">Max Year</h4>
                  		<input type="text" id="max_year" name="max_year" class="form-control">
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Amount</h4>
                      <input type="text" id="amount" name="amount" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Difference</h4>
                      <input type="text" id="difference" name="difference" class="form-control">
                    </div>
                    <input type="hidden" name="type" value="long_service_awards">

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
