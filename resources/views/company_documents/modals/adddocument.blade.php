<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addDocumentModal" aria-hidden="true" aria-labelledby="addDocumentModal" role="dialog" >
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addDocumentForm" enctype="multipart/form-data">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Create New Document</h4>
	        </div>
            <div class="modal-body">



            @csrf
                <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" id="title" >

                </div>
                <div class="form-group">
                    <label for="">FIle</label>
                    <input type="file" class="form-control" name="document">
                </div>


                <input type="hidden" name="type" value="save_company_document">







          <div class="form-group">
            <label for="">Description</label>
            <textarea class="form-control" id="description" name="description" style="height: 100px;resize: none;" placeholder="Document Description" required="required"></textarea>
          </div>



            </div>
            <div class="modal-footer">
              <div class="col-xs-12">

                  <div class="form-group">

                    <button type="submit" class="btn btn-info pull-left">Submit</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>
