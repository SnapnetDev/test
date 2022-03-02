<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editShiftModal" aria-hidden="true"
     aria-labelledby="editShiftModal" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <form class="form-horizontal" id="editShiftForm" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Edit Shifts</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            @csrf
                            <div class="form-group">
                                <h4 class="example-title">Name</h4>
                                <input type="text" name="type" class="form-control" id="editstype">
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Shift Starts</h4>
                                <div class="input-group clockpicker-wrap" data-plugin="clockpicker">
                                    <input type="time" data-plugin="clockpicker" class="form-control" name="start_time" id="editsstart_time">
                                    <span class="input-group-addon"><span class="md-time"></span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Shift Ends</h4>
                                <div class="input-group clockpicker-wrap">
                                    <input type="time" class=" clockpicker form-control" name="end_time"
                                           id="editsend_time">
                                    <span class="input-group-addon"><span class="md-time"></span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Shift Color</h4>
                                <input type="text" id="editcolor_code" class="colorpicker-default form-control colorInputUi-input" value="#ff666b" name="color_code">
                            </div>
                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">

                        <div class="form-group">
                            <input type="hidden" id="editsid" name="shift_id">
                            <button type="submit" class="btn btn-info pull-left">Save</button>
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                        </div>
                        <!-- End Example Textarea -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>