<style>

    .mrating {
        unicode-bidi: bidi-override;
        direction: rtl;
        text-align: center;
    }

    /*.rating {*/
    /*unicode-bidi: bidi-override;*/
    /*direction: rtl;*/
    /*}*/

    .mrating > span {
        display: inline-block;
        position: relative;
        width: 2.1em;
        font-size: 26px;
    }
    .mrating > span:hover:before,
    .mrating > span:hover ~ span:before {
        content: "\2605";
        position: absolute;
    }

    .mrating > span.selected:before,
    .mrating > span.selected ~ span:before {
        content: "\2605";
        position: absolute;
    }


</style>


<script type="text/html" id="training-stat">

    <div class="col-md-4">
        <ul class="list-group list-group-bordered">
            <li class="list-group-item">
                Employee Number:
                <span id="emp_num" class="pull-right">1002</span></li>
            <li class="list-group-item ">Name:
                <span id="user_name" class="pull-right">....</span>
            </li>
            <li class="list-group-item ">Job Role:<span class="pull-right" id="job_title">...</span></li>
            <li class="list-group-item ">Department:<span class="pull-right" id="department_name">...</span></li>
            <li onclick="$globalTraining.fetchAll('')" data-toggle="modal" data-target="#recommend-offline-modal" class="list-group-item ">Available Trainings:<span class="pull-right tag tag-info" id="available-training">0</span></li>
            <li onclick="$globalTraining.fetchAll('enrolled=1')" data-toggle="modal" data-target="#recommend-offline-modal" class="list-group-item ">Enrolled Trainings:<span class="pull-right tag tag-info" id="enrolled-training">0</span></li>
            <li onclick="$globalTraining.fetchAll('completed=1')" data-toggle="modal" data-target="#recommend-offline-modal" class="list-group-item ">Completed Trainings:<span class="pull-right tag tag-info" id="completed-training" >0</span></li>
            <li onclick="$globalTraining.fetchAll('in_progress=1')" data-toggle="modal" data-target="#recommend-offline-modal" class="list-group-item ">Trainings In Progress:<span class="pull-right tag tag-info" id="in-progress-training">0</span></li>
        </ul>
    </div>

</script>


<script type="text/html" id="training-item">

    <div>

        <div class="col-md-12" style="margin-bottom: 8px;padding: 0;background-color: #fff;padding-bottom: 11px;">
            <h4 id="name" style="margin-top: 0;color: #fff;background-color: #75a9d6;padding: 11px;"></h4>
            <div class="row" style="padding: 5px;">

                <div class="col-md-6">
                    <b>Cost Per Head</b>
                </div>
                <div class="col-md-6" id="cost_per_head">
                    0.0
                </div>
                <div style="clear: both;"></div>

                <div class="col-md-6">
                    <b>Max Applicants</b>
                </div>
                <div class="col-md-6" id="number_of_enrollees">
                    11
                </div>
                <div style="clear: both;"></div>


                <div class="col-md-6">
                    <b>Enroll Status</b>
                </div>


                <div class="col-md-6">
                   Not-Enrolled
                </div>

                <div style="clear: both;"></div>

                <div class="col-md-6">
                    <b>Progress Status</b>
                </div>
                <div class="col-md-6">
                   0%
                </div>

                <div style="clear: both;"></div>


                <div class="col-md-6">
                    <b>Is Eligible</b>
                </div>
                <div class="col-md-6" >
                  Yes
                </div>
                <div style="clear: both;"></div>

                <span>
                <div class="col-md-6">
                <div>Department:</div>
                </div>


                <div class="col-md-6">
                <div id="department_name">Department Name</div>
                </div>
            </span>
                <div style="clear: both;"></div>

                <span>
                    <div class="col-md-6">
                    <div>Groups:</div>
                </div>


                <div class="col-md-6">
                <div id="group-container">
                </div>
                </div>
            </span>
                <div style="clear: both;"></div>


                <div class="col-md-6">
                    <div>Role:</div>
                </div>


                <div class="col-md-6">
                    <div id="role_name" style="font-style: italic;font-weight: bold;">Role</div>
                </div>
                <div style="clear: both;"></div>



                <div class="col-md-6">
                    <div>Type:</div>
                </div>


                <div class="col-md-6">
                    <div>
                        <span id="type" class="tag tag-success"></span>
                    </div>
                </div>

                <div style="clear: both;"></div>



                <div class="col-md-6">
                    <div>Status:</div>
                </div>


                <div class="col-md-6">
                    <div>
                        <span id="status" class="tag tag-info">Not-Enrolled</span>
                    </div>
                </div>

                <div style="clear: both;"></div>


                <div class="col-md-12">
                    <div class="col-md-12">


                        <hr />
                        <br />
                        <div id="feedback">

                            <div>
                                <label for="">
                                    <input type="checkbox" name="view-feedback">&nbsp;View Feedback
                                </label>
                            </div>

                            <br />

                            <div class="row" id="row-slide">



                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">
                                            Progress Notes
                                        </label>
                                        <textarea name="progress_notes" class="form-control"></textarea>
                                    </div>

                                    {{--edit-feedback--}}
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">
                                            Feedback
                                        </label>
                                        <textarea name="feedback" class="form-control"></textarea>
                                    </div>
                                    {{--edit-feedback--}}
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">
                                            <input type="checkbox" name="completed" />
                                            Is Completed
                                        </label>
                                    </div>
                                    {{--edit-feedback--}}
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">
                                            <div id="download">
                                                <a href="">Download Certificate</a>
                                            </div>
                                        </label>
                                    </div>
                                    {{--edit-feedback--}}
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">
                                            Enter Progress in percentage(%)
                                        </label>
                                        <input name="progress" class="form-control" />
                                    </div>
                                    {{--edit-feedback--}}
                                </div>


                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">
                                            Rating
                                        </label>
                                        <div  class="mrating" style="text-align: left;">

                                            <span data="5">☆</span>
                                            <span data="4">☆</span>
                                            <span data="3">☆</span>
                                            <span data="2">☆</span>
                                            <span data="1">☆</span>



                                        </div>

                                        <span></span>
                                    </div>
                                    {{--edit-feedback--}}
                                </div>



                            </div>

                        </div>




                    </div>
                </div>



                <div style="clear: both;"></div>


                <div class="col-md-12" align="right" style="margin-top: 12px;">

                    <form id="enroll-form" action="" method="post" style="display: inline-block;">
                        <input type="hidden" name="training_plan_id" />
                        <input type="hidden" name="user_id" />
                        <input type="hidden" name="type" value="enroll" />
                        <button id="enroll" type="submit" class="btn btn-sm btn-success">Enroll</button>
                    </form>

                    <form id="unenroll-form" action="" method="post" style="display: inline-block;">
                        <input type="hidden" name="training_plan_id" />
                        <input type="hidden" name="user_id" />
                        <input type="hidden" name="type" value="unenroll" />
                      <button id="unenroll" type="submit" class="btn btn-sm btn-danger">UnEnroll</button>
                    </form>

                </div>

            </div>
        </div>
    </div>


