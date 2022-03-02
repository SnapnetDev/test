<script id="approval-template" type="text/html">

  <table class="table table-striped">

      <tr>
          <th>
              Stage
          </th>
          <th>
              Status
          </th>
          <th>
              Approved By
          </th>
          <th>
              Date Approved
          </th>
          <th>
              Created
          </th>
          <th>
              Actions
          </th>
      </tr>

  </table>

</script>



<div id="approval" class="modal fade" role="dialog">

        <div class="modal-dialog modal-info modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Approvals</h4>
                </div>
                <div class="modal-body">


                    <div id="approval-outlet" class="col-md-12">

                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>

</div>

@include('training_new.crud.approvals.update')