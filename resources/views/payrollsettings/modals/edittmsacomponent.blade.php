<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editTMSAComponentModal" aria-hidden="true" aria-labelledby="editTMSAComponentModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form class="form-horizontal" id="editTMSAComponentForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit  TMSA Component</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <div class="row">
                <div class="col-xs-12 col-xl-6">
                  <div class="form-group" >
                      <h4>Type</h4>
                      <input type="radio" id="edittcallowance" name="tctype" value="1"> Allowance
                      <input type="radio" id="edittcdeduction" name="tctype" value="0"> Deduction
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group" >
                      <h4>Fixed</h4>
                      <input type="radio" id="edittcfixed" name="tcfixed" value="1"> Fixed Amount
                      <input type="radio" id="edittcderivative" name="tcfixed" value="0"> Derivative(Formula)
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" id="edittcname" name="name" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Comment (A note about this component)</h4>
                      <textarea name="comment" id="edittccomment" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
               <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Amount</h4>
                      <input type="text" id="edittcamount" name="amount" class="form-control">
                    </div>
                    </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Formula</h4>
                      <input type="text" id="edittcformula" name="formula" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <label class="example-title" for="taxable">Taxable</label>
                      <select required="" name="taxable" style="width:100%;" id="edittctaxable" class="form-control " >
                        <option value='0'>No</option>
                        <option value='1'>Yes</option></select>
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <label class="example-title" for="taxable">Uses Months</label>
                      <select required="" name="uses_month" style="width:100%;" id="edittcuses_month" class="form-control " >
                        <option value='0'>No</option>
                        <option value='1'>Yes</option></select>
                    </div>
                  </div>
                  
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">General Ledger Code</h4>
                      <input type="text" name="gl_code" id="edittcgl_code"  class="form-control">
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Constant</h4>
                      <input type="text" id="edittcconstant" name="constant" class="form-control" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <label class="example-title" for="constant">Applies to All Employee Except</label>
                      
                      <select  name="exemptions[]" style="width:100%;" id="edittcexemptions" class="form-control " multiple><option selected value='0'>Not Applicable</option></select>
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <label class="example-title" for="constant">Months</label>
                      
                      <select  name="months[]" style="width:100%;" id="edittcmonths" class="form-control " multiple></select>
                    </div>
                  </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Year</h4>
                      <input type="text" name="year" id="edittcyear"  class="form-control">
                    </div>
                  </div>
                  <div class="col-xs-12 col-xl-6">
                    <div class="form-group">
                      <h4 class="example-title">Rate</h4>
                      <input type="text" name="rate" id="edittcrate" class="form-control">
                    </div>
                  </div>
                </div>
                    <input type="hidden" name="tmsa_component_id" id="edittcid">
                    <input type="hidden" name="type" value="tmsa_component">
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