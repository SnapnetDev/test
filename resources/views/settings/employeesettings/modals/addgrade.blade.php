<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addGradeModal" aria-hidden="true" aria-labelledby="addGradeModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addGradeForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add New Grade</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                  		<h4 class="example-title">Level</h4>
                  		<input type="text" name="level" class="form-control">
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Grade Category</h4>
                      <select class="form-control" name="grade_category_id" >
                        @foreach($grade_categories as $grade_category)
                        <option value="{{$grade_category->id}}">{{$grade_category->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Monthly Gross Salary</h4>
                      <input type="text" name="basic_pay" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Leave Length</h4>
                      <input type="text" name="leave_length" class="form-control">
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