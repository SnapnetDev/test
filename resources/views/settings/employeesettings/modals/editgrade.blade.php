<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editGradeModal" aria-hidden="true" aria-labelledby="editGradeModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="editGradeForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit Grade</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Level</h4>
                      <input type="text" name="level" class="form-control" id="editlevel">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Grade Category</h4>
                      <select class="form-control" name="grade_category_id" id="editgc">
                        @foreach($grade_categories as $grade_category)
                        <option value="{{$grade_category->id}}">{{$grade_category->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Monthly Gross Salary</h4>
                      <input type="text" name="basic_pay" id="editbasic_pay" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Leave Length</h4>
                      <input type="text" name="leave_length" id="editleave_length" class="form-control">
                    </div>
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                
                  <div class="form-group">
                    <input type="hidden" id="editid" name="grade_id">
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