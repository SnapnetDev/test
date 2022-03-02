<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editWorkingPeriodModal" aria-hidden="true" aria-labelledby="editWorkingPeriodModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="editWorkingPeriodForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit Business Hours</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Business Starts</h4>
                      <div class="input-group clockpicker-wrap" data-plugin="clockpicker">
                    <input type="time" data-plugin="clockpicker" class="form-control" name="sob" id="editsob">
                    <span class="input-group-addon">
                      <span class="md-time"></span>
                    </span>
                  </div>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Business Ends</h4>
                      <div class="input-group clockpicker-wrap" >
                    <input type="time" class=" clockpicker form-control" name="cob" id="editcob">
                    <span class="input-group-addon">
                      <span class="md-time"></span>
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