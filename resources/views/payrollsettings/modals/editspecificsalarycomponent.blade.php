<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editSpecificSalaryComponentModal" aria-hidden="true" aria-labelledby="editSpecificSalaryComponentModal" role="dialog" >
      <div class="modal-dialog ">

          <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit Specific Salary Component</h4>
          </div>
            <div class="modal-body">


            <br>
            <hr>
            <form  class="form-horizontal" id="editSpecificSalaryComponentForm"  method="POST">

                    @csrf
                    <div class="form-group" >
                      <h4>Type</h4>
                      <input type="radio" id="esscallowance" name="ssctype" value="1"> Allowance
                      <input type="radio" id="esscdeduction" name="ssctype" value="0"> Deduction
                      <input type="radio" id="esscrebate" name="ssctype" value="2"> Tax Rebate
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" name="name" id="ename"  class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="ecategory">Category</label>

                      <select required="" name="category" style="width:100%;" id="ecategory" class="form-control " >
                        @foreach($sscs_categories as $category)
                        <option  value='{{$category->id}}'>{{$category->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Employee</h4>
                      <select name="user_id" id="eemps" style="width:100%;" class="form-control" >
                        <option></option>
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Comment (A note about this component)</h4>
                      <textarea name="comment" id="ecomment"  class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Amount</h4>
                      <input type="text" id="eamount" name="amount"  class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="taxable">Taxable</label>

                      <select required="" name="taxable" style="width:100%;" id="etaxable" class="form-control " >
                        <option selected value='0'>No</option>
                        <option selected value='1'>Yes</option></select>
                    </div>
                <div class="form-group">
                    <label class="example-title" for="etaxable_type">Taxable</label>

                    <select required="" name="taxable_type" style="width:100%;" id="etaxable_type" class="form-control " >
                        <option selected value='1'>Monthly</option>
                        <option selected value='2'>Annual</option></select>
                </div>
                    <div class="form-group">
                      <label class="example-title" for="one_off">One Time</label>

                      <select required="" name="one_off" style="width:100%;" id="eone_off" class="form-control " >
                        <option selected value='0'>No</option>
                        <option selected value='1'>Yes</option></select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">General Ledger Code</h4>
                      <input type="text" name="gl_code" id="egl_code"  class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Project Code</h4>
                      <input type="text" name="project_code" id="eproject_code" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Duration(Months)</h4>
                      <input type="number" name="duration" id="eduration"  class="form-control">
                    </div>
                    <input type="hidden" name="type" value="update_specific_salary_component">
                    <input type="hidden" name="id" id="eid">

                <div class="form-group">

                    <button type="submit" class="btn btn-info pull-left">Save</button>
                  </div>
                  <br>
            </form>

            </div>

         </div>

      </div>
    </div>
