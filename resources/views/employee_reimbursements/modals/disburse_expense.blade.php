<div class="modal fade in modal-3d-flip-horizontal modal-info" id="DisburseEmployeeReimbursementModal" aria-hidden="true" aria-labelledby="DisburseEmployeeReimbursementModal" role="dialog" >
    <div class="modal-dialog ">
        <form class="form-horizontal" id="DisburseEmployeeReimbursementForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Disburse Fund </h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="">Disbursement Date</label>
                        <input type="text" class="form-control  date_picker" name="disbursement_date">
                    </div>

                    <input type="hidden" name="type" value="disburse_employee_reimbursement">
                    <input type="hidden" name="employee_reimbursement_id" id="disbursement_id" >

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
