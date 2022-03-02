<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editPerformanceCategoryModal" aria-hidden="true" aria-labelledby="editPerformanceCategoryModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">

	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Performance Category</h4>
	        </div>
          <form class="form-horizontal" id="editPerformanceCategoryForm"  method="POST">
            <div class="modal-body">

                  	@csrf

                  	<div class="form-group">
                  		<h4 class="example-title">Name</h4>
                  		<input type="text" name="name"  id="editgradecategoryname" class="form-control">
                  	</div>
                   

                    <div class="form-group">
                      <label class="example-title" for="constant">Grades</label>

                      <select  name="grade_id[]" style="width:100%;" id="editgrades" class="form-control" multiple></select>
                    </div>
                    <input type="hidden" name="type" value="save_bsc_grade_performance_category">
                    <input type="hidden" name="performance_category_id" id="editperformancecategoryid">

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
