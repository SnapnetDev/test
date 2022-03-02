<div class="modal fade in modal-3d-flip-horizontal modal-info" id="changePasswordModal" aria-hidden="true" aria-labelledby="changePasswordModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="changePasswordForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" >Change Password</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	
                    <div class="form-group">
                      <h4 class="example-title">Current Password</h4>
                       <input type="password"  required placeholder="Current Password" name="password"   class="form-control">
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">New Password</h4>
                  		 <input type="password"  required placeholder="New Password" name="new_password"   class="form-control ">
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Confirm Password</h4>
                       <input type="password" required  placeholder="Confirm New Password" name="new_password_confirmation"   class="form-control">
                    </div>
                  	
                     <input type="hidden" name="type" value="change_password">
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