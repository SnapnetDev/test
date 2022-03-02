<div class="modal fade in modal-3d-flip-horizontal modal-info" id="uploadProjectSalaryCategoryTimesheetModal" aria-hidden="true" aria-labelledby="uploadProjectSalaryCategoryTimesheetModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Upload Project Salary Category Timesheets</h4>
	        </div>
          
            <div class="modal-body"> 
            <form class="form-horizontal" id="uploadProjectSalaryCategoryTimesheetForm"  method="POST" enctype="multipart/form-data">
             
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Upload Excel Sheet</h4>
                      <a href="{{url('/payrollsettings/download_timesheet_upload_template?salary_category_id=')}}{{$salary_category->id}}"> Download upload excel template here</a>
                      <input type="file" name="timesheet_template" class="form-control">
                      <input type="hidden" name="month" value='{{date('m-Y',strtotime($date))}}'>
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