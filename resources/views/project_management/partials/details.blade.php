<div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Project Details</h3>
                  <div class="panel-actions">
                    <div class="panel-actions">
                      @if(Auth::user()->role->permissions->contains('constant', 'edit_project'))
                      @if ($project->status==0)
                       <button class="btn btn-info" data-toggle="modal" data-target="#editProjectDetailsModal">Edit Project Details</button>
                      @endif
                      @endif
                      

                    </div>
                  </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tr>
                          <th style="width:20%">Project Name</th>
                          <td style="width:80%">{{$project->name}}</td>
                        </tr>
                        <tr>
                          <th>Project Manager</th>
                          <td>{{$project->project_manager->name}}</td>
                        </tr>
                        <tr>
                          <th>Project Code</th>
                          <td>{{$project->code}}</td>
                        </tr>
                        <tr>
                          <th>Project Status</th>
                          <td><span class=" tag tag-outline  {{$project->status==1?'tag-success':'tag-warning'}}">{{$project->status==1?'completed':'pending'}}</span></td>
                        </tr>
                        <tr>
                          <th>Start Date</th>
                          <td>{{date("F j, Y", strtotime($project->start_date))}}</td>
                        </tr>
                        <tr>
                          <th>Estimated Ending Date</th>
                          <td>{{date("F j, Y", strtotime($project->end_est_date))}}</td>
                        </tr>
                        <tr>
                          <th>Proposed Duration</th>
                          <td>{{\Carbon\Carbon::parse($project->start_date)->diffInDays($project->end_est_date)}} Days</td>
                        </tr>
                        <tr>
                          <th>Actual Ending Date</th>
                          <td>{{date("F j, Y", strtotime($project->actual_ending_date))}}</td>
                        </tr>
                        <tr>
                          <th>Actual Duration</th>
                          <td>{{$project->actual_ending_date==null?'0':\Carbon\Carbon::parse($project->end_est_date)->diffInDays($project->actual_ending_date)}} Days</td>
                        </tr>
                        <tr>
                          <th>Remark</th>
                          <td>{{$project->remark}}</td>
                        </tr>
                        <tr>
                          <th>Timesheet Report</th>
                          <td><a class="btn btn-primary" href="{{url('projects/project_hours_report')}}?project_id={{$project->id}}">View Report</a></td>
                        </tr>
                        
                      </table>
                </div>
          </div>