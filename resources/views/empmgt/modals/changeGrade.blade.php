<div class="modal fade in modal-3d-flip-horizontal modal-info" id="changeGradeModal" aria-hidden="true" aria-labelledby="changeRoleModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="changeGradeForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Change User Grade</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                  		<h4 class="example-title">Grade</h4>
                  		<select id="grade_id" name="role_id" class="form-control">
                        @foreach($grades as $grade)
                      <option value="{{$grade->id}}">{{$grade->level}} ({{$grade->description}})</option>
                        @endforeach 
                      </select>
                  	</div>
                  	<div class="form-group">
                        <h4 class="example-title">Effective Date</h4>
                         <input type="text" id="grade_effective_date"  placeholder="Promotion Effective" name="effective_date"   class="form-control datepicker">
                      </div>
                     
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              
                  <div class="form-group">
                    
                    <button type="button" id="changeGrade" class="btn btn-info pull-left">Save</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>