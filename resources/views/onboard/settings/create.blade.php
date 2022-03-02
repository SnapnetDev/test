<form  action="{{ route('checklistSettings.store') }}" method="post" enctype="multipart/form-data">

    @csrf

    <input type="hidden" name="parent_id" value="{{ $parent_id }}" />

    <div id="create" class="modal fade" role="dialog">
        <div class="modal-dialog modal-info modal-md">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Checklist Item</h4>
                </div>
                <div class="modal-body">


                    <div class="col-md-12">


                        <div class="row">


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Action
                                    </label>
                                    <input value="{{ old('action') }}" type="text" name="action" class="form-control" placeholder="Action" />
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Assigned Personnel
                                    </label>
                                    <select name="assigned_personnel_id" class="form-control" id="">
                                        <option value="">--Select Personnel--</option>
                                        @foreach ($personnels as $personnel)
                                            <option {{ $selected($personnel->id == old('assigned_personnel_id')) }} value="{{ $personnel->id }}">{{ $personnel->name }} ({{ $personnel->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Time
                                    </label>
                                    <input value="{{ old('time') }}" type="time" name="time" class="form-control" placeholder="Time" />
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Duration
                                    </label>
                                    <input value="{{ old('duration') }}" type="number" name="duration" class="form-control" placeholder="Duration" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Document Template
                                    </label>
                                    <input type="file" name="document_template" class="form-control" placeholder="Document Template" />
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