</script>

{{--training-stat--}}

<div id="recommend-offline-modal" role="dialog" class="modal fade in">
    <div class="modal-dialog modal-info modal-md modal-simple modal-sidebar" style="margin-top: 26px;">
        <div class="modal-content" style="min-height: 618px; background-color: rgb(238, 238, 238);">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" style="position: relative;top: 0px;" class="close">×</button>
                <h4 class="modal-title" style="text-transform: uppercase;">Recommend Training</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" style="background-color: rgb(255, 255, 255); margin-bottom: 7px; border: 1px solid rgb(221, 221, 221);">
                        <div class="checkbox-custom checkbox-primary" style="display: inline-block; margin-left: 13px;">
                            <input onclick="if ($(this).is(':checked')){ $globalTraining.fetchAll('dep_id=1'); }else{ $globalTraining.fetchAll('');}" name="filter_user_dep" type="checkbox" />
                            <label for="filter_user_dep">
                                Department
                            </label>
                        </div>

                        <div class="checkbox-custom checkbox-primary" style="display: inline-block; margin-left: 13px;">
                            <input onclick="if ($(this).is(':checked')){ $globalTraining.fetchAll('role_id=1'); }else{ $globalTraining.fetchAll('');}" type="checkbox">
                            <label for="filter_user_role">
                                Role
                            </label>
                        </div>

                        <div class="checkbox-custom checkbox-primary" style="display: inline-block; margin-left: 13px;">
                            <input type="checkbox" onclick="if ($(this).is(':checked')){ $globalTraining.fetchAll('group_id=1'); }else{ $globalTraining.fetchAll('');}">
                            <label for="filter_user_grp">
                                Group
                            </label>
                        </div>

                        <div class="checkbox-custom checkbox-primary" style="display: inline-block; margin-left: 13px;">
                            <input type="checkbox" onclick="if ($(this).is(':checked')){ $globalTraining.fetchAll('online=1'); }else{ $globalTraining.fetchAll('');}">
                            <label for="filter_user_grp">
                                Online
                            </label>
                        </div>

                        <div class="checkbox-custom checkbox-primary" style="display: inline-block; margin-left: 13px;">
                            <input type="checkbox" onclick="if ($(this).is(':checked')){ $globalTraining.fetchAll('enrolled=1'); }else{ $globalTraining.fetchAll('');}">
                            <label for="filter_user_grp">
                                Enrolled
                            </label>
                        </div>


                    </div>

                    <div class="col-md-12" style="padding: 0px;">

                        <div id="course-list" class="col-md-12" style="padding: 0px;">

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default waves-effect">Close</button>
            </div>

        </div>

    </div>

</div>
