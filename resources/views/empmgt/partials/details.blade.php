<div class="row">
        <div class="col-md-12 col-xs-12">
        	<!-- Panel -->
          <div class="panel">
            <div class="panel-body nav-tabs-animate nav-tabs-horizontal" data-plugin="tabs">
              <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                <li class="nav-item" role="presentation"><a class="active nav-link" data-toggle="tab" href="#personal"
                  aria-controls="activities" role="tab">Personal Information </a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#academics" aria-controls="profile"
                  role="tab">Academic History</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#dependants" aria-controls="messages"
                  role="tab">Dependants</a></li>
                  <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#skills" aria-controls="messages"
                  role="tab">Skills</a></li>
                  <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#experience" aria-controls="messages"
                  role="tab">Work Experience</a></li>
                  <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#history" aria-controls="messages"
                  role="tab">Promotion History</a></li>
                
              </ul>
              <div class="tab-content">
                <div class="tab-pane active animation-slide-left" id="personal" role="tabpanel">
                	<br>
                  <form enctype="multipart/form-data" id="emp-data" method="POST" onsubmit="">
                  	@csrf
                  	<input type="hidden" name="user_id" value="{{$user->id}}">
                  	<div class="row">
                  	<div class="col-md-4 col-lg-4">
                  		<div class="form-group">
					        <label>Upload Image</label>
					        <img class="img-circle img-bordered img-bordered-blue text-center" width="150" height="150" src="{{ asset('global/portraits/2.jpg') }}" alt="..." id='img-upload'>
					      
					        <div class="input-group">
					            <span class="input-group-btn">
					                <span class="btn btn-default btn-file">
					                    Browse… <input type="file" id="imgInp" name="avatar" accept="image/*">
					                </span>
					            </span>
					            <input type="text" class="form-control" readonly>
					        </div>
					    </div>
                  		
                  			
                  		
                  		
                  	</div>

                  	<br>
                  	<div class="col-md-4">
              		<div class="form-group form-material" data-plugin="formMaterial">
	                  <label class="form-control-label" for="inputText">Name</label>
	                  <input type="text" class="form-control" id="name" value="{{$user->name}}" name="name" placeholder="Name"
	                   required />
	                </div>
                  	</div>
                  	<div class="col-md-4">
              		<div class="form-group form-material" data-plugin="formMaterial">
	                  <label class="form-control-label" for="inputText">Employee Number </label>
	                  <input type="text" class="form-control" id="emp_num" value="{{$user->emp_num}}" name="emp_num" placeholder="Employee Number"
	                   required />
	                </div>
                  	</div>
                  	<div class="col-md-4">
                  		<div class="form-group form-material" data-plugin="formMaterial">
	                  <label class="form-control-label" for="inputText">Email</label>
	                  <input type="email" class="form-control" id="email" value="{{$user->email}}" name="email" placeholder="Email"
	                   required />
	                </div>

                  	</div>
                  	<div class="col-md-4">
                  		<div class="form-group form-material" data-plugin="formMaterial">
	                  <label class="form-control-label" for="inputText">Phone Number</label>
	                  <input type="text" class="form-control" id="phone" value="{{$user->phone}}" name="phone" placeholder="Phone Number" required 
	                  />
	                </div>

                  	</div>
                  </div>

                  	<hr>
                  	<div class="row">
                  		<div class="col-md-4">
              			<div class="form-group form-material" data-plugin="formMaterial">
		                  <label class="form-control-label" for="select">Sex</label>
		                  <select class="form-control" id="sex" name="sex">
		                    <option value="M" {{$user->sex=='M'?'selected':''}}>Male</option>
		                    <option value="F" {{$user->sex=='F'?'selected':''}}>Female</option>
		                  </select>
		                </div>
                  		</div>
                  		<div class="col-md-4">
                  			<div class="form-group form-material" data-plugin="formMaterial">
		                  <label class="form-control-label" for="select">Marital Status</label>
		                  <select class="form-control" id="marital_status" name="marital_status">
		                    <option>Single</option>
		                    <option>Married</option>
		                    <option>Divorced</option>
		                  </select>
		                </div>
                  		</div>
                  		<div class="col-md-4">
                  			<div class="form-group form-material" data-plugin="formMaterial">
			                  <label class="form-control-label" for="inputText">Date of Birth</label>
			                  <input type="text" class="form-control datepicker"  id="dob" name="dob" placeholder="Phone Number"
			                   required  value="{{date("m/d/Y",strtotime($user->dob))}}" />
			                </div>
                  		</div>
                  	</div>
                  	<div class="row">
                  		<div class="col-md-12">
              			<div class="form-group form-material" data-plugin="formMaterial">
		                   <label class="form-control-label" for="inputText">Address</label>
	                   <textarea class="form-control" id="address" name="address" rows="3">{{$user->address}}</textarea>
		                </div>
                  		</div>
                  		
                  	</div>
                  	
                  
                  	<div class="row">
                  		<div class="col-md-4">
              			<div class="form-group form-material" data-plugin="formMaterial">
		                  <label class="form-control-label" for="select">Location</label>
		                  <select class="form-control" id="location_id" name="location_id">
                        
		                  </select>
		                </div>
                  		</div>
                  		<div class="col-md-4">
              			<div class="form-group form-material" data-plugin="formMaterial">
		                  <label class="form-control-label" for="select">Staff Category</label>
		                  <select class="form-control" id="staff_category_id" name="staff_category_id">
		                    
		                  </select>
		                </div>
                  		</div>
                  		<div class="col-md-4">
              			<div class="form-group form-material" data-plugin="formMaterial">
		                  <label class="form-control-label" for="select">Position</label>
		                  <select class="form-control" id="dept_id" name="dept_id">
		                   
		                  </select>
		                </div>
                  		</div>
                  		
                  		
                  	</div>
                  		<div class="row">
                  		<div class="col-md-4">
                  			<div class="form-group form-material" data-plugin="formMaterial">
			                  <label class="form-control-label" for="inputText">Hire Date</label>
			                  <input type="text" class="form-control datepicker"  id="hiredate" name="hiredate" placeholder="Hire Date"
			                  />
			                </div>
                  		</div>
                  		<div class="col-md-4">
              			<div class="form-group form-material" data-plugin="formMaterial">
		                  <label class="form-control-label" for="select">Job Role</label>
		                  <select class="form-control" id="job_id" name="job_id">
		                    <option>Branch 1</option>
		                    <option>Branch 2</option>
		                  </select>
		                </div>
                  		</div>
                  		<div class="col-md-4">
              			<div class="form-group form-material" data-plugin="formMaterial">
		                  <label class="form-control-label" for="select">Grade</label>
		                  <input type="text" class="form-control " disabled  id="inputText" name="inputText" placeholder="Grade 14" 
			                  />
		                </div>
                  		</div>
                  		
                  		
                  	</div>
                  	
                  	
                  	<br>
                  	<button type="submit" class="btn btn-primary btn-lg">Save</button>
                  </form>
                  
                </div>
                <div class="tab-pane animation-slide-left" id="academics" role="tabpanel">
                	<br>
                	<button class="btn btn-primary ">Add Qualification</button>
                  <table id="exampleTablePagination" data-toggle="table" 
                  data-query-params="queryParams" data-mobile-responsive="true"
                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                      <tr>
                        <th >Qualification:</th>
                        <th >Year:</th>
                        <th >Institution:</th>
                        <th >CGPA/ Grad / Score:</th>
                        <th >Discipline:</th>
                        <th >Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@forelse($user->educationHistories as $history)
                    	<tr>
                    		<td>{{$history->qualification}}</td>
                    		<td>{{date('Y',strtotime($history->year))}}</td>
                    		<td>{{$history->institution}}</td>
                    		<td>{{$history->grade}}</td>
                    		<td>{{$history->course}}</td>
                    		<td></td>
                    	</tr>
                    	@empty
                    	@endforelse
                    	
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane animation-slide-left" id="dependants" role="tabpanel">
                	<br>
                	<button class="btn btn-primary ">Add Dependant</button>
                  <table id="exampleTablePagination" data-toggle="table" 
                  data-query-params="queryParams" data-mobile-responsive="true"
                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                      <tr>
                        <th >Name:</th>
                        <th >Date of Birth:</th>
                        <th >Email:</th>
                        <th >Phone Number:</th>
                        <th >Relationship:</th>
                        <th >Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@forelse($user->dependants as $dependant)
                    	<tr>
                    		<td>{{$dependant->name}}</td>
                    		<td>{{date("F j, Y", strtotime($dependant->dob))}}</td>
                    		<td>{{$dependant->email}}</td>
                    		<td>{{$dependant->phone_num}}</td>
                    		<td>{{$dependant->relationship}}</td>
                    		<td></td>
                    	</tr>
                    	@empty
                    	@endforelse
                    	
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane animation-slide-left" id="skills" role="tabpanel">
                	<br>
                	<button class="btn btn-primary ">Add Skill</button>
                  <table id="exampleTablePagination" data-toggle="table" 
                  data-query-params="queryParams" data-mobile-responsive="true"
                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                      <tr>
                        <th >Skill:</th>
                        <th >Experience (Years):</th>
                        <th >Rating:</th>
                        <th >Remarks:</th>
                        <th >Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@forelse($user->skills as $skill)
                    	<tr>
                    		<td>{{$skill->skill}}</td>
                    		<td>{{$skill->experience}}</td>
                    		<td>{{$skill->rating}}</td>
                    		<td>{{$skill->remark}}</td>
                    		<td></td>
                    	</tr>
                    	@empty
                    	@endforelse
                    	
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane animation-slide-left" id="experience" role="tabpanel">
                	<br>
                	<button class="btn btn-primary ">Add Employment History</button>
                  <table id="exampleTablePagination" data-toggle="table" 
                  data-query-params="queryParams" data-mobile-responsive="true"
                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                      <tr>
                        <th >Organization:</th>
                        <th >Position:</th>
                        <th >Start Date:</th>
                        <th >End Date:</th>
                        <th >Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@forelse($user->employmentHistories as $ehistory)
                    	<tr>
                    		<td>{{$ehistory->organization}}</td>
                    		<td>{{$ehistory->position}}</td>
                    		<td>{{date("F j, Y", strtotime($ehistory->start_date))}}</td>
                    		<td>{{date("F j, Y", strtotime($ehistory->end_date))}}</td>
                    		<td></td>
                    	</tr>
                    	@empty
                    	@endforelse
                    	
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane animation-slide-left" id="history" role="tabpanel">
                  <table id="exampleTablePagination" data-toggle="table" 
                  data-query-params="queryParams" data-mobile-responsive="true"
                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                      <tr>
                        <th >Organization:</th>
                        <th >Position:</th>
                        <th >Start Date:</th>
                        <th >End Date:</th>
                        <th >Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@forelse($user->employmentHistories as $ehistory)
                    	<tr>
                    		<td>{{$ehistory->organization}}</td>
                    		<td>{{$ehistory->position}}</td>
                    		<td>{{date("F j, Y", strtotime($ehistory->start_date))}}</td>
                    		<td>{{date("F j, Y", strtotime($ehistory->end_date))}}</td>
                    		<td></td>
                    	</tr>
                    	@empty
                    	@endforelse
                    	
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- End Panel -->

          
        </div>
        
      </div>