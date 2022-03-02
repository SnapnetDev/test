<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editEmployeeReimbursementTypeModal" aria-hidden="true" aria-labelledby="editEmployeeReimbursementTypeModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="editEmployeeReimbursementTypeForm"  method="POST">
          <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit Employee Reimbursement Type</h4>
          </div>
            <div class="modal-body">
                <div class="row row-lg col-xs-12">
                  <div class="col-xs-12">
                    @csrf
                      <div class="form-group">
                          <h4 class="example-title">Name</h4>
                          <input type="text" id="editname" name="name" class="form-control" required>
                      </div>
                      <div class="form-group">
                          <h4 class="example-title">Workflow</h4>
                          <select name="workflow_id" id="editworkflow_id" class="form-control" required>
                              <option value="">Select  Workflow</option>
                              @foreach($workflows as $workflow)
                                  <option value="{{$workflow->id}}">{{$workflow->name}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                </div>
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">

                  <div class="form-group">
                    <input type="hidden" id="editid" name="employee_reimbursement_type_id">
                      <input type="hidden" name="type" value="save_employee_reimbursement_type">
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
