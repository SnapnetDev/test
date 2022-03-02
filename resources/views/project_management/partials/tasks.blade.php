<div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Project Tasks</h3>
                  <div class="panel-actions">
                    <div class="panel-actions">
                      <button class="btn btn-info" data-toggle="modal" data-target="#addLeaveModal">Add Task</button>

                    </div>
                  </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width:70%">Name</th>
                          <th style="width:70%">Status</th>
                          <th style="width:30%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($project->tasks as $task)
                          <tr>
                          <th style="width:20%">{{$task->name}}</th>
                           <th style="width:20%"><span class=" tag tag-outline  {{$task->status==1?'tag-success':'tag-warning'}}">{{$task->status==1?'completed':'pending'}}</span></th>
                          <td style="width:80%"><button class="btn btn-danger">Remove</button></td>
                        </tr>
                        @endforeach
                        
                      </tbody>
                        
                        
                        
                      </table>
                </div>
          </div>