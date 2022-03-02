<div class="modal fade modal-super-scaled" id="deciplinary_action_taken" aria-labelledby="exampleModalTitle"
     role="dialog" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-info modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Disciplinary Action Details</h4>
            </div>
            <div class="modal-body">

                <table class="table">
                    <tr>
                        <td>Are There Any Disciplinary Action Taken</td>
                        <td><select class="form-control" name="action_taken" id="disciplinary_action">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="action_taken hide">
                        <td>
                            Action Taken
                        </td>
                        <td>
                            <input type="hidden" id="query_id">
                            <input type="hidden" id="queried_user_id">
                            <select class="select_action_taken form-control" name="specific_action_taken"
                                    id="select_action_taken">

                                <option value="warning">Warning</option>
                                <option value="suspension">Suspension</option>
                                <option value="dismissal">Dismissal</option>
                                <option value="other">Other</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="effective_date hide">
                        <td>Effective Date</td>
                        <td>
                            <input type="text" value="{{date('Y-m-d')}}" readonly name="effective_date"
                                   class="effective_date form-control datepicker_noprevious">
                        </td>
                    </tr>
                    <tr class="others hide">
                        <td>Others</td>
                        <td>
                          <textarea class="form-control" style="height: 100px" name="other_action">

                          </textarea>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="close_query_btn" class="btn btn-primary">Close Query</button>
            </div>
        </div>
    </div>
</div>



