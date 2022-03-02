<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addStaffCategoryModal" aria-hidden="true" aria-labelledby="addStaffCategoryModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addStaffCategoryForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add New Staff Category</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                  		<h4 class="example-title">Name</h4>
                  		<input type="text" name="name" class="form-control">
                  	</div>
                    <div class="form-group " >
                      <label class="form-control-label" for="select">Payroll Type</label>
                      <select class="form-control"  name="payroll_type">
                        <option value="1" >Normal</option>
                        <option value="2" >TMSA</option>
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