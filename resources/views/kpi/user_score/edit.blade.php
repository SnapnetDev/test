<style>
    .mbox{

        border: 1px solid #57c7d4;
        padding-top: 8px;
        background-color: #eee;
        color: #000;
        margin-bottom: 12px;

    }
</style>

<form data-modal-ref="edit-evaluation-data{{ $v->id }}" data-value-set="tag{{ $v->id }}" data-kpi-user-score-form="{{ $v->id }}" action="{{ route('app.exec',['kpi-create-user-score']) }}" method="post" enctype="multipart/form-data">

    {{ csrf_field() }}

    <input type="hidden" name="user_id" value="{{ $user_id }}" />
    <input type="hidden" name="kpi_data_id" value="{{ $v->id }}" />

    <div id="edit-evaluation-data{{ $v->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Kpi Evaluation</h4>
                </div>
                <div class="modal-body">

                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Requirement
                                    </label>
                                    <textarea disabled readonly name="requirement" class="form-control" placeholder="Requirement">{{ $v->requirement }}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Percentage
                                    </label>
                                    <input disabled readonly value="{{ $v->percentage }}" type="text" name="percentage" class="form-control" placeholder="Percentage" />
                                </div>
                            </div>
                        </div>





                        <div class="row mbox">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Your Score
                                    </label>
                                    <input {{ $user_required }} user-role="user" data-limit="{{ $v->percentage }}" user_score  type="text" name="user_score" class="form-control" placeholder="Your Score (Max {{ $v->percentage }}%)" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Your Comment
                                    </label>
                                    <textarea {{ $user_required }}  user-role="user" user_comment name="user_comment" class="form-control" placeholder="Your Comment" ></textarea>

                                </div>
                            </div>

                        </div>


                        <div class="row mbox">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Line Manager Score
                                    </label>
                                    <input {{ $manager_required }} user-role="manager" data-limit="{{ $v->percentage }}" manager_score  type="text" name="manager_score" class="form-control" placeholder="Line Manager Score (Max {{ $v->percentage }}%)" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Line Manager Comment
                                    </label>
                                    <textarea {{ $manager_required }}  user-role="manager" manager_comment name="manager_comment" class="form-control" placeholder="Line Manager Comment" ></textarea>

                                </div>
                            </div>

                        </div>




                        <div class="row mbox">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        HR Score
                                    </label>
                                    <input {{ $hr_required }} user-role="hr" data-limit="{{ $v->percentage }}" hr_score  type="text" name="hr_score" class="form-control" placeholder="HR Score (Max {{ $v->percentage }}%)" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        HR Comment
                                    </label>
                                    <textarea {{ $hr_required }} user-role="hr" hr_comment name="hr_comment" class="form-control" placeholder="HR Comment" ></textarea>

                                </div>
                            </div>

                        </div>



                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" id="user-score-indicator" class="btn btn-success btn-sm">Update Evaluation</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</form>
