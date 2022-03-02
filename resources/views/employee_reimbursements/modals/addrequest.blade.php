<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addEmployeeReimbursementModal" aria-hidden="true" aria-labelledby="addEmployeeReimbursementModal" role="dialog" >
    <div class="modal-dialog ">
        <form class="form-horizontal" id="addEmployeeReimbursementForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Create New Expense Reimbursement</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="form-group">
                        <label for="">Expense Date</label>
                        <input type="text"  class=" form-control date_picker" name="expense_date" placeholder="Expense date" id="expense_date" value="" required readonly />
                    </div>
                    <div class="form-group">
                        <label for="">Amount</label>
                        <input type="text" class="form-control" name="amount" id="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="">Attachment</label>
                        <input type="file" class="form-control" name="attachment">
                    </div>
                    <div class="form-group">
                        <label for="">Expense Reimbursement Type</label>
                        <select class="form-control" required id="employee_reimbursement_type_id" name="employee_reimbursement_type_id"  style="width:100%;">
                            <option value="">-Select Expense Reimbursement Type-</option>
                            @foreach($employee_reimbursement_types as $ert)
                                <option value="{{$ert->id}}">{{$ert->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea class="form-control" id="description" name="description" style="height: 100px;resize: none;" placeholder="Description" required="required"></textarea>
                    </div>
                    <input type="hidden" name="type" value="save_employee_reimbursement">
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
