<form action="{{ route('app.exec',['add-kpi-interval']) }}?kpi_year_id={{ request()->get('kpi_year_id') }}" method="post" enctype="multipart/form-data">

    {{ csrf_field() }}

    <div id="create-kpi-interval" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Kpi Interval</h4>
                </div>
                <div class="modal-body">

                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Interval Name
                                    </label>
                                    <input type="text" name="name" class="form-control" placeholder="Interval Name" />
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Interval Start
                                    </label>
                                    <input type="date" name="interval_start" class="form-control" placeholder="Interval Start" />
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Interval Stop
                                    </label>
                                    <input type="date" name="interval_stop" class="form-control" placeholder="Interval Stop" />
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
