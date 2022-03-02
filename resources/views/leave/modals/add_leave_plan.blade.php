<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addLeavePlanModal" aria-hidden="true" aria-labelledby="addLeavePlanModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">

          <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Add Leave Plan</h4>
          </div>
          <form class="form-horizontal" id="addLeavePlanForm"  method="POST">
            <div class="modal-body">

                    @csrf
                    
                    
                   
                    
                    
                    <ul id="" style="border: #ddd 1px solid; padding-inline-start: 0px;padding-top: 10px;">
                        <label for="" style="padding-left: .9375rem;">Leave Periods</label>

                   <li style="list-style:none;padding-left: .9375rem;" class="datepickerDiv" >
                    <div class="form-cont row" >
                      <div class="col-md-9">
                        <div class="form-group">
                          <label for="">Period</label>

                          <div class="input-group" id="datepicker">
                            <input type="text" class="input-sm form-control period_daterange period_start_date" name="start_date[]" placeholder="From date" id="startdate" value="" required="" autocomplete="off" />
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control period_daterange period_end_date" name="end_date[]" placeholder="To date" id="enddate" value="" required="" autocomplete="off" />
                          </div>
                          </div>
                        </div>
                            <div class="col-md-3"><div class="form-group" style="padding-top: 2rem;"> <button  type="button" class="btn btn-primary remComponent" id="remComponent">Remove Period</button> </div></div></div
                            ><hr>
                      </li>
                      <div id="plancont"></div>
                    </ul>

                    <button type="button" id="addComponent" name="button" class="btn btn-primary">Add Period</button>
                    
                  




                    <input type="hidden" name="type" value="save_leave_plans">

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
             </form>
         </div>

      </div>
    </div>
