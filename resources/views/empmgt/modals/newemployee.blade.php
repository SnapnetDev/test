<!-- Modal -->
                  <div class="modal fade modal-info" id="addUserForm" aria-hidden="false" aria-labelledby="addUserForm"
                  role="dialog" >
                    <div class="modal-dialog  modal-top modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="exampleFillInModalTitle">New Employee</h4>
                        </div>
                         <form id="addNewUserForm" method="POST">
                        <div class="modal-body">
                         @csrf
                            <div class="row">
                              <div class="col-xs-12 col-xl-6 form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="first_name">First Name</label>
                                  <input type="text" class="form-control" id="first_name"  name="first_name" placeholder="First Name"
                                   required />
                                </div>
                              </div>
                              <div class="col-xs-12 col-xl-6 form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="middle_name">Middle Name</label>
                                  <input type="text" class="form-control" id="middle_name"  name="middle_name" placeholder="Middle Name"
                                    />
                                </div>
                              </div>
                              <div class="col-xs-12 col-xl-6 form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="last_name">Last Name</label>
                                  <input type="text" class="form-control" id="last_name"  name="last_name" placeholder="Last Name"
                                   required />
                                </div>
                              </div>
                              <div class="col-xs-12 col-xl-6 form-group">
                               <div class="form-group " >
                                  <label class="form-control-label" for="emp_num">Employee Number</label>
                                  <input type="text" class="form-control" id="emp_num"  name="emp_num" placeholder="Employee Number"
                                   required />
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-xs-12 col-xl-6 form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="email">Email</label>
                                  <input type="email" class="form-control" id="email"  name="email" placeholder="Email"
                                    />
                                </div>
                              </div>
                              <div class="col-xs-12 col-xl-6 form-group">
                               <div class="form-group " >
                                  <label class="form-control-label" for="phone">Phone Number</label>
                                  <input type="text" class="form-control" id="phone"  name="phone" placeholder="Phone Number"
                                    />
                                </div>
                              </div>
                              </div>
                            <div class="row">
                               <div class="col-xs-12 col-xl-6 form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="sex">Gender</label>
                                  <select class="form-control" id="sex" name="sex" required>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-xs-12 col-xl-6 form-group">
                                <div class="form-group">
                                  <h4 class="example-title">Date of Birth</h4>
                                   <input type="text"   placeholder="Date of Birth" name="dob"   class="form-control datepicker">
                                </div>
                              </div>
                              </div>
                            <div class="row">
                              <div class="col-xs-12 col-xl-6 ">
                               <div class="form-group " >
                                  <label class="form-control-label" for="grade">Role</label>
                                 <select id="grade_id" name="role_id" class="form-control select2" required style="width: 100%">
                                  @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                                  @endforeach
                                </select>
                                </div>
                              </div>
                              <div class="col-xs-12 col-xl-6 ">
                               <div class="form-group " >
                                  <label class="form-control-label" for="grade">Grade</label>
                                 <select id="grade_id" name="grade_id" class="form-control select2"  style="width: 100%">
                                  <option></option>
                                  @foreach($grades as $grade)
                                <option value="{{$grade->id}}">{{$grade->level}}</option>
                                  @endforeach
                                </select>
                                </div>
                              </div>
                              </div>
                            <div class="row">
                              <div class="col-xs-12 col-xl-6 form-group">
                               <div class="form-group " >
                                  <label class="form-control-label" for="branch_id">Branch</label>
                                  <select class="form-control select2"  name="branch_id"  style="width: 100%">
                                     @forelse($ncompany->branches as $branch)
                                    <option value="{{$branch->id}}" >{{$branch->name}}</option>
                                    @empty
                                    <option value="0">Please Create a branch</option>
                                    @endforelse
                                  </select>
                                </div>
                              </div>
                              <div class="col-xs-12 col-xl-6 form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="department_id">Department</label>
                                  <select class="form-control select2" name="department_id" onchange="departmentChange(this.value);" required style="width: 100%">
                                    @forelse($ncompany->departments as $department)
                                    <option value="{{$department->id}}" >{{$department->name}}</option>
                                    @empty
                                    <option value="0">Please Create a department</option>
                                    @endforelse
                                  </select>
                                </div>
                              </div>
                              </div>
                            <div class="row">
                              <div class="col-xs-12 col-xl-6 form-group">
                               <div class=" form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="jobroles">Job Role</label>
                                  <select class="form-control select2" id="jobroles" name="job_id" required style="width: 100%">

                                    <option value="0">Please select department</option>

                                  </select>
                                </div>
                              </div>
                              </div>
                               <div class="col-xs-12 col-xl-6 form-group">
                                <div class="form-group">
                                  <h4 class="example-title">Started</h4>
                                   <input type="text"  required placeholder="Started" name="started"   class="form-control datepicker">
                                </div>
                              </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                          <input type="hidden" name="company_id" value="{{$ncompany->id}}">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success">Save changes</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- End Modal -->
