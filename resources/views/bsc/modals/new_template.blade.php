<div class="modal fade in modal-3d-flip-horizontal modal-info" id="newTemplateModal" aria-hidden="true" aria-labelledby="newTemplateModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="newTemplateForm" action="{{url('bscsettings/get_det')}}" >
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">New Template</h4>
	        </div>
            <div class="modal-body">
          <div class="form-group">
            <label for="">Template name</label>
              <input type="text" class="form-control" required name="title">
          </div>

          
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