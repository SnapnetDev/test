<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editTmsaScheduleModal" aria-hidden="true" aria-labelledby="editTmsaScheduleModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="editTmsaScheduleForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit TMSA Schedule Component</h4>
          </div>
            <div class="modal-body">         
                
                    @csrf
                    <div class="form-group">
                      

                        <div class="col-md-12">
                        <h4 class="example-title">Employee</h4>
                        <input type="text" id="editusername" class="form-control">

                    </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <h4 class="example-title">Day Rate Onshore</h4>
                        <input type="text" name="day_rate_onshore" id="editday_rate_onshore" class="form-control">
                      </div>
                      <div class="col-md-6">
                        <h4 class="example-title">Day Worked Onshore</h4>
                        <input type="text" name="days_worked_onshore" id="editdays_worked_onshore" class="form-control">
                        </div>
                      
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <h4 class="example-title">Day Rate Offshore</h4>
                        <input type="text" name="day_rate_offshore" id="editday_rate_offshore" class="form-control">
                      </div>
                      

                        <div class="col-md-6">
                        <h4 class="example-title">Day Worked Offshore</h4>
                        <input type="text" name="days_worked_offshore" id="editdays_worked_offshore" class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <h4 class="example-title">Paid Day Time Rate</h4>
                        <input type="text" name="paid_off_time_rate" id="editpaid_off_time_rate" class="form-control">
                        </div>

                        <div class="col-md-6">
                        <h4 class="example-title">Paid Off Day</h4>
                        <input type="text" name="paid_off_day" id="editpaid_off_day" class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                      <h4 class="example-title">BRT Days</h4>
                      <input type="text" name="brt_days" id="editbrt_days"  class="form-control">
                        </div>

                        <div class="col-md-6">
                      <h4 class="example-title">Living Allowance</h4>
                      <input type="text" name="living_allowance" id="editliving_allowance"  class="form-control">
                    </div>
                    </div>



                    <div class="form-group">
                      <div class="col-md-6">
                      <h4 class="example-title">Transport Allowance</h4>
                      <input type="text" name="transport_allowance" id="edittransport_allowance"  class="form-control">
                    </div>

                    
                    <div class="col-md-6">
                      <h4 class="example-title">Days Out Of Station</h4>
                      <input type="text" name="days_out_of_station" id="editdays_out_of_station"  class="form-control">
                    </div>
                  </div>

                    
                    <input type="hidden" name="tmsa_schedule_id" id="edittsid">
                    <input type="hidden" name="type" value="save_tmsa_schedule">
                          
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