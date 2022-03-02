<form class="form-horizontal" method="POST" action="{{ route('add_budget_to_training') }}">
      {{ csrf_field() }}
      <div class="modal fade" id="addBudgetTrainingModal" aria-labelledby="examplePositionSidebar" role="dialog"  aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sidebar modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title">Select Budget</h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label class="example-title" for="trainee_id">Budget</label>
                  <select class="form-control select2" name="budget_id" id="budgets" style="width: 100%">
                    <option value="">  </option>
                    @if($budgets)
                        @foreach($budgets as $budgets)
                            <option value="{{$budgets->id}}"> {{$budgets->purpose}} </option>                            
                        @endforeach
                    @endif
                  </select>
                </div>
                <input type="text" name="training_id" id="training_id">
              </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary btn-block waves-effect">Save changes</button>
              <button type="button" class="btn btn-default btn-block btn-pure waves-effect" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
</form>