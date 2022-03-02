  


    <div class="modal fade modal-primary modal-3d-slit" id="viewreport{{$kpi->id}}" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1" aria-hidden="true">
             
             <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title">Progress Report</h4>
                </div>
                <div class="modal-body">
                  @if(count($kpi->progressreport)>0)
                  <table class="table table-striped">
                   <tr>
                    <td>Report</td>
                    <td>Amount Achieved</td>

                    <td> Duration</td>
                    <td>Status </td>
                    <td>Action</td> 
                  </tr>
                  @foreach($kpi->progressreport as $report)
                  <tr>
                    <td>
                        {{$report->report}}
                     
                    </td>
                    <td> <span class="tag tag-primary">{{$report->achievedamount}} </span></td>

                    <td> {{date('Y-m-d',strtotime($report->from))}} <b style="font-weight:bold">to</b> {{date('Y-m-d',strtotime($report->to))}} </td>

                    <td>{!! $report->resolve_status !!}</td>
                    <td>

                      <div class="btn-group" role="group" data-toggle="tooltip" data-original-title="Click to dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-effect waves-light waves-round" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                          <i class="icon md-apps" aria-hidden="true"></i>
                        </button>
                        <input type="hidden" id="realComment{{$report->id}}" value="{{$report->reportcomment}}">
                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                          <a class="dropdown-item" onclick="loadcbox('{{$report->id}}','{!! $report->reportcomment!!}','{{$kpi->deliverable}}')" data-toggle="modal" data-target="#commentbox" href="javascript:void(0)" role="menuitem">View/Add Comment</a>
<!-- 
                          <a class="dropdown-item" href="javascript:void(0)" role="menuitem">Add Kpi</a> -->
                        </div>
                      </div> 
                    </td>
                  </tr>
                  @endforeach
                </table>
                @else

                <p class="text-success text-md"> No Report Yet </p>
                @endif
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade modal-primary" id="commentbox" aria-labelledby="exampleModalPrimary" role="dialog" tabindex="-1" aria-hidden="true" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" >Line Manager's Comment</h4>
              </div>
              <form id="savecomment">
                <div class="modal-body">

                    {{-- {{Auth::user()->role->permissions->contains('constant', 'edit_performance')}} --}}
                  <textarea placeholder="LineManager's comment goes here"  {{((Auth::user()->role->permissions->contains('constant', 'edit_performance') || Auth::user()->role->permissions->contains('constant', 'add_kpi')) && Auth::user()->id!=Auth::user()->id) ? '' : 'readonly'}} class="form-control" id="reportcomment" rows="6"></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button  class="btn btn-primary {{Auth::user()->role->permissions->contains('constant', 'edit_performance')? '' : 'hide'}}" type="submit" >Save Comment</button>
                </div>
              </form>
            </div>
          </div>
        </div>
