<script id="budget-template" type="text/html">

  <table class="table table-striped">

      <tr>
          <th>
              Department
          </th>
          <th>
              Name
          </th>
          <th>
              Allocation
          </th>
          <th>
              Year
          </th>
          {{--<th>--}}
              {{--Status--}}
          {{--</th>--}}
          <th>
              Actions
          </th>
      </tr>

  </table>

</script>



<div id="budget" class="modal fade" role="dialog">

        <div class="modal-dialog modal-info modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Training Budget By Department</h4>
                </div>
                <div class="modal-body">

                    <div class="col-md-12" align="right">
                        <a style="margin-bottom: 11px;" href="#" data-create-form class="btn btn-sm btn-primary" >Upload Budget</a>
                    </div>

                    <div id="budget-outlet" class="col-md-12">

                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>

</div>

@include('training_new.crud.budget.create')
@include('training_new.crud.budget.update')