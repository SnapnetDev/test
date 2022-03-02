<div class="modal fade" id="editJobListingModal" aria-labelledby="examplePositionSidebar" role="dialog" tabindex="-1" aria-hidden="true" style="display: none;">
   <form id="editJobListingForm">
  <div class="modal-dialog modal-sidebar modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Edit Job Listing</h4>
      </div>
      <div class="modal-body">
         @csrf
         <input type="hidden" name="target" value="1">
        <div class="form-group">
          <label class="example-title" for="editjtype">Display</label>
          
          <select required name="jtype" style="width:100%;" id="editjtype" class="form-control " >
            <option  value='1'>Internal</option>
            <option  value='2'>Public</option>
              <option  value='3'>Internal and Public</option>
          </select>
        </div>
        <div class="form-group">
          <label class="example-title" for="editjlevel">Job Level</label>
          
          <select required name="level" style="width:100%;" id="editjlevel" class="form-control " >
            <option  value='1'>Graduate Trainee</option>
            <option  value='2'>Entry Level</option>
              <option  value='3'>Non Manager</option>
              <option  value='4'>Manager</option>
          </select>
        </div>
        <div class="form-group">
          <label class="example-title" for="salary_from">Salary </label>
           <div class=" input-group" >
                    <input type="number" class=" form-control" id="editjsalary_from" name="salary_from" placeholder="From Amount" />
                    <span class="input-group-addon">to</span>
                    <input type="number" class="form-control" id="editjsalary_to" name="salary_to"placeholder="To Amount" />
                </div>
          
          
        </div>
      
        <div class="form-group">

          <label class="example-title" for="salary_from"> Experience (Years)</label>
           <div class=" input-group" >
                    <input type="number" class=" form-control" id="editjexperience_from"  name="experience_from" placeholder="From Year" />
                    <span class="input-group-addon">to</span>
                    <input type="number" class="form-control" id="editjexperience_to" name="experience_to" placeholder="To Year" />
                </div>
          
        </div>
        
        <div class="form-group">
          <label class="example-title" for="expires">Expires</label>
          
          <input type="text" id="editjexpires" name="expires" class="form-control datepicker">
        </div>
        <div class="form-group">
           <label class="example-title" for="editjrequirements">Extra Requirements</label>
          <textarea name="requirements" id="editjrequirements" class="form-control summernote" rows="6"></textarea>
        </div>
             <input type="hidden" name="type" value="save_listing"> 
             <input type="hidden" id="editjlid" name="job_listing_id" >
              <input type="hidden" id="editjobid" name="job_id" >   
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-block waves-effect">Save changes</button>
        <button type="button" class="btn btn-default btn-block btn-pure waves-effect" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</form>
</div>