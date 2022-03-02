<div class="modal fade in modal-3d-flip-horizontal modal-info" id="uploadDETModal" aria-hidden="true" aria-labelledby="uploadDETModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="uploadDETForm" enctype="multipart/form-data" >
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Upload Department Evaluation Template</h4>
	        </div>
            <div class="modal-body">         
                
                  	
            
            @csrf
            <a href="{{url('bscsettings/download_template')}}">Download Template for upload</a>
          <div class="form-group">
            <label for="">Template</label>
            <input type="file" name="template" class="form-control" required>
          </div>
          <input type="hidden" name="type" value="import_template">
           <input type="hidden" name="det_id" value="{{$det->id}}">
          
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              
                  <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Upload</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>