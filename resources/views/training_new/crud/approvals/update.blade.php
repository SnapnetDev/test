<div id="edit-approval" class="modal fade" role="dialog">
    <form action="">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Submit Approval</h4>
                </div>
                <div class="modal-body">


                    <div class="col-md-12">


                        <div class="row">



                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Comment
                                    </label>
                                    <textarea name="comment" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Status
                                    </label>
                                    <select name="status" class="form-control" >
                                        <option value="">--Select--</option>
                                        <option value="1">Approve</option>
                                        <option value="0">Reject</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-success btn-sm">Submit</button>
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