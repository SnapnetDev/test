<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editPaceSalaryComponentModal" aria-hidden="true" aria-labelledby="editPaceSalaryComponentModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="editPaceSalaryComponentForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit  Project Salary Component</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Type</h4>
                      <input type="radio" id="editpcallowance" name="pctype" value="1"> Allowance
                      <input type="radio" id="editpcdeduction" name="pctype" value="0"> Deduction
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Fixed</h4>
                      <input type="radio" id="editpcfixed" name="pcfixed" value="1"> Fixed Amount
                      <input type="radio" id="editpcderivative" name="pcfixed" value="0"> Derivative
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="editpcuses_days">Uses Days</label>
                      
                      <select required="" name="uses_days" style="width:100%;" id="editpcuses_days" class="form-control " >
                        <option value='0'>No</option>
                        <option value='1'>Yes</option></select>
                    </div>
                    <div class="form-group">
                        <label class="example-title" for="editpcuses_anniversary">Uses Anniversary</label>

                        <select required="" name="uses_anniversary" style="width:100%;" id="editpcuses_anniversary" class="form-control">
                          <option value='0'>No</option>
                          <option value='1'>Yes</option></select>
                      </div>
                      <div class="form-group">
                        <label class="example-title" for="uses_probation">For Probationers</label>

                        <select required="" name="uses_probation" style="width:100%;" id="editpuses_probation" class="form-control">
                          <option value='0'>No</option>
                          <option value='1'>Yes</option></select>
                      </div>
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" name="name" id="editpcname" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="taxable">Project Salary Category</label>
                      <select required name="salary_category_id" style="width:100%;" id="editpcsalarycategory" class="form-control " >
                        @foreach($salary_categories as $category)
                        <option value='{{$category->id}}'>{{$category->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Comment (A note about this component)</h4>
                      <textarea name="comment" id="editpccomment" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Amount</h4>
                      <input type="text" name="amount" id="editpcamount" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Formula</h4>
                      <input type="text" id="editpcformula" name="formula" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Constant</h4>
                      <input type="text" name="constant" id="editpcconstant" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="edipctaxable">Taxable</label>
                      
                      <select required="" name="taxable" style="width:100%;" id="editpctaxable" class="form-control " >
                        <option value='0'>No</option>
                        <option value='1'>Yes</option></select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">General Ledger Code</h4>
                      <input type="text" name="gl_code" id="editpcgl_code" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Project Code</h4>
                      <input type="text" name="project_code" id="editpcproject_code" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="editpcexemptions">Applies to All Employee Except</label>
                      
                      <select name="exemptions[]" style="width:100%;" id="editpcexemptions" multiple></select>
                    </div>
                    <input type="hidden" name="id" id="editpcid">
                    <input type="hidden" name="type" value="save_project_salary_component">
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