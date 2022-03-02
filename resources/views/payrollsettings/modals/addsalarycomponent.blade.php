<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addSalaryComponentModal" aria-hidden="true" aria-labelledby="addSalaryComponentModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add Salary Component</h4>
	        </div>
          <form class="form-horizontal" id="addSalaryComponentForm"  method="POST">
            <div class="modal-body">         
                
                  	@csrf
                   <div class="form-group" >
                      <h4>Type</h4>
                      <input type="radio" id="allowance" name="sctype" value="1"> Allowance
                      <input type="radio" id="deduction" name="sctype" value="0"> Deduction
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Name</h4>
                  		<input type="text" name="name" class="form-control">
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Comment (A note about this component)</h4>
                      <textarea name="comment" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Formula</h4>
                      <input type="text" name="formula" class="form-control">
                    </div>
                    <div class="form-group">
                      <label class="example-title" for="taxable">Taxable</label>
                      
                      <select required="" name="taxable" style="width:100%;" id="taxable" class="form-control " >
                        <option selected value='0'>No</option>
                        <option selected value='1'>Yes</option></select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Constant</h4>
                      <input type="text" name="constant" class="form-control">
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
                    <input type="hidden" name="type" value="salary_component">
                          
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
             </form>
	       </div>
	      
	    </div>
	  </div>