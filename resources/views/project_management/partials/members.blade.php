<div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Project Members</h3>
                  <div class="panel-actions">
                    <div class="panel-actions">
                      @if(Auth::user()->role->permissions->contains('constant', 'create_project'))
                      <button class="btn btn-info" id="{{$project->id}}" onclick="prepareEditMData(this.id);">Add Members</button>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width:70%">Name</th>
                          <th style="width:30%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($project->project_members as $member)
                          <tr>
                          <th >{{$member->name}}</th>
                          <td ><button class="btn btn-danger">Remove</button></td>
                        </tr>
                        @endforeach
                        
                      </tbody>
                        
                        
                        
                      </table>
                </div>
          </div>