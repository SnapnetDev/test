<!--- Add Subject modal start -->
  <div class="modal fade in modal-3d-flip-horizontal modal-primary" id="editQuestionCategoryModal" aria-hidden="true" aria-labelledby="editQuestionCategoryModal"
  role="dialog" tabindex="-1">

    <div class="modal-dialog ">
      <form class="form-horizontal" id="editQuestionCategoryForm" role="form" method="POST">
        <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title" id="training_title">Edit Question Category</h4>
        </div>
        <div class="modal-body">

          <div class="row row-lg col-xs-12">
            <div class="col-xs-12">


              <div class="form-group">
                <h4 class="example-title">Name</h4>

                <input type="text" id="editqcname"  name="name" class="form-control ">
              </div>
                <div class="form-group">
                    <h4 class="example-title">Description</h4>

                    <textarea  name="description" id="editqcdescription" class="form-control "></textarea>
                </div>

            </div>

              <input type="hidden" name="type" value="save_question_category">
              <input type="hidden" id="editqcid" name="qc_id" >
            <div class="clearfix hidden-sm-down hidden-lg-up"></div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="col-xs-12">
              <!-- Example Textarea -->
              <div class="form-group">

                {{ csrf_field() }}
                <div class="text-xs-left"><span class="no-left-padding" id="btn_div"><input type="submit" class="btn btn-primary waves-effect" id="sugtraining_btn" value="Save" ></span>
                <span class="no-left-padding"><input type="button" class="btn btn-default waves-effect" value="Cancel"  data-dismiss="modal"></span></div>
              </div>
              <!-- End Example Textarea -->
            </div>
         </div>
       </div>
      </form>
    </div>
  </div>
  <!-- Add subject modal end -->
