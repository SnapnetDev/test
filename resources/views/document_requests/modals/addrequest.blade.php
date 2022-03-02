<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addDocumentRequestModal" aria-hidden="true"
     aria-labelledby="addDocumentRequestModal" role="dialog">
    <div class="modal-dialog ">
        <form class="form-horizontal" id="addDocumentRequestForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Create New Document Request</h4>
                </div>
                <div class="modal-body">


                    @csrf
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" class="form-control" name="title" id="title">

                    </div>
                    <div class="form-group">
                        <label for="">Due Date</label>


                        <input type="text" class=" form-control date_picker" name="due_date" placeholder="Due date"
                               id="due_date" value="" required="" readonly/>

                    </div>


                    <div class="form-group">
                        <label for="">Document Type</label>
                        <select class="form-control" id="abtype" name="document_request_type_id" style="width:100%;">
                            <option value="" selected="selected">-Select Document Request Type-</option>

                            @foreach ($document_request_types as $drt)
                                <option value="{{ $drt->id }}">{{ $drt->name }}</option>
                            @endforeach


                        </select>
                    </div>

                    <div class="form-group" id="docUpload" style="display: none">
                        <label for=""> Upload Document</label>
                        <input type="file" class="form-control" name="uploaded_doc" id="uploaded_doc" accept=" .pdf,
                        .doc,
                        .docx, .jpeg, .jpg" max-size="10048576">
                        <span style="font-size: 10px"> File types: pdf | doc | docx | gif | png | jpg | jpeg.  Max size:
                            10MB
                        </span>
                        <p style="font-size: 10px; color: red" id="fileError">  </p>

                    </div>


                    <div class="form-group">
                        <label for="">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" style="height: 100px;resize: none;"
                                  placeholder="Briefly State Reason" required="required"></textarea>
                    </div>

                    <input type="hidden" name="type" value="save_document_request">

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
