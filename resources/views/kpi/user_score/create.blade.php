<form action="{{ route('app.exec',['add-kpi-data']) }}?scope={{ request()->get('scope') }}&type={{ request()->get('type') }}&kpi_interval_id={{ request()->get('kpi_interval_id') }}&dep_id={{ request()->get('dep_id') }}" method="post" enctype="multipart/form-data">

    {{ csrf_field() }}

    <div id="create-kpi-data" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Kpi Data</h4>
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
                                    <input type="file" name="excel_file" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        {{--Download Template--}}
                                    </label>
                                    <a href="{{ asset('kpi_data_template.xlsx') }}">Download Template</a>
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
