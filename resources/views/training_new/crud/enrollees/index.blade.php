<script id="enrollee-template" type="text/html">

    <span>


    <div id="enrollee" class="modal fade" role="dialog">

        <div class="modal-dialog modal-info modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="title">Enrolled Users</h4>
                </div>
                <div class="modal-body">


                    <div id="enrollee-outlet" class="col-md-12">


                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-5" id="create-container">
                                    <form action="" method="post">
                                        @csrf
                                        <select style="padding: 6px;margin-bottom: 11px;" name="user_id" id="">
                                        <option value="">--Select Employee--</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-success">Enroll</button>
                                    </form>
                                </div>

                            </div>
                        </div>


                        <table class="table table-striped">

                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    E-mail
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>

                        </table>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>

    </div>






        {{--modal--}}
        <div id="edit-container" class="modal fade" role="dialog">
<div  class="modal-dialog modal-info modal-md">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="title">User Feedback</h4>
        </div>
        <div class="modal-body">

            <div class="row" style="">



                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">
                            Progress Notes
                        </label>
                        <textarea name="progress_notes" class="form-control" readonly="readonly"></textarea>
                    </div>


                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">
                            Feedback
                        </label>
                        <textarea name="feedback" class="form-control" readonly="readonly"></textarea>
                    </div>

                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">
                            <input type="checkbox" name="completed" value="1" readonly="readonly">
                            Is Completed
                        </label>
                    </div>

                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">
                            <div id="download">
                                <a href="http://127.0.0.1:8000/uploads/user_training_feedback/8O0dPV9I4GEYhN1bFvmglbFiKkPLMLbdwBKU3NOm.docx" style="display: inline;">Download Certificate</a>
                            </div>
                        </label>
                    </div>

                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">
                            Progress in percentage(%)
                        </label>
                        <input name="progress" class="form-control" readonly="readonly">
                    </div>

                </div>


                <div class="col-md-8">
                    <div class="form-group">
                        <label for="">
                            Rating
                        </label>
                        <div class="mrating" style="text-align: left;">

                            <span data="5">☆</span>
                            <span data="4">☆</span>
                            <span data="3">☆</span>
                            <span data="2" class="selected">☆</span>
                            <span data="1">☆</span>



                        </div>

                        <span></span>
                    </div>

                </div>



            </div>

        </div>


    </div>
</div>
</div>
        {{--modal--}}






    </span>


</script>


{{--<script type="text/html">--}}
{{--</script>--}}

