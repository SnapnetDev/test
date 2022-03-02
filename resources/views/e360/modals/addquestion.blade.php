<!--- Add Subject modal start -->
<div class="modal fade in modal-3d-flip-horizontal modal-primary" id="addQuestionModal" aria-hidden="true" aria-labelledby="addQuestionModal"
     role="dialog" tabindex="-1">

    <div class="modal-dialog ">
        <form class="form-horizontal" id="addQuestionForm" role="form" method="POST">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title" id="training_title">Add Question</h4>
                </div>
                <div class="modal-body">

                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">

                            <div class="form-group">
                                <h4 class="example-title"> Question Category</h4>
                                <select name="question_category_id" id="" class="form-control" required>
                                    @foreach($question_categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>


                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Question</h4>

                                <textarea  name="question" class="form-control "></textarea>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Self Question</h4>

                                <textarea  name="self_question" class="form-control "></textarea>
                            </div>

                        </div>

                        <input type="hidden" name="type" value="save_question">
                        <input type="hidden"  name="mp_id" value="{{$mp->id}}" >
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
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Add subject modal end -->
