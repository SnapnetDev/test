<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addPaceSalaryComponentModal" aria-hidden="true" aria-labelledby="addTmsaComponentModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add Project Salary Component</h4>
	        </div>
          
            <div class="modal-body"> 
            <form class="form-horizontal" id="uploadProjectsSalaryComponentForm"  method="POST" enctype="multipart/form-data">
             
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Upload Excel Sheet</h4>
                      <a href="{{url('/payrollsettings/download_project_salary_component')}}">Want to add multiple at once? Download excel template here</a>
                      <input type="file" name="project_template" class="form-control">
                      <input type="hidden" name="type" value="import_project_salary_components">
                    </div>
                    <div class="form-group">
                    <button type="submit" class="btn btn-info pull-left ">Upload</button>
                    </div>
                  
                  
            </form>
            <br>
            <hr>         
                <form class="form-horizontal" id="addPaceSalaryComponentForm"  method="POST">
                  	@csrf
                   <div class="form-group" >
                      <h4>Type</h4>
                      <input type="radio" id="allowance" name="pctype" value="1"> Allowance
                      <input type="radio" id="deduction" name="pctype" value="0"> Deduction
                    </div>
                    <div class="form-group" >
                      <h4>Fixed</h4>
                      <input type="radio" id="fixed" name="pcfixed" value="1"> Fixed Amount
                      <input type="radio" id="derivative" name="pcfixed" value="0"> Derivative(Formula)
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="uses_days">Uses Days</label>
                      
                      <select required="" name="uses_days" style="width:100%;" id="uses_days" class="form-control">
                        <option value='0'>No</option>
                        <option value='1'>Yes</option></select>
                    </div>
                    <div class="form-group">
                        <label class="example-title" for="uses_anniversary">Uses Anniversary</label>

                        <select required="" name="uses_anniversary" style="width:100%;" id="uses_anniversary" class="form-control">
                          <option value='0'>No</option>
                          <option value='1'>Yes</option></select>
                      </div>
                      <div class="form-group">
                        <label class="example-title" for="uses_probation">For Probationers</label>

                        <select required="" name="uses_probation" style="width:100%;" id="uses_probation" class="form-control">
                          <option value='0'>No</option>
                          <option value='1'>Yes</option></select>
                      </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Name</h4>
                  		<input type="text" name="name" class="form-control" required>
                  	</div>
                    <div class="form-group">
                      <label class="example-title" for="taxable">Project Salary Category</label>
                      <select required name="salary_category_id" style="width:100%;" id="category" class="form-control " >
                        @foreach($salary_categories as $category)
                        <option value='{{$category->id}}'>{{$category->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Comment (A note about this component)</h4>
                      <textarea name="comment" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Amount</h4>
                      <input type="text" id="amount" name="amount" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Formula</h4>
                      <input type="text" id="formula" name="formula" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="taxable">Taxable</label>
                      <select required="" name="taxable" style="width:100%;" id="taxable" class="form-control " >
                        <option value='0'>No</option>
                        <option value='1'>Yes</option></select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Constant</h4>
                      <input type="text" name="constant" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">General Ledger Code</h4>
                      <input type="text" name="gl_code"  class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Project Code</h4>
                      <input type="text" name="project_code"  class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="constant">Applies to All Employee Except</label>
                      
                      <select required="" name="exemptions[]" style="width:100%;" id="exemptions" class="form-control " multiple><option selected value='0'>Not Appllicable</option></select>
                    </div>

                    <input type="hidden" name="type" value="save_project_salary_component">
                      <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                  </div> 
                  </form> 
                  <br>  
            </div>
           
             
	       </div>
	      
	    </div>
	  </div>