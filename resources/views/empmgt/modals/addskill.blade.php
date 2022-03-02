<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addSkillModal" aria-hidden="true" aria-labelledby="addSkillModal" role="dialog" >
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addSkillForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add New Skill</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	
                    <div class="form-group">
                      <h4 class="example-title">Skill</h4>
                        <select class="form-control skills" id="skill" style="width:100%;" name="skill" ></select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Competency</h4>
                        <select class="form-control roles" name="competency_id" >
                           @forelse ($competencies as $competency) 
                           <option value="{{$competency->id}}">{{$competency->proficiency}}</option>
                            @empty
                             <option value="">No Competency Created</option>
                             @endforelse
                          </select>
                    </div>
                  	
                  	 <input type="hidden" name="user_id" value="{{$user->id}}">
                     <input type="hidden" name="type" value="skill">
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