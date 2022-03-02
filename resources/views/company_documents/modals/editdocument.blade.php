<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editDocumentModal" aria-hidden="true" aria-labelledby="editDocumentModal" role="dialog" >
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="EditDocumentForm" enctype="multipart/form-data">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Document Request File </h4>
	        </div>
            <div class="modal-body">
            @csrf
                <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" id="editcdtitle" >

                </div>
          <div class="form-group">
            <label for="">FIle</label>
              <input type="file" class="form-control" name="document">
          </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" id="editcddescription" name="description" style="height: 100px;resize: none;" placeholder="Document Description" required="required"></textarea>
                </div>


                <input type="hidden" name="type" value="update_company_document">
                <input id="editcdid" type="hidden" name="document_id" value="">

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
