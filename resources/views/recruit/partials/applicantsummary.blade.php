
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="exampleModalTabs">Applicant Details</h4>
                        </div>
                        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                          <li class="nav-item" role="presentation"><a class="nav-link active" data-toggle="tab" href="#bio" aria-controls="bio" role="tab" aria-expanded="true">Bio</a></li>
                          <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#edu" aria-controls="exampleLine2" role="tab" aria-expanded="false">Education</a></li>
                          <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#exampleLine3" aria-controls="exampleLine3" role="tab" aria-expanded="false">Documents</a></li>
                          <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#phistory" aria-controls="exampleLine4" role="tab" aria-expanded="false">Promotion History</a></li>
                           <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#skill" aria-controls="exampleLine4" role="tab" aria-expanded="false">Skills</a></li>
                        </ul>
                        <div class="modal-body">
                          <div class="tab-content">
                            <div class="tab-pane active" id="bio" role="tabpanel" aria-expanded="true">
                              
                                <img width="100" height="auto" class="img img-responsive img-rounded" src="{{ File::exists('storage/avatar'.$user->image)?asset('storage/avatar'.$user->image):($user->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}" alt="">
                              
                              <ul class="list-group list-group-bordered ">
                                  <li class="list-group-item bg-grey-300">Information</li>
                                <li class="list-group-item ">
                                  <strong>Name:</strong> {{ $user->name }}
                                </li>
                                <li class="list-group-item ">
                                  <strong>Company:</strong> {{ $user->company->name }}
                                </li>
                                <li class="list-group-item ">
                                  <strong>Position:</strong>  @if(count($user->jobs)>0){{$user->jobs()->latest()->first()->title}} @endif
                                </li>
                               <li class="list-group-item ">
                                 <strong>Grade:</strong> @if(count($user->promotionHistories())>0)        
            
                  {{($user->promotionHistories()->latest()->first()->grade)?$user->promotionHistories()->latest()->first()->grade->level:''}}
                  @endif
                                </li>
                                <li class="list-group-item ">
                                  <strong>Staff ID:</strong> {{ $user->emp_num  }}
                                </li>
                                <li class="list-group-item ">
                                  <strong>Email:</strong> {{ $user->email }}
                                </li>
                                <li class="list-group-item ">
                                  <strong>Gender:</strong> {{$user->sex=='M'?'Male':'Female'}}
                                </li>
                                 <li class="list-group-item ">
                                  <strong>Date of Birth:</strong> {{date("F j, Y",strtotime($user->dob))}}
                                </li>
                                <li class="list-group-item ">
                                 <strong>Joined:</strong> {{date("F j, Y",strtotime($user->hiredate))}} ({{$user->hiredate->diffForHumans()}})
                                </li>
                                <li class="list-group-item ">
                                 <strong>Qualification Status:</strong><span class="{{$qualification>0?'text-success':'text-danger'}}">{{$qualification>0?'Employee has the minimum qualification required for the Job.':'Employee does not have the minimum qualification required for the Job.'}} </span>
                                </li>

                              </ul>
                            </div>
                            <div class="tab-pane" id="edu" role="tabpanel" aria-expanded="false">
                              <table id="exampleTablePagination" data-toggle="table" 
                  data-query-params="queryParams" data-mobile-responsive="true"
                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                      <tr>
                         <th>Title:</th>
                        <th >Qualification:</th>
                        <th >Year:</th>
                        <th >Institution:</th>
                        <th >CGPA/ Grad / Score:</th>
                        <th >Discipline:</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($user->educationHistories as $history)
                      <tr>
                       <td>{{$history->title}}</td>
                       @if($history->qualification_id>0)
                        <td>{{$history->qualification->name}}</td>
                        @else
                        <td></td>
                        @endif
                        <td>{{$history->year}}</td>
                        <td>{{$history->institution}}</td>
                        <td>{{$history->grade}}</td>
                        <td>{{$history->course}}</td>
                        
                      </tr>
                      @empty
                      @endforelse
                      
                    </tbody>
                  </table>
                            </div>
                            <div class="tab-pane" id="exampleLine3" role="tabpanel" aria-expanded="false">
                             Employee Does not have documents
                            </div>
                            <div class="tab-pane" id="phistory" role="tabpanel" aria-expanded="false">
                              <table id="exampleTablePagination" data-toggle="table" 
                                  data-query-params="queryParams" data-mobile-responsive="true"
                                  data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th >Old Grade:</th>
                                        <th >New Grade:</th>
                                        <th >Approved By</th>
                                        <th >Approved On</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @forelse($user->promotionHistories as $phistory)
                                      <tr>

                                        <td>{{$phistory->oldgrade?$phistory->oldgrade->level:''}}</td>
                                        <td>{{$phistory->grade?$phistory->grade->level:''}}</td>
                                        <td>{{$phistory->approver?$phistory->approver->name:''}}</td>
                                        <td>{{date("F j, Y", strtotime($phistory->approved_on))}}</td>
                                      </tr>
                                      @empty
                                      @endforelse
                                      
                                    </tbody>
                                  </table>
                            </div>
                            <div class="tab-pane" id="skill" role="tabpanel" aria-expanded="false">
                             <div class="example text-xs-center max-width">
                                <canvas id="skillComChart" height="200"></canvas>
                              </div>
                                  @if($user->skills)
                                   <ul class="list-group list-group-bordered ">
                                    @php
                                      $sn=1;
                                    @endphp
                                     
                                   <li class="list-group-item bg-grey-300">Skills</li>
                                   @foreach($user->skills as $lskill)
                                    <li class="list-group-item ">{{$sn}}.  {{strtoupper($lskill->name)}} - {{$lskill->pivot->competency->proficiency}}</li>
                                  @php
                                    $sn++;
                                  @endphp
                                  @endforeach
                                   </ul>
                                  @endif
             
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                 

                  <script type="text/javascript">
                    
                     var ctx = document.getElementById('skillComChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'radar',

    // The data for our dataset
    data: {
        labels: [  @foreach($joblisting->job->skills as $skill)"{{strtoupper($skill->name)}}", @endforeach],
        pointLabelFontSize: 14,
        datasets: [{
            label: "Skill for Job",
             pointRadius: 4,
        borderDashOffset: 2,
            backgroundColor: 'rgba(98, 168, 234, 0.15)',
            borderColor: 'rgba(0, 0, 0,0)',
             pointBackgroundColor: Config.colors("primary", 600),
        pointBorderColor: "#fff",
        pointHoverBackgroundColor: "#fff",
        pointHoverBorderColor: Config.colors("primary", 600),
            data: [ @foreach($joblisting->job->skills as $skill){{$skill->pivot->competency->id}}, @endforeach],
        },
         {
        label: "Employee Skills",
        pointRadius: 4,
        borderDashOffset: 2,
        backgroundColor: "rgba(250,122,122,0.25)",
        borderColor: "rgba(0,0,0,0)",
        pointBackgroundColor: Config.colors("red", 500),
        pointBorderColor: "#fff",
        pointHoverBackgroundColor: "#fff",
        pointHoverBorderColor: Config.colors("red", 500),
        data: [@foreach($joblisting->job->skills as $skill) 
                  @if($user->skills->count()>0) 
                  @if($user->skills->contains('id', $skill->id))
                    @foreach($user->skills as $uskill) 
                      @if($skill->id==$uskill->id)
                      {{$uskill->pivot->competency->id}},
                       @endif
                     @endforeach
                     @else
                      0,
                     @endif
                   @endif 
               @endforeach]
      }]
    },

    // Configuration options go here
    options: {
      responsive: true,
        scale: {
          ticks: {
            beginAtZero: true
          }
        }
    }
});
                  </script>