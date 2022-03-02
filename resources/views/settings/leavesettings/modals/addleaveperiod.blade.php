<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addLeavePeriodModal" aria-hidden="true" aria-labelledby="addLeavePeriodModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addLeavePeriodForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add New Leave Period</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                    <div class="form-group">
                      <h4 class="example-title">Grade</h4>
                      <select class="form-control" name="grade_id" >
                        @foreach($grades as $grade)
                        <option value="{{$grade->id}}">{{$grade->level}}</option>
                        @endforeach
                      </select>
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Length (Days)</h4>
                  		<input type="text" name="length" class="form-control">
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