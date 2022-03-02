<form action="{{ route('app.exec',['add-kpi-year']) }}" method="post" enctype="multipart/form-data">

    {{ csrf_field() }}

    <div id="create-kpi-year" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Kpi Year</h4>
                </div>
                <div class="modal-body">

                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Year
                                    </label>
                                    <input type="text" name="year" class="form-control" placeholder="Year" />
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm">Create</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</form>
