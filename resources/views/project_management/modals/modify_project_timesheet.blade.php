<div class="modal fade in modal-3d-flip-horizontal modal-info" id="modifyTimesheetModal" aria-hidden="true" aria-labelledby="addLeavePlanModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">

          <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Modify Project timesheet for <span id="timesheet_title"></span></h4>
          </div>
          <form class="form-horizontal" id="modifyTimesheetForm"  method="POST">
            <div class="modal-body">
                    @csrf
                    <input type="hidden" name="date" id="timesheet_date"  value="">
                    <ul id="" style="border: #ddd 1px solid; padding-inline-start: 0px;padding-top: 10px;">
                        <label for="" style="padding-left: .9375rem;">Projects</label>

                   <li style="list-style:none;padding-left: .9375rem;" class="projectDiv" >
                    <div class="form-cont row" >
                      <div class="col-md-3">
                        
                        <div class="form-group">
                            <label for="">Project(Fund code)</label>
                            <select name="project_id[]" class="form-control" id="">
                              @foreach($projects as $project)
                                <option value="{{$project->id}}">{{$project->name}}({{$project->fund_code}})</option>
                                @endforeach
                            </select>
                              </div>
                      </div>
                              <div class="col-md-3">
                        <div class="form-group">
                          <label for="">LIN code</label>
                            <input type="text" class="input-sm form-control" name="lin_code[]" placeholder="Lin Code"  value="" required=""  />
                          </div>
                          
                        </div>
                    
                        <div class="col-md-3">
                            <div class="form-group">
                              <label for="">Hours</label>
                                <input type="text" class="input-sm form-control" name="hour[]" placeholder="Hours"  value="" required="" autocomplete="off" />
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
                    <input type="hidden" name="type" value="timesheet">
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
