<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addTmsaScheduleModal" aria-hidden="true" aria-labelledby="addTmsaScheduleModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog modal-lg">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add TMSA Schedule</h4>
	        </div>
          
            <div class="modal-body"> 
            <form class="form-horizontal" id="uploadTMSAScheduleForm"  method="POST" enctype="multipart/form-data">
             
                    @csrf

                    <div class="form-group">
                      <h4 class="example-title">Upload Excel Sheet</h4>
                      <a href="{{url('/payrollsettings/download_tmsa_template')}}">Want to add multiple at once? Download excel template here</a>
                      <div class="row">
                       <div class="col-md-6">
                        <h4 class="example-title">Period (Month)</h4>
                        <input type="text" name="for" class="form-control monthpicker" autocomplete="off">
                       
                      <input type="file" name="template" class="form-control">
                      <input type="hidden" name="type" value="import_tmsa_schedule">
                    </div>
                    </div>
                    </div>
                    <div class="form-group">
                    <button type="submit" class="btn btn-info pull-left ">Upload</button>
                    </div>
                  
                  
            </form>
            <br>
            <hr>         
                <form class="form-horizontal" id="addTmsaScheduleForm"  method="POST">
                  	@csrf
                  	<div class="form-group">
                      <div class="col-md-6">
                    		<h4 class="example-title">Period (Month)</h4>
                    		<input type="text" name="for" class="form-control monthpicker" autocomplete="off">
                        </div>

                        <div class="col-md-6">
                        <h4 class="example-title">Employee</h4>
                        <select class="form-control" name="user_id" id="user_id" required>
                          <option value=""> Select User </option>
                          @if($users)
                              @foreach($users as $users)
                                  <option value="{{$users->id}}"> {{$users->name}} </option>                           
                              @endforeach
                          @endif
                      </select>
                    </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <h4 class="example-title">Day Rate Onshore</h4>
                        <input type="text" name="day_rate_onshore" class="form-control">
                      </div>
                      <div class="col-md-6">
                        <h4 class="example-title">Day Worked Onshore</h4>
                        <input type="text" name="days_worked_onshore" class="form-control">
                        </div>
                      
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <h4 class="example-title">Day Rate Offshore</h4>
                        <input type="text" name="day_rate_offshore" class="form-control">
                      </div>
                      

                        <div class="col-md-6">
                        <h4 class="example-title">Day Worked Offshore</h4>
                        <input type="text" name="days_worked_offshore" class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <h4 class="example-title">Paid Day Time Rate</h4>
                        <input type="text" name="paid_off_time_rate" class="form-control">
                        </div>

                        <div class="col-md-6">
                        <h4 class="example-title">Paid Off Day</h4>
                        <input type="text" name="paid_off_day" class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                      <h4 class="example-title">BRT Days</h4>
                      <input type="text" name="brt_days"  class="form-control">
                        </div>

                        <div class="col-md-6">
                      <h4 class="example-title">Living Allowance</h4>
                      <input type="text" name="living_allowance"  class="form-control">
                    </div>
                    </div>



                    <div class="form-group">
                      <div class="col-md-6">
                      <h4 class="example-title">Transport Allowance</h4>
                      <input type="text" name="transport_allowance"  class="form-control">
                    </div>

                    
                    <div class="col-md-6">
                      <h4 class="example-title">Days Out Of Station</h4>
                      <input type="text" name="days_out_of_station"  class="form-control">
                    </div>
                  </div>

                    

                    <input type="hidden" name="type" value="save_tmsa_schedule">
                          
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              	<br>
                <br>
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