<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addLeaveModal" aria-hidden="true" aria-labelledby="addLeaveModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addLeaveForm"  method="POST">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add Leave</h4>
	        </div>
            <div class="modal-body">
                <div class="row row-lg col-xs-12">
                  <div class="col-xs-12">
                  	@csrf
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" name="name" class="form-control" >
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Length</h4>
                      <input type="text" name="length" class="form-control" >
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">With / Without Pay</h4>
                      <select class="form-control" name="with_pay" >

                        <option value="1">With Pay</option>
                        <option value="0">Without Pay</option>
                      </select>
                    </div>
                      <div class="form-group">
                          <h4 class="example-title">Gender</h4>
                          <select class="form-control" name="gender" >
                              <option value="all">All</option>
                              <option value="M" >Male</option>
                              <option value="F" >Female</option>
                          </select>
                      </div>
                      <div class="form-group">
                          <h4 class="example-title">Marital Status</h4>
                          <select class="form-control" name="marital_status" >
                              <option value="All">All</option>
                              <option value="Single" >Single</option>
                              <option value="Married" >Married</option>
                              <option value="Divorced" >Divorced</option>
                              <option value="Separated" >Separated</option>
                          </select>
                      </div>


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
