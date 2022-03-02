<div class="modal fade modal-fade-in-scale-up" id="exampleNiftyFadeScale" aria-hidden="true"
     aria-labelledby="exampleModalTitle" role="dialog">
    <div class="modal-dialog modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Add Commission to Staff for {{ $opportunity->project_name }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form  method="Post" id="addCommissionForm">
                            {{ csrf_field() }}
                            <input type="hidden" name="opportunity_id" value="{{ $opportunity->id }}">
                            <div class="example-wrap m-sm-0">
                                <h4 class="example-title">Select Staff</h4>
                                <div class="form-group">
                                    <select style="width: 100%;" class="form-control" data-plugin="select2" data-select2-id="1" tabindex="-1" aria-hidden="true" name="staff_id" id="staff_id" required>
                                        @foreach($staffs as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="example-wrap" style="margin-bottom: 10px">
                                <h4 class="example-title">Expected Commission Amount</h4>
                                <input type="number" class="form-control focus" id="inputFocus" name="expected_commission" required placeholder="Expected Commission">
                            </div>
                            <div class="example-wrap" style="margin-bottom: 10px">
                                <h4 class="example-title">To Pay</h4>
                                <input type="number" class="form-control focus" name="commission" required placeholder="To Pay">
                            </div>
                            <button type="submit" class="btn btn-primary" id="addCommissionFormSubmit">Submit</button>
                            <div id="loader" style="display: none;" class="loader vertical-align-middle loader-ellipsis"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
