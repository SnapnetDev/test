
@php
 
    $disable2= ( in_array(Auth::user()->role->manages,['dr','all']) ||  Auth::user()->role->permissions->contains('constant', 'add_hr_comment') ) && ($employee->id!=Auth::user()->id) ? '' : 'disabled';
     $disable= $employee->id==Auth::user()->id ? '' : 'disabled';
@endphp
<div class="modal fade modal-3d-slit in" id="review" aria-labelledby="exampleModalTitle" role="dialog" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="progressdel"></h4>
      </div>
      <div class="modal-body">
        <form id="addKpiReport">

         <table class="table table-striped">
          <tr>
            <td> Progress Report</td>
            <td> <textarea {{$disable}} required id="progressreport" class="form-control" rows="3"></textarea></td>
          </tr>
          <tr>
          </table>
          <div class="example">
                  <div class="input-daterange" data-plugin="datepicker">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="icon md-calendar" aria-hidden="true"></i>
                      </span>
                      <input type="text" class="form-control" readonly="" placeholder="from" name="start">
                    </div>
                    <div class="input-group">
                      <span class="input-group-addon">to</span>
                      <input type="text" class="form-control" readonly="" placeholder="to" name="end">
                    </div>
                  </div>
                </div>

          <table class="table table-striped">
             <tr>
              <td>
              Amount Achieved</td>
              <td>
               <input {{$disable}} required="" class="form-control" type="number" min="0"  id="achievedamount" /></td>
             </tr>
             <tr>
              <tr>
                <td>
                Deliverable (In-Progress/Completed)</td>
                <td>
                   <input  {{$disable}} type="checkbox" name="status" data-plugin="switchery" />
                </td>
              </tr>
               
             <tr>
               <td>
               Comment</td>
               <td>
                 <textarea {{$disable2}} placeholder="to be completed by linemanager"    class="form-control" rows="3" id="commentrep"> </textarea>
               </td>
             </tr> 
           </table>
         </div>
         <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="submitReport"  type="submit" class="btn btn-success"> Submit </button> 
        </form>
        
      </div>
    </div>
  </div>
</div>



</script>