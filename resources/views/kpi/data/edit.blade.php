<form action="{{ route('app.exec',['update-kpi-data']) }}" method="post" enctype="multipart/form-data">

    {{ csrf_field() }}

    <input type="hidden" name="id" value="{{ $v->id }}" />

    <div id="edit-kpi-data{{ $v->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Kpi Data</h4>
                </div>
                <div class="modal-body">

                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Requirement
                                    </label>
                                    <textarea name="requirement" class="form-control" placeholder="Requirement">{{ $v->requirement }}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">
                                        Percentage
                                    </label>
                                    <input value="{{ $v->percentage }}" type="text" name="percentage" class="form-control" placeholder="Percentage" />
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</form>
