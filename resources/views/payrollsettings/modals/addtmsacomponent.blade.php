<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addTMSAComponentModal" aria-hidden="true" aria-labelledby="addTmsaComponentModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog modal-lg">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add TMSA Component</h4>
	        </div>
          
            <div class="modal-body"> 
            <form class="form-horizontal" id="uploadTMSAComponentForm"  method="POST" enctype="multipart/form-data">
             
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Upload Excel Sheet</h4>
                      <a href="{{url('/payrollsettings/download_tmsa_component')}}">Want to add multiple at once? Download excel template here</a>
                      <input type="file" name="project_template" class="form-control">
                      <input type="hidden" name="type" value="import_tmsa_components">
                    </div>
                    <div class="form-group">
                    <button type="submit" class="btn btn-info pull-left ">Upload</button>
                    </div>
                  
                  
            </form>
            <br>
            <hr>         
                <form class="form-horizontal" id="addTMSAComponentForm"  method="POST">
                  	@csrf
               <div class="row">
                <div class="col-xs-12 col-xl-6">
                  <div class="form-group" >
                      <h4>Type</h4>
                      <input type="radio" id="allowance" name="tctype" value="1"> Allowance
                      <input type="radio" id="deduction" name="tctype" value="0"> Deduction
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group" >
                      <h4>Fixed</h4>
                      <input type="radio" id="fixed" name="tcfixed" value="1"> Fixed Amount
                      <input type="radio" id="derivative" name="tcfixed" value="0"> Derivative(Formula)
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                  	<div class="form-group">
                  		<h4 class="example-title">Name</h4>
                  		<input type="text" name="name" class="form-control" required>
                  	</div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Comment (A note about this component)</h4>
                      <textarea name="comment" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
               <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Amount</h4>
                      <input type="text" id="amount" name="amount" class="form-control">
                    </div>
                    </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Formula</h4>
                      <input type="text" id="formula" name="formula" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <label class="example-title" for="taxable">Taxable</label>
                      <select required="" name="taxable" style="width:100%;" id="taxable" class="form-control " >
                        <option value='0'>No</option>
                        <option value='1'>Yes</option></select>
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <label class="example-title" for="taxable">Uses Months</label>
                      <select required="" name="uses_month" style="width:100%;" id="uses_month" class="form-control " >
                        <option value='0'>No</option>
                        <option value='1'>Yes</option></select>
                    </div>
                  </div>
                  
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">General Ledger Code</h4>
                      <input type="text" name="gl_code"  class="form-control">
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Constant</h4>
                      <input type="text" name="constant" class="form-control" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <label class="example-title" for="constant">Applies to All Employee Except</label>
                      
                      <select  name="exemptions[]" style="width:100%;" id="exemptions" class="form-control " multiple><option selected value='0'>Not Appllicable</option></select>
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <label class="example-title" for="constant">Months</label>
                      
                      <select  name="months[]" style="width:100%;" id="months" class="form-control " multiple></select>
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Year</h4>
                      <input type="text" name="year"  class="form-control">
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Rate</h4>
                      <input type="text" name="rate"  class="form-control">
                    </div>
                  </div>
                </div>
                    <input type="hidden" name="type" value="tmsa_component">
                      <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                  </div> 
                  </form> 
                  <br>  
            </div>
           
             
	       </div>
	      
	    </div>
	  </div>