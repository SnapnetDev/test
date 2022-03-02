<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addOrganogramPositionModal" aria-hidden="true" aria-labelledby="addOrganogramPositionModal" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <form class="form-horizontal" id="addOrganogramPositionForm"  method="POST">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Add Organogram Position</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            @csrf
                            <div class="form-group">
                                <h4 class="example-title">Name</h4>
                                <input type="text" id="addopname" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Level</h4>
                                <select name="company_organogram_level_id" id="addopcompany_organogram_level_id" required class="form-control select2" style="width:100%;">
                                    @foreach($organogram_levels as $level)
                                        <option value="{{$level->id}}">{{$level->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Employee Name</h4>
                                <select name="user_id"   class="form-control select2" style="width:100%;">
                                    <option value="0">None</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Parent Position</h4>
                                <select name="p_id" id="parent_node_id"  required class="form-control select2" style="width:100%;">
                                    <option value="0">Root</option>
                                    @foreach($organogram_positions as $position)

                                        <option value="{{$position->id}}">{{$position->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Second Parent Position</h4>
                                <select name="sp_id"   class="form-control select2" style="width:100%;">
                                    <option value="0">None</option>
                                    @foreach($organogram_positions as $position)

                                        <option value="{{$position->id}}">{{$position->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">

                        <div class="form-group">

                            <input type="hidden" name="company_organogram_id" value="{{$organogram->id}}">
                            <input type="hidden" name="type" value="save_organogram_position">
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
