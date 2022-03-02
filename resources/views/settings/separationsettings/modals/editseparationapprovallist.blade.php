<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editSeparationApprovalListModal" aria-hidden="true" aria-labelledby="editSeparationApprovalListModal" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <form class="form-horizontal" id="editSeparationApprovalListForm"  method="POST">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Edit Separation Approval List</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            @csrf
                            <div class="form-group">
                                <h4 class="example-title">Name</h4>
                                <input type="text" id="editalname" name="name" class="form-control" >
                            </div>


                            <input type="hidden" value="save_separation_approval_list" name="type">
                            <input type="hidden" id="editalid" name="separation_approval_list_id">
                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
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
