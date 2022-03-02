<form action="{{ route('app.exec',['create-individual-kpi']) }}?type={{ $type }}&user_id={{ $user_id }}&dep_id={{ $workdept_id }}" method="post" enctype="multipart/form-data">

    {{ csrf_field() }}

    <div id="create-individual-kpi-interval" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Individual Kpi</h4>
                </div>
                <div class="modal-body">

                    <div class="col-md-12">


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Requirement
                                    </label>
                                    <textarea name="requirement" class="form-control" placeholder="Requirement"></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Percentage
                                    </label>
                                    <input type="text" name="percentage" class="form-control" placeholder="Percentage" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Bulk Excel Upload
                                    </label>
                                    <input type="file" name="excel_file" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        <a href="{{ asset('kpi_data_template.xlsx') }}">Download Excel Template</a>
                                    </label>
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
