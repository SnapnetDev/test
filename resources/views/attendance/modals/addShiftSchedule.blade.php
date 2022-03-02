<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addShiftScheduleModal" aria-hidden="true" aria-labelledby="addShiftScheduleModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="addShiftScheduleForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="schedule_title">Add Shift Schedule</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Start Date</h4>
                      <div class="input-group " >
                        <input type="text"  class="form-control datepicker" name="startdate" id="startdate" required>
                        <span class="input-group-addon">
                          <span class="md-calendar"></span>
                        </span>
                        </div>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">End Date</h4>
                       <div class="input-group " >
                        <input type="text" class=" datepicker form-control" name="enddate" id="enddate" required>
                        <span class="input-group-addon">
                          <span class="md-calendar"></span>
                        </span>
                      </div>
                 
                    </div>
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                
                  <div class="form-group">
                    <input type="hidden" id="editid" name="working_period_id">
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