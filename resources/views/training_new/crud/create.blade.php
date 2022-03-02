 <div id="create" class="modal fade" role="dialog">
     <form action="">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Training Plan</h4>
                </div>
                <div class="modal-body">


                    <div class="col-md-12">


                        <div class="row">



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Name Of Training
                                    </label>
                                    <input  type="text" data-input name="name" class="form-control" placeholder="Name Of Training" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Cost Per Head
                                    </label>
                                    <input data-input  autocomplete="off"  type="text" name="cost_per_head" class="form-control" placeholder="Cost Per Head" />
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Number Of Enrollees
                                    </label>
                                    <input data-input  autocomplete="off"  type="text" name="number_of_enrollees" class="form-control" placeholder="Number Of Enrollees" />
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Grand Total
                                    </label>
                                    <input data-input  readonly  type="text" name="grand_total" class="form-control" placeholder="Grand Total" />
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Training Start
                                    </label>
                                    <input data-input   type="date" name="train_start" class="form-control" placeholder="Training Start" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Training Stop
                                    </label>
                                    <input data-input type="date" name="train_stop" class="form-control" placeholder="Training Stop" />
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Department
                                    </label>
                                    <select data-input  name="dep_id" class="form-control" id="">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Role
                                    </label>
                                    <select data-input  name="role_id" class="form-control" id="">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">
                                        Assign Groups
                                    </label>
                                    <select  class="form-control" id="group-select">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9" id="el-other" style="padding: 0;"></div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Enroll Instructions
                                    </label>
                                    <textarea data-input name="enroll_instructions" class="form-control" id=""></textarea>
                                    {{--cols="30" rows="10"--}}
                                </div>
                            </div>


                            <div class="col-md-12">

                                <div>
                                    <label for="">
                                        <input  data-input type="checkbox" name="type" value="1"> &nbsp; Online
                                    </label>
                                </div>
                                <div class="form-group" id="resource_url_container">
                                    <label for="">
                                        Resource Url
                                    </label>
                                    <input  data-input  type="text" name="resource_url" class="form-control" placeholder="Resource Url" />
                                </div>


                            </div>



                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-sm">Create</button>
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