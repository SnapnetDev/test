 <div class="modal fade modal-super-scaled" id="addkpi" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="modaltitle">Add KPI</h4>
                        </div>
                        <div class="modal-body">
                        <form id="kpiform">
                          <table class="table table-striped">

                                <tr>
                                    <td> Deliverable<br></td>
                                    <td> <textarea required class="form-control" rows="3" id="deliverables" ></textarea></td>

                                </tr>
                                 <tr>
                                    <td>Target Weight</td>
                                    <td> <input required type="number" min="0"  id="targetweight" class="form-control"></td>

                                </tr>
                                <tr>
                                    <td>Target Amount</td>
                                    <td> <input required type="number" min="0"   id="targetamount" class="form-control"></td>

                                </tr>
                                 <tr>
                                    <td>Comment</td>
                                    <td><textarea required="" class="form-control" rows="3" id="comment" ></textarea></td>
                                    <input type="hidden" id="assigned_to" value="{{$employee->id}}">
                                </tr> 

                                  <tr>
                                    <td>Assigned To  </td>
                                    <td>

                                      <select id="department_assigned" class="form-control">

                                      <option value="this_employee">{{$employee->name}}</option>
                                      @foreach($departments as $department)
                                      <option {{ $employee->department == $department->id ? 'selected' : '' }} value="{{$department->id}}">{{$department->name}}</option>
                                      @endforeach
                                    </select> 
                                    </td>
                                    
                                </tr> 

                                 <tr  style="display: none;">
                                <td>Quarter</td>
                                <td>
                                <select class="form-control" id="kpi_quarter" style="display: none;">
                                  <option value="0">-Select Quarter-</option>
                                  @for($i = 1; $i <= $employee->getquarter(); $i++)
                                   <option value="{{$i}}"  @if(isset($_GET['quarter']) && $_GET['quarter']==$i)  selected @elseif(\Auth::user()->getquarter()==$i) selected @endif  >{{ $employee->quarterName($i) }} Quarter </option>    
                                  @endfor
                                </select>
                                </td>
                                </tr>
                          </table>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" id="addbtn">Add Kpi</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>