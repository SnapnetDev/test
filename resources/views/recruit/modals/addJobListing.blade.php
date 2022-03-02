<div class="modal fade" id="addJoblistingModal" aria-labelledby="examplePositionSidebar" role="dialog" aria-hidden="true" style="display: none;">
  <form id="addJobListingForm">
  <div class="modal-dialog modal-sidebar modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">List Job</h4>
      </div>
      <div class="modal-body">
        @csrf
        <div class="form-group">
          <label class="example-title" for="target">Posting type</label>
          
          <select required="" name="target" style="width:100%;" id="target" class="form-control " >
            <option  value='1'>Internal</option>
            <option  value='2'>External</option>
          </select>
        </div>
         <div class="form-group ij_cont" id="department_cont" >
          <label class="form-control-label" for="department_id">Department</label>
          <select class="form-control ij_elt" id="department_id" name="department_id"  onchange="departmentChange(this.value);" required>
            @forelse($departments as $department)
            <option value="{{$department->id}}" >{{$department->name}}</option>
            @empty
            <option value="0">Please Create a department</option>
            @endforelse
          </select>
        </div>

        <div class="form-group ij_cont" id="job_cont" >
          <label class="form-control-label" for="jobroles">Job Role</label>
          <select class="form-control ij_elt" id="jobroles" name="job_id" required>
            @forelse($jobs as $job)
            <option value="{{$job->id}}" >{{$job->title}}</option>
            @empty
            <option value="0">Please Create a job role in department</option>
            @endforelse
          </select>
        </div>
        <div class="form-group ej_cont" id="">
           <label class="example-title" for="description">Job Title</label>
         <input type="text" name="title" required id="title" class="form-control ej_elt">
        </div>
        <div class="form-group ej_cont" id="">
           <label class="example-title" for="description">Job Reference Number</label>
         <input type="text" name="job_ref" required id="job_ref" class="form-control ej_elt">
        </div>

        <div class="form-group ej_cont" id="">
           <label class="example-title" for="description">Job Description</label>
          <textarea name="description" id="description" class="form-control ej_elt summernote" rows="6"></textarea>
        </div>
        <div class="form-group ej_cont" id="">
           <label class="example-title" for="experience">Experience</label>
          <textarea name="experience" id="experience" class="form-control  summernote" rows="6"></textarea>
        </div>
        <div class="form-group ej_cont" id="">
           <label class="example-title" for="skills">Skills</label>
          <textarea name="skills" id="skills" class="form-control  summernote" rows="6"></textarea>
        </div>

        <div class="form-group ij_cont">
          <label class="example-title" for="jtype">Display</label>
          
          <select required name="jtype" style="width:100%;" id="jtype" class="form-control ij_elt" >
            <option  value='1'>Internal</option>
            <option  value='2'>Public</option>
              <option  value='3'>Internal and Public</option>
          </select>
        </div>
        <div class="form-group">
          <label class="example-title" for="type">Job Level</label>
          
          <select required="" name="level" style="width:100%;" id="level" class="form-control " >
            <option  value='1'>Graduate Trainee</option>
            <option  value='2'>Entry Level</option>
              <option  value='3'>Non Manager</option>
              <option  value='4'>Manager</option>
          </select>
        </div>
         <div class="form-group ej_cont" >
          <label class="form-control-label" for="select">Location (Country)</label>
          <select class="form-control ej_elt" id="country" name="country"  style="width: 100%;">
           
            <option value="">Select Country</option>
            
          </select>
          </div>
          <div class="form-group ej_cont">
            <label class="form-control-label" for="select">Location (State/Region)</label>
            <select class="form-control ej_elt" id="state" name="state"  style="width: 100%;">
              
               <option value="">Select Country</option>
                
            </select>
          </div>
        <div class="form-group">
          <label class="example-title" for="salary_from">Salary </label>
           <div class=" input-group" >
                    <input type="number" class=" form-control"  name="salary_from" placeholder="From Amount" />
                    <span class="input-group-addon">to</span>
                    <input type="number" class="form-control"  name="salary_to"placeholder="To Amount" />
                </div>
        </div>
        <div class="form-group">
          <label class="example-title" for="salary_from"> Experience (Years)</label>
           <div class=" input-group" >
                    <input type="number" class=" form-control" i  name="experience_from" placeholder="From Year" />
                    <span class="input-group-addon">to</span>
                    <input type="number" class="form-control"  name="experience_to" placeholder="To Year" />
                </div>
          
        </div>
        <div class="form-group">
          <label class="example-title" for="expires">Expires</label>
          
          <input type="text" name="expires" class="form-control datepicker">
        </div>
        <div class="form-group">
           <label class="example-title" for="requirements">Extra Requirements</label>
          <textarea name="requirements" id="requirements" class="form-control summernote" rows="6"></textarea>
        </div>
                <input type="hidden" name="type" value="save_listing">   
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-block waves-effect">Save changes</button>
        <button type="button" class="btn btn-default btn-block btn-pure waves-effect" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
  </form>
</div>