<div class="modal fade in modal-3d-flip-horizontal modal-info" id="assignJobRoleModal" aria-hidden="true" aria-labelledby="assignJobRoleModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="assignJobRoleForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Assign Job Role</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	
                    <div class=" form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="department_id">Department</label>
                                  <select class="form-control" id="department_id" name="department_id"  onchange="departmentChange(this.value);" required>
                                    @forelse($departments as $department)
                                    <option value="{{$department->id}}" >{{$department->name}}</option>
                                    @empty
                                    <option value="0">Please Create a department</option>
                                    @endforelse
                                  </select>
                                </div>
                              </div>
                  	<div class=" form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="jobroles">Job Role</label>
                                  <select class="form-control" id="jobroles" name="job_id" required>
                                    @forelse($jobs as $job)
                                    <option value="{{$job->id}}" >{{$job->title}}</option>
                                    @empty
                                    <option value="0">Please Create a job role in department</option>
                                    @endforelse
                                  </select>
                                </div>
                              </div>
                    <div class="form-group">
                      <h4 class="example-title">Started</h4>
                       <input type="text"  required placeholder="Started" name="started"   class="form-control datepicker">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Ended</h4>
                       <input type="text"   placeholder="Ended" name="ended"   class="form-control datepicker">
                    </div>
                  	 <input type="hidden" name="user_id" value="{{$user->id}}">
                     <input type="hidden" name="type" value="job_history">
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