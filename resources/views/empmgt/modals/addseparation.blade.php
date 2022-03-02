<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addSeparationModal" aria-hidden="true" aria-labelledby="addSeparationModal" role="dialog" >
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addSeparationForm"  method="POST" enctype="multipart/form-data">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add New Separation</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                      <h4 class="example-title">Date of Separation</h4>
                       <input type="text"  required placeholder="Date of Separation" name="dos"   class="form-control datepicker">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Separation Type</h4>
                        <select class="form-control separations" id="separation_type" style="width:100%;" name="separation_type"  required></select>
                    </div>

                    <div class="form-group">
                      <h4 class="example-title">Comment</h4>
                      <textarea placeholder="Comment" name="comment"   class="form-control"></textarea>
                       
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Exit Interview Form</h4>
                      <input type="file" name="exit_interview_form" class="form-control" required>
                       
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Exit Checkout Form</h4>
                      <input type="file" name="exit_checkout_form" class="form-control" required>
                       
                    </div>
                  	
                  	 <input type="hidden" name="user_id" value="{{$user->id}}" id="">
                     <input type="hidden" name="type" value="save_separation">
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