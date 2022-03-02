<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addSalaryReviewModal" aria-hidden="true" aria-labelledby="addSalaryReviewModal" role="dialog" >
	    <div class="modal-dialog ">

	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="">Add New Salary Review</h4>
	        </div>
            <div class="modal-body">

            <hr>
            <form  class="form-horizontal" id="addSalaryReviewForm"  method="POST">

                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Employee</h4>
                      <select name="employee_id" id="emps" style="width:100%;" class="form-control" >
                        <option></option>
                      </select>
                    </div>

                    <div class="form-group">
                      <h4 class="example-title">Review Month</h4>
                      <input type="text" name="review_month" required class="form-control monthpicker">
                    </div>
                <div class="form-group">
                    <h4 class="example-title">Salary Month</h4>
                    <input type="text" name="payment_month" required class="form-control monthpicker">
                </div>
                <div class="form-group">
                    <h4 class="example-title">Previous Gross</h4>
                    <input type="text" name="previous_gross" required class="form-control ">
                </div>


                    <input type="hidden" name="type" value="salary_review">

                <div class="form-group">

                    <button type="submit" class="btn btn-info pull-left">Save</button>
                  </div>
                  <br>
            </form>

            </div>

	       </div>

	    </div>
	  </div>
