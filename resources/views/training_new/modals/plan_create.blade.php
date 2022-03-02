<form action="{{ route('process.action.command',['create-training-plan']) }}" method="post">

    @csrf


<div id="create-training-plan" class="modal fade" role="dialog">
    <div class="modal-dialog modal-info modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Training Plan</h4>
            </div>
            <div class="modal-body">


                <div class="col-md-12">


                    <training-plan-form
                            training_groups="[]"
                            year_of_training="{{ date('Y') }}"
                            dep_id=""
                            groups="{{ json_encode($vars['groups']) }}"
                            departments='{{ json_encode($vars['departments']) }}'
                            roles='{{ json_encode($vars['roles']) }}'
                            role_id=""
                            locked="0"
                            name=""  
                            cost_per_head = "0" 
                            number_of_enrollees = "0" 
                    ></training-plan-form>




















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
