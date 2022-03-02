<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editSalaryReviewItemModal" aria-hidden="true" aria-labelledby="editSalaryReviewItemModal" role="dialog" >
    <div class="modal-dialog ">

        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="">Edit New Salary Review</h4>
            </div>
            <div class="modal-body">

                <hr>
                <form  class="form-horizontal" id="editSalaryReviewForm"  method="POST">

                    @csrf
                    <div class="form-group">
                        <h4 class="example-title">Employee</h4>
                        <select name="employee_id" id="eemps" style="width:100%;" class="form-control" >
                            <option></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <h4 class="example-title">Review Month</h4>
                        <input type="text" id="srreview_month" name="review_month"  class="form-control monthpicker">
                    </div>
                    <div class="form-group">
                        <h4 class="example-title">Salary Month</h4>
                        <input type="text" id="srpayment_month" name="payment_month"  class="form-control monthpicker">
                    </div>
                    <div class="form-group">
                        <h4 class="example-title">Previous Gross</h4>
                        <input type="text" id="srprevious_gross" name="previous_gross" required class="form-control ">
                    </div>


                    <input type="hidden" name="type" value="salary_review">
                    <input type="hidden" name="id" id="eid" >


                    <div class="form-group">

                        <button type="submit" class="btn btn-info pull-left">Save</button>
                    </div>
                    <br>
                </form>

            </div>

        </div>

    </div>
</div>
