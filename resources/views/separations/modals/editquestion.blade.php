<!--- Add Subject modal start -->
<div class="modal fade in modal-3d-flip-horizontal modal-primary" id="editQuestionModal" aria-hidden="true" aria-labelledby="editQuestionModal"
     role="dialog" tabindex="-1">

    <div class="modal-dialog ">
        <form class="form-horizontal" id="editQuestionForm" role="form" method="POST">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title" id="training_title">Edit Question</h4>
                </div>
                <div class="modal-body">

                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">

                            <div class="form-group">
                                <h4 class="example-title">Question</h4>

                                <textarea id="editqquestion"  name="question" class="form-control " required></textarea>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title"> Question Category</h4>
                                <select name="separation_question_category_id" id="editqquestion_category" class="form-control" required>
                                    @foreach($question_categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title"> Question Type</h4>
                                <select name="question_type" id="editqtype" class="form-control" required>
                                    <option value="txt">TextBox</option>
                                    <option value="chk">CheckBox</option>
                                    <option value="rad">Radio Button</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title"> Status</h4>
                                <select name="status" id="editqstatus" class="form-control" required>
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title"> Compulsory</h4>
                                <select name="compulsory" id="editqcompulsory" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                        </div>

                        <input type="hidden" name="type" value="save_separation_question">
                        <input type="hidden" id="editqid" name="separation_question_id" >
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                        <!-- Example Textarea -->
                        <div class="form-group">

                            {{ csrf_field() }}
                            <div class="text-xs-left">
                                <button type="submit" class="btn btn-primary "  value="Save" >Save</button>
                                <button  type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                            </div>
                            <!-- End Example Textarea -->
                        </div>
                        <!-- End Example Textarea -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Add subject modal end -->
