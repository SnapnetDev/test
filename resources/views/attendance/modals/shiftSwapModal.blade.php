<div class="modal fade in modal-3d-flip-horizontal modal-info" id="swapShiftModal" aria-hidden="true" aria-labelledby="swapShiftModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="swapShiftForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="schedule_title">Swap Shift for <span id="swap_title_date"></span></h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <input type="hidden" name="user_shift_schedule_id" id="user_shift_schedule_id">
                    <input type="hidden" name="date" id="date">
                    <div class="form-group">
                      <h4 class="example-title">Swap To</h4>
                      <select name="shift_id" id="swap_shifts" class="form-control">
                       
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Swap With</h4>
                       <select name="swapper_id" id="swap_users" class="form-control">
                        
                       </select>
                 
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Reason</h4>
                       <textarea name="reason" class="form-control"></textarea>
                 
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