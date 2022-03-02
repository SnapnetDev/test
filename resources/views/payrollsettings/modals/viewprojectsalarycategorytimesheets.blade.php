<div class="modal fade in modal-3d-flip-horizontal modal-info" id="viewTimesheetsModal" aria-hidden="true" aria-labelledby="viewTimesheetsModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">View Project Salary Category Timesheet </h4>
	        </div>
          <form class="form-horizontal" >
            <div class="modal-body">         
                
                  	@csrf
                  	<div class="form-group">
                  		<h4 class="example-title"> Month</h4>
                  		<input type="text" name="month" autocomplete="off" id="month" class="form-control monthpicker">
                  	</div>
                    <input type="hidden" name="salary_category" id="timesheet_salary_category_id" value="">
                          
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              	
                  <div class="form-group">
                    
                    <button type="button" class="btn btn-info pull-left" id="viewCategoryTimesheets">View Timesheet</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
             </form>
	       </div>
	      
	    </div>
	  </div>