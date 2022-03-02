<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editDependantModal" aria-hidden="true" aria-labelledby="editDependantModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="editDependantForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Dependant</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                       <input type="text"  required placeholder="Name" name="name" id="editdname"   class="form-control">
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Date of Birth</h4>
                  		 <input type="text"  required placeholder="Date of Birth" name="dob" id="editddob"   class="form-control datepicker">
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Email</h4>
                       <input type="email"   placeholder="Email" name="email" id="editdemail"   class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Phone Number</h4>
                       <input type="text"   placeholder="Phone" name="phone" id="editdphone"  class="form-control">
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Relationship</h4>
                  		<select name="relationship" class="form-control" id="editdrelationship" required>
                        <option value="father">Father</option>
                        <option value="mother">Mother</option>
                        <option value="brother">Brother</option>
                        <option value="sister">Sister</option>
                        <option value="nephew">Nephew</option>
                        <option value="niece">Niece</option>
                        <option value="uncle">Uncle</option>
                        <option value="aunt">Aunt</option>
                        <option value="son">Son</option>
                        <option value="daughter">Daughter</option> 
                      </select> 
                  	</div>
                  	 <input type="hidden" name="user_id" value="{{$user->id}}">
                     <input type="hidden" name="type" value="dependant">
                      <input type="hidden" name="dependant_id" id="dependant_id">
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