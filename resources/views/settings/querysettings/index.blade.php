<div class="page-header">
    <h1 class="page-title">{{__('Query Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item ">{{__('Query Settings')}}</li>
        <li class="breadcrumb-item active">{{__('You are Here')}}</li>
    </ol>
    <div class="page-header-actions">
        <div class="row no-space w-250 hidden-sm-down">

            <div class="col-sm-6 col-xs-12">
                <div class="counter">
                    <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="counter">
                    <span class="counter-number font-weight-medium" id="time"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-content container-fluid" >
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Query Escalation Flow</h3>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <td>Number of Reminders</td>
                            <td>Escalate to Role</td>
                            <td>Escalate to Group</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>After Two Reminders  </td>
                            <td>
                                <select id="role_id_2" class="form-control">
                                    @if(count($escalationRoutes->where('num_of_reminder',2))>0)
                                        <option value="{{@$escalationRoutes->where('num_of_reminder',2)->pluck('role_id')[0]}}">{{@$escalationRoutes->where('num_of_reminder',2)->pluck('role.name')[0]}}</option>
                                    @endif
                                    <option value="">-Select Role-</option>

                                </select>

                            </td>
                            <td>
                                <select id="group_id_2" class="form-control">
                                    @if(count($escalationRoutes->where('num_of_reminder',2))>0)
                                        <option value="{{@$escalationRoutes->where('num_of_reminder',2)->pluck('group_id')[0]}}">{{@$escalationRoutes->where('num_of_reminder',2)->pluck('group.name')[0]}}</option>
                                    @endif
                                    <option value="">-Select Group-</option>

                                </select>

                            </td>
                        </tr>
                        <tr>
                            <td>After Three Reminders</td>
                            <td>
                                <select id="role_id_3" class="form-control">
                                    @if(count($escalationRoutes->where('num_of_reminder',3))>0)
                                        <option value="{{@$escalationRoutes->where('num_of_reminder',3)->pluck('role_id')[0]}}">{{@$escalationRoutes->where('num_of_reminder',3)->pluck('role.name')[0]}}</option>
                                    @endif
                                    <option value="">-Select Role-</option>

                                </select>
                            </td>
                            <td>
                                <select id="group_id_3" class="form-control">
                                    @if(count($escalationRoutes->where('num_of_reminder',3))>0)
                                        <option value="{{@$escalationRoutes->where('num_of_reminder',3)->pluck('group_id')[0]}}">{{@$escalationRoutes->where('num_of_reminder',3)->pluck('group.name')[0]}}</option>
                                    @endif
                                    <option value="">-Select Group-</option>

                                </select>

                            </td>
                        </tr>
                        <tr>
                            <td>After More than Three Reminders  </td>
                            <td>
                                <select id="role_id_4" class="form-control">
                                    @if(count($escalationRoutes->where('num_of_reminder',4))>0)
                                        <option value="{{@$escalationRoutes->where('num_of_reminder',4)->pluck('role_id')[0]}}">{{@$escalationRoutes->where('num_of_reminder',4)->pluck('role.name')[0]}}</option>
                                        @endif

                                    <option value="">-Select Role-</option>

                                </select>
                            </td>
                            <td>
                                <select id="group_id_4" class="form-control">
                                    @if(count($escalationRoutes->where('num_of_reminder',3))>0)
                                        <option value="{{@$escalationRoutes->where('num_of_reminder',3)->pluck('group_id')[0]}}">{{@$escalationRoutes->where('num_of_reminder',3)->pluck('group.name')[0]}}</option>
                                    @endif
                                    <option value="">-Select Group-</option>

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                               <button class="btn btn-info pull-right" id="save_query_escalation_flow">Save</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Query Settings</h3>
                    <div class="panel-actions panel-actions-keep">
                        <button class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#addEditModal"><i class="fa fa-plus-circle"></i></button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Created By </th>
                            <th>Created At </th>
                            <th>Action</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($query_types as $query_type)
                            <tr>
                                <td>{{$query_type->title}}</td>
                                <td>
                                    <input type="hidden" value="{{$query_type->content}}" class="query_content_hidden">
                                    {{$query_type->createdby->name}}
                                </td>
                                <td>
                                    {{$query_type->updated_at->diffForHumans()}}
                                </td>
                                <td>
                                    <div class="btn-group show" role="group">
                                        <button type="button" class="btn btn-outline-info dropdown-toggle" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="true">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu show" aria-labelledby="exampleIconDropdown1" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="editQuery({{$query_type->id}},'{{$query_type->title}}',$('.query_content_hidden').val())" role="menuitem">Edit</a>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="deleteQuery({{$query_type->id}})" role="menuitem">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>


        </div>

    </div>
    </div>
<div class="col-md-12" style="margin-bottom: 200px;"></div>
    @include('settings.querysettings.modals.addEditModal');

<script>

    $(function () {
        for(i=2; i<=4; i++) {
            selectAjax('role_id_'+i, '{{url('query')}}/allRoles')
            selectAjax('group_id_'+i, '{{url('query')}}/allGroups')
        }

        $('#save_query_escalation_flow').click(function(){
            var data = [];
            for(i=2; i<=4; i++) {
                data[i-2]={
                    num_of_reminder:i,
                    role_id:$('#role_id_'+i).val(),
                    group_id:$('#group_id_'+i).val()
                }
            }
            formData={
                type:'saveQueryEscalationFlow',
                _token:'{{csrf_token()}}',
                data:data
            }
            postData(formData,'{{url('query')}}');

        })
    })
    function  editQuery(id,title,content) {
        $('.query_id').val(id);
        $('.query_title').val(title);
        $('.content_note').summernote('code',content);
        $('#addEditModal').modal('show');
    }

    function  deleteQuery(id) {
        formData={
            id:id,
            _method:'DELETE',
            _token:'{{csrf_token()}}'
        }
        alertify.confirm('Are you sure , This process cannot be undone', function(){
                alertify.success('Ok');
                return postData(formData, '{{url('query')}}/'+id,true)
            }
            , function(){
            alertify.error('Operation Cancelled');
        });

    }
</script>
