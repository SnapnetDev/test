<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editSuspensionModal" aria-hidden="true" aria-labelledby="aeditSeparationModal" role="dialog" >
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="editSuspensionForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Suspension</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                      <h4 class="example-title">Effective From</h4>
                       <input type="text"  required placeholder="Date of Suspension" name="dos" id="edit_dos"   class="form-control datepicker">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Suspension Ends</h4>
                       <input type="text"  required placeholder="Suspension Ends" name="suspension_ends" id="edit_suspension_ends"  class="form-control datepicker">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Suspension Type</h4>
                        <select class="form-control suspensions" id="suspension_type" style="width:100%;" id="edit_suspension_type" name="suspension_type"  required=""></select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Comment</h4>
                      <textarea placeholder="Comment" name="comment" id="edit_commment"   class="form-control"></textarea>
                       
                    </div>
                  	
                  	 
                     <input type="hidden" name="type" value="update_suspension">
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