<form class="form-horizontal" id="addUserTrainingForm">
      {{ csrf_field() }}
      <div class="modal fade" id="addUserTrainingModal" aria-labelledby="examplePositionSidebar" role="dialog"  aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sidebar modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title">Select Users</h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label class="example-title" for="trainee_id">Users</label>
                  <select class="form-control select2" name="trainee_id[]" id="trainees" multiple="" style="width: 100%">
                    <option value="">  </option>
                    @if($users)
                        @foreach($users as $users)
                            <option value="{{$users->id}}"> {{$users->name}} </option>                            
                        @endforeach
                    @endif
                  </select>
                </div>
                <input type="hidden" name="training_id" id="edittrainingid">
              </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary btn-block waves-effect">Save changes</button>
              <button type="button" class="btn btn-default btn-block btn-pure waves-effect" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
</form>