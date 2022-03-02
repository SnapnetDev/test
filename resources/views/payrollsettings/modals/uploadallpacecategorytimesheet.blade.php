<div class="modal fade in modal-3d-flip-horizontal modal-info" id="uploadPaceSalaryCategoryTimesheetModal" aria-hidden="true" aria-labelledby="uploadPaceSalaryCategoryTimesheetModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Upload Project Salary Category Timesheets</h4>
	        </div>
          
            <div class="modal-body"> 
            <form class="form-horizontal" id="uploadAllProjectSalaryCategoryTimesheetForm"  method="POST" enctype="multipart/form-data">
             
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Upload Excel Sheet</h4>
                      <a href="{{url('/payrollsettings/download_all_timesheet_upload_template')}}"> Download upload excel template here</a>
                      <input type="file" name="timesheet_template" class="form-control">
                      <label>Month</label>
                      <input type="text" name="month" class="form-control monthpicker" autocomplete="off">
                      <input type="hidden" name="type" value="import_project_salary_timesheets">
                    </div>
                    <div class="form-group">
                    <button type="submit" class="btn btn-info pull-left ">Upload</button>
                    </div>
                  <br>
                  <br>
                  
            </form>
           
            </div>
           
             
	       </div>
	      
	    </div>
	  </div>