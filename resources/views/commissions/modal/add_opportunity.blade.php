<div class="modal fade in modal-3d-flip-horizontal modal-info" id="exampleNiftyFadeScale" aria-hidden="true"
     aria-labelledby="attendanceDetailsModal" role="dialog">
    <div class="modal-dialog modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Add New Opportunity</h4>
            </div>
            <div class="modal-body">
                <form  method="Post" id="addOpportunityForm">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Client Name</h4>
                                <input type="text" class="form-control focus" id="inputFocus" name="client_id" required autocomplete="off" placeholder="Client Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Project Name</h4>
                                <input type="text" class="form-control focus" id="inputFocus" name="project_name" required autocomplete="off" placeholder="Project Name">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Project Payment Status</h4>
                                <div class="form-group">
                                    <select class="form-control"  name="payment_status" id="payment_status" required>
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
                                    <select class="form-control" name="project_status" id="project_status" required>
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
                                    <input type="text" class="form-control" data-plugin="datepicker" name="date" required autocomplete="off" placeholder="Project Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="example" style="margin-bottom: 0px; margin-top: 0px">
                                <h4 class="example-title">Project Amount</h4>
                                <input type="number" class="form-control focus" id="inputFocus" name="project_amount" required autocomplete="off" placeholder="Project Amount">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="addOpportunityFormSubmit">Submit</button>
                    <div id="loader" style="display: none;" class="loader vertical-align-middle loader-ellipsis"></div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade in modal-3d-flip-horizontal modal-info" id="bulkImportModal" aria-hidden="true"
     aria-labelledby="exampleModalTitle" role="dialog" >
    <div class="modal-dialog modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Upload Bulk</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6" style=" border-right: 1px dashed #333;">
                        <form class="form-horizontal" id="uploadOpportunityForm" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-row">
                                <h4>Add Opportunity</h4>
                                <h4><a href="{{ url('opportunity-template') }}">Download Template</a></h4>
                                <div class="form-group col-md-12">
                                    <input type="file" name="template" class="form-control-file">
                                    <input type="hidden" name="import_shift">
                                </div>
                            </div>
                            <div class="form-row">
                                <button type="submit" id="uploadOpportunityFormSubmit" class="btn btn-info pull-left ">Upload</button>
                            </div>
                            <br>
                            <div class="form-row">
                                <div id="Opportunityloader" style="display: none;" class="loader vertical-align-middle loader-ellipsis"></div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-6">
                        <form class="form-horizontal" id="uploadCommissionForm" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-row">
                                <h4>Add Commissions</h4>
                                <h4><a href="{{ url('commissions-template') }}">Download Template</a></h4>
                                <div class="form-group col-md-12">
                                    <input type="file" name="template" class="form-control-file">
                                    <input type="hidden" name="import_shift">
                                </div>
                            </div>
                            <div class="form-row">
                                <button type="submit" id="uploadCommissionFormSubmit" class="btn btn-info pull-left ">Upload</button>
                            </div>
                            <br>
                            <div class="form-row">
                                <div id="Commissionloader" style="display: none;" class="loader vertical-align-middle loader-ellipsis"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>