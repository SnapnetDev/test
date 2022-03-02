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

<div id="edit-feedback" class="modal fade" role="dialog">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send Feedback</h4>
                </div>
                <div class="modal-body">


                    <div class="col-md-12">


                        <div class="row">

                            <div class="col-md-12" align="right">
                                <a data-toggle="modal" data-target="#onboard-instructions" href="#" class="btn btn-sm btn-primary">On-board Instructions</a>
                            </div>



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
                                        Indicate Completion
                                    </label>
                                </div>
                                {{--edit-feedback--}}
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Upload Certificate
                                        <input name="file" type="file" name="completed" />
                                        <div id="download">
                                            <a href="">Download</a>
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

                                        <input type="hidden" name="rating" />

                                    </div>

                                    <span></span>
                                </div>
                                {{--edit-feedback--}}
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                </div>
                            </div>


                        </div>


                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </form>
</div>

<div id="onboard-instructions" class="modal fade" role="dialog">

        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Enrollment Instructions</h4>
                </div>
                <div class="modal-body">


                    <div class="col-md-12">


                        <div class="row">


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Enroll Instructions
                                    </label>
                                    <textarea name="enroll_instructions" readonly class="form-control"></textarea>
                                </div>
                                {{--edit-feedback--}}
                            </div>

                            <div class="col-md-12">
                                <a href="" target="_blank" id="access" class="btn btn-sm btn-warning">Access E-learning</a>
                            </div>




                        </div>


                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>

</div>