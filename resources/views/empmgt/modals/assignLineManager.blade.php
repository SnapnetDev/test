<div class="modal fade in modal-3d-flip-horizontal modal-info" id="assignManagerModal" aria-hidden="true" aria-labelledby="assignManagerModal" role="dialog" >
    <div class="modal-dialog ">
        <form class="form-horizontal" id="assignLineManagerForm" >
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Assign Line Manager</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            @csrf
                            <div class="form-group">
                                <h4 class="example-title">Managers</h4>
                                <select id="managers" class="form-control select2" style="width:100%;">
                                    @foreach ($managers as $manager)
                                        <option value="{{$manager->id}}">{{$manager->name}}</option>
                                    @endforeach
                                    <option></option>
                                </select>
                            </div>


                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">

                        <div class="form-group">

                            <button type="button" class="btn btn-info pull-left" id="assignLineManager">Save</button>
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                        </div>
                        <!-- End Example Textarea -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
