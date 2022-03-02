<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editOpportunityModal" aria-hidden="true"
     aria-labelledby="attendanceDetailsModal" role="dialog">
    <div class="modal-dialog modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Edit Opportunity</h4>
            </div>
            <div class="modal-body">
                <form  method="Post" id="editOpportunityForm">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Client Name</h4>
                                <input type="text" class="form-control focus" id="client_id" name="client_id" required autocomplete="off" placeholder="Client Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Project Name</h4>
                                <input type="text" class="form-control focus" id="project_name" name="project_name" required autocomplete="off" placeholder="Project Name">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Project Payment Status</h4>
                                <div class="form-group">
                                    <select class="form-control" name="payment_status" id="payment_status" required>
                                        <option value="pending">Pending</option>
                                        <option value="part_payment">Part Payment</option>
                                        <option value="half_payment">Half Payment</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Project Status</h4>
                                <div class="form-group">
                                    <select class="form-control"  name="project_status" id="project_status" required>
                                        <option value="pending">Pending</option>
                                        <option value="ongoing">Ongoing</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 10px; margin-top: 0px">
                                <h4 class="example-title">Project Date</h4>
                                <div class="input-group">
                                        <span class="input-group-addon">
                                          <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                    <input type="text" class="form-control" data-plugin="datepicker" name="date" id="date" required autocomplete="off" placeholder="Project Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Project Amount</h4>
                                <input type="number" class="form-control focus" id="project_amount" name="project_amount" required autocomplete="off" placeholder="Project Amount">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id">

                    <button type="submit" class="btn btn-primary" id="editOpportunityFormSubmit">Submit</button>
                    <div id="loader2" style="display: none;" class="loader vertical-align-middle loader-ellipsis"></div>
                </form>
            </div>
        </div>
    </div>
</div>