<div class="panel-group" id="exampleAccordionDefault" aria-multiselectable="true" role="tablist">
@if(count($discussions)>0)
@foreach($discussions as $discussion)
                  <div class="panel panel-info">
                    <div class="panel-heading" id="exampleHeadingDefaultThree{{$discussion->id}}" role="tab">
                      <a class="panel-title collapsed" data-toggle="collapse" href="#exampleCollapseDefaultThree{{$discussion->id}}" data-parent="#exampleAccordionDefault{{$discussion->id}}" aria-expanded="false" aria-controls="exampleCollapseDefaultThree{{$discussion->id}}">
                     {{$discussion->title}} @ {{$discussion->created_at->diffForHumans()}}
                    </a>
                    </div>
                  
               
                    <div class="panel-collapse collapse" id="exampleCollapseDefaultThree{{$discussion->id}}" aria-labelledby="exampleHeadingDefaultThree{{$discussion->id}}" role="tabpanel">
                      <div class="panel-body">
                       {!!  $discussion->discussion !!}
                          <table class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>S/n</th>
                                  <th>KPI/Goals</th>
                                  <th>Action/ Status Update</th>
                                  <th>Challenge</th>
                                  <th>Comments/Discussions</th>
                                  <th>Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              @foreach($discussion->discussion_details as $discussion_detail)
                              <tr>
                                  <td>{{$loop->iteration}}</td>
                                  <td>{{$discussion_detail->evaluation_detail->key_deliverable}}</td>
                                  <td>{{$discussion_detail->action_update}}</td>
                                  <td>{{$discussion_detail->challenges}}</td>
                                  <td>{{$discussion_detail->comment}}</td>
                                  <td><button class="btn btn-primary btn-xs " onclick="EditPDD({{$discussion_detail}},{{$discussion_detail->evaluation_detail}})" >Edit discussion</button></td>
                              </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>

                    </div>
                   
                  </div>
                  
                @endforeach
                </div>
         @endif