<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editLeavePeriodModal" aria-hidden="true" aria-labelledby="editLeavePeriodModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="editLeavePeriodForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit LeavePeriod</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">name</h4>
                      <input type="text" name="name" class="form-control" id="editname">
                    </div>
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                
                  <div class="form-group">
                    <input type="hidden" id="editqid" name="qualification_id">
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