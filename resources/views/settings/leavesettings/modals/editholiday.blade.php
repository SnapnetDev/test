<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editHolidayModal" aria-hidden="true" aria-labelledby="editHolidayModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="editHolidayForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit Holiday</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" name="title" class="form-control" id="edithtitle">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Date</h4>
                      
                    <input type="text" class="input-sm form-control datepicker" id="edithdate" name="name" placeholder="Date" value=""/>
                    
                    
                    </div>
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                
                  <div class="form-group">
                    <input type="hidden" id="editid" name="holiday_id">
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