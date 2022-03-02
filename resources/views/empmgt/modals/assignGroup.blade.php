<div class="modal fade in modal-3d-flip-horizontal modal-info" id="assignGroupModal" aria-hidden="true" aria-labelledby="assignManagerModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="assignGroupForm" >
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add User to Group</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                  		<h4 class="example-title">User Groups</h4>
                  		<select id="groups" class="form-control">
                         @forelse($user_groups as $group)
                                    <option value="{{$group->id}}" {{ request()->group==$group->id?'selected':'' }}>{{$group->name}}</option>
                                    @empty
                                    <option value="0">Please Create User Groups</option>
                                    @endforelse 
                      </select>
                  	</div>
                  	
                     
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              	
                  <div class="form-group">
                    
                    <button type="button" class="btn btn-info pull-left" id="assignGroup">Save</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>