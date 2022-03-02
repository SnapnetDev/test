<form class="form-horizontal" id="addGroupTrainingForm">
      {{ csrf_field() }} 
      <div class="modal fade" id="addGroupTrainingModal" aria-labelledby="examplePositionSidebar" role="dialog"  aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sidebar modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title">Select Groups</h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label class="example-title" for="group_id">Groups</label>
                  <select class="form-control select2" name="group_id[]" id="groups" multiple="" style="width: 100%">
                    <option value="">  </option>
                    @if($groups)
                        @foreach($groups as $groups)
                            <option value="{{$groups->id}}"> {{$groups->name}} </option>                            
                        @endforeach
                    @endif
                  </select>
                </div>
                <input type="hidden" name="training_id" id="t_id">
              </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary btn-block waves-effect">Save changes</button>
              <button type="button" class="btn btn-default btn-block btn-pure waves-effect" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
</form>