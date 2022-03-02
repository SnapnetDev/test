<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addDepartmentModal" aria-hidden="true" aria-labelledby="addDepartmentModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addDepartmentForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add New Job</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                  		<h4 class="example-title">Title</h4>
                  		<input type="text" name="title" class="form-control">
                  	</div>
                  	<div class="form-group">
                      <h4 class="example-title">Personnel</h4>
                      <input type="number" name="personnel" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Description</h4>
                     <textarea name="description"></textarea>
                    </div>
                    <div class="form-group">
                       <h4 class="example-title">Users</h4>
                      
                      <select required="" name="users[]" style="width:100%;" id="users" class="form-control " multiple><option selected value='0'>Users</option></select>
                    </div>
                    <div class="form-group">
                    	<h4 class="example-title">Head of Department</h4>
                    	<select class="form-control" name="manager_id" >
                        <option value="0">None</option>
                    		@foreach($users as $user)
                    		<option value="{{$user->id}}">{{$user->name}}</option>
                    		@endforeach
                    	</select>
                    </div> 
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              	<input type="hidden" name="company_id" id="company_id" value="{{$company->id}}">
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