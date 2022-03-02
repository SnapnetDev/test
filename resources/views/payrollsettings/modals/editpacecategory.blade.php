<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editPaceSalaryCategoryModal" aria-hidden="true" aria-labelledby="editPaceSalaryCategoryModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="editPaceSalaryCategoryForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit Project Salary Category</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <div class="form-group">
                      <h4 class="name-title">Name</h4>
                      <input type="text" name="name" id="editname" class="form-control" required="">
                    </div>
                    
                    
                    <div class="form-group">
                      <h4 class="unionized-title">Unionized</h4>
                      <select class="form-control" name="unionized" id="editunionized" required>
                           
                            <option value="1"> Yes </option>
                            <option value="0"> No </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="unionized-title">Uses Timesheet</h4>
                      <select class="form-control" name="timesheet" id="edittimesheet" required>
                           
                            <option value="1"> Yes </option>
                            <option value="0"> No </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="unionized-title">Uses Daily Net</h4>
                      <select class="form-control" name="uses_daily_net" id="edituses_daily_net" required>
                           
                            <option value="1"> Yes </option>
                            <option value="0"> No </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="unionized-title">Calculate tax</h4>
                      <select class="form-control" name="uses_tax" id="edituses_tax" required>
                           
                            <option value="1"> Yes </option>
                            <option value="0"> No </option>
                      </select>
                    </div>
                    <div class="form-group">
                          <h4 class="unionized-title">Uses Direct Tax</h4>
                          <select class="form-control" name="uses_direct_tax" id="edituses_direct_tax" required>
                              <option value="0"> No </option>
                              <option value="1"> Yes </option>

                          </select>
                      </div>
                    <div class="form-group">
                      <h4 class="basic_salary-title">Basic Salary</h4>
                      <input type="text" name="basic_salary" id="editbasic_salary" class="form-control" required="">
                    </div>
                      <div class="form-group">
                          <h4 class="tax_rate-title"> Tax Rate</h4>
                          <input type="text" name="tax_rate" id="edittax_rate" class="form-control" required>
                      </div>
                    <div class="form-group">
                  <h4 class="basic_salary-title">Relief</h4>
                  <input type="text" name="relief" id="editrelief" class="form-control" >
                </div>
                    <input type="hidden" name="id" id="editpcid">
                    <input type="hidden" name="type" value="save_project_salary_category">

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