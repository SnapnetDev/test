<div class="modal fade in modal-3d-flip-horizontal modal-info" id="modifyTimesheetDetailModal" aria-hidden="true" aria-labelledby="addHolidayModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="modifyTimesheetDetailForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Timesheet detail for</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                      <div class="form-group">
                        <label for="">Project(Fund code)</label>
                        <select name="project_id" id="detail_project_id" class="form-control" id="">
                          @foreach($projects as $project)
                            <option value="{{$project->id}}">{{$project->name}}({{$project->fund_code}})</option>
                            @endforeach
                        </select>
                          </div>
                          <div class="form-group">
                            <label for="">LIN code</label>
                              <input type="text" class=" form-control" name="lin_code" id="detail_lin_code" placeholder="Lin Code"  value="" required=""  />
                            </div>
                            <div class="form-group">
                                <label for="">Hours</label>
                                  <input type="text" class=" form-control" id="detail_hour" name="hour" placeholder="Hours"  value="" required="" autocomplete="off" />
                                </div>
                    
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              	
                  <div class="form-group">
                    <input type="hidden" name="type" value="timesheet_detail">
                    <input type="hidden" id="detail_id" name="detail_id" value="">
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                    <button type="button" id="" class="btn btn-danger delete-detail" onclick="deletedetail(this.id)">Delete Entry</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>