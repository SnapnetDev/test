<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editSalComponentTypeModal" aria-hidden="true" aria-labelledby="editSalComponentTypeModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Salary Component Type</h4>
	        </div>
          <form class="form-horizontal" id="editSalComponentTypeForm"  method="POST">
            <div class="modal-body">         
                
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title"> Component Type Name</h4>
                      <input type="text" name="name" id="esalname" class="form-control">
                    </div>
                    <input type="hidden" name="type"  value="sal_component_type">
                    <input type="hidden" name="id" id="esalid" value="">
                          
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