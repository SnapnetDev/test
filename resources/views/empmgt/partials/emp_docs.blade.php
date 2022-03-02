<div class="tab-pane animation-slide-left" id="documents" role="tabpanel">
    <div class="panel-body">

        <div class="table-responsive col-md-12">
            @if(count($documents)>0)
                <table class="table table-striped"  >
                    <thead class="bg-blue-grey-100">
                    <tr>
                        <th>
                        <span class="checkbox-custom checkbox-primary">
                          <input class="selectable-all"  type="checkbox">
                          <label></label>
                        </span>
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Owner
                        </th>
                        <th>
                            Category
                        </th>

                        <th>
                            Created
                        </th>
                        <th>
                            Expires
                        </th>
                        <th>
                            Last Viewed By
                        </th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($documents as $document)
                        <tr>
                            <td>
                      <span class="checkbox-custom checkbox-primary">
                        <input class="doclist" value="{{$document->id}}" id="delete{{$document->id}}" type="checkbox">
                        <label></label>
                      </span>
                            </td>
                            <td><a href="javascript:void(0)">{{$document->document_name}}</a>

                            </td>
                            <td>{{$document->user->name}}
                            </td>
                            <td>{{$document->folder->docname}}
                            </td>

                            <td>
                                <i class="icon wb-time m-l-10" aria-hidden="true"></i>
                                <span> {{$document->created_at->diffForHumans()}}</span>

                            </td>
                            <td>
                                @if($document->expiry=="")
                                    N/A

                                @else
                                    {{$document->expiry->diffForHumans()}}
                                @endif
                            </td>
                            <td>
                                @if($document->last_mod_id!=0)  {{$document->user_modified->name }} @ {{$document->updated_at->diffForHumans()}}
                                @else
                                    None
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary dropdown-toggle waves-effect" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
                                        <i class="icon md-apps" aria-hidden="true"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                        <a class="dropdown-item" href="{{url('document')}}/download?id={{$document->id}}" role="menuitem">Download Document</a>


                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            @else
                <div style="margin-top:10px;" class="alert alert-danger"><h4>Folder Empty</h4></div>
            @endif

        </div>
    </div>
</div>