@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/tablesaw/tablesaw.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/apps/contacts.css') }}">

    <link rel="stylesheet" href="{{ asset('global/vendor/ladda/ladda.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/uikit/buttons.css') }}">
    <link href="{{asset('global/vendor/rwd-table-patterns/dist/css/rwd-table.min.css')}}" rel="stylesheet" type="text/css" media="screen">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-table/bootstrap-table.css') }}">
    <style type="text/css">
        .table-responsive[data-pattern="priority-columns"] {

            overflow-x: auto;
            overflow-y: auto;
        }


        table th{
            background-color: #03a9f4;
            color: #fff !important;
        }
    </style>
@endsection
@section('content')
    <div class="page bg-white">
        <div class="page-aside">
            <!-- Contacts Sidebar -->
            <div class="page-aside-switch">
                <i class="icon md-chevron-left" aria-hidden="true"></i>
                <i class="icon md-chevron-right" aria-hidden="true"></i>
            </div>
            <div class="page-aside-inner page-aside-scroll">
                <div data-role="container">
                    <div data-role="content">
                        <div class="page-aside-section">
                            <div class="list-group">
                                <a class="list-group-item status-reset" href="javascript:void(0)">
                                    <span class="item-right">{{$usersforcount->whereIn('status', [0, 1])->count()}}</span><i class="icon md-accounts-alt" aria-hidden="true"></i>Active
                                    Employees
                                </a>
                            </div>
                        </div>
                        <div class="page-aside-section">
                            <h5 class="page-aside-title">Employment Status</h5>
                            <div class="list-group">
                                <a class="list-group-item status-sort {{request()->status==1?'active':''}}" href="javascript:void(0)" id="1">
                                    <span class="item-right">{{$usersforcount->where('status',1)->count()}}</span><i class="icon md-check-square" aria-hidden="true"></i>Confirmed
                                </a>
                                <a class="list-group-item status-sort {{request()->status==0?'active':''}}" href="javascript:void(0)" id="0">
                                    <span class="item-right">{{$usersforcount->where('status',0)->count()}}</span><i class="icon md-square-o" aria-hidden="true"></i>Probation
                                </a>
                                <a class="list-group-item status-sort {{request()->status==2?'active':''}}" href="javascript:void(0)" id="2">
                                    <span class="item-right">{{$usersforcount->where('status',2)->count()}}</span><i class="icon md-delete" aria-hidden="true"></i>Disengaged
                                </a>
                                <a class="list-group-item status-sort {{request()->status==3?'active':''}}" href="javascript:void(0)" id="3">
                                    <span class="item-right">{{$usersforcount->count()}}</span><i class="icon md-check-square" aria-hidden="true"></i>Master Record
                                </a>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Contacts Content -->
        <div class="page-main">
            <!-- Contacts Content Header -->
            <div class="page-header">
                <h1 class="page-title">Employee Information



                    <div class="btn-group btn-group-flat" style="float: right;margin-right: 74px;">
                        @if(Auth::user()->role->permissions->contains('constant', 'manage_user') && isset(\App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value) && \App\Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value=='1')
                            <button class="btn btn-info">
                                <a style="text-decoration: none; color: white" href="{{ route('enroll-users') }}" title="Enroll to Biometric"> Enroll All</a>
                            </button>&nbsp;
                            <button class="btn btn-danger">
                                <a style="text-decoration: none; color: white" href="{{ route('remove-users') }}" title="Remove from Biometric"> Remove All</a>
                            </button>&nbsp;
                        @endif
                        <button class="btn  btn-success  waves-effect" title="Export to Excel" id="exporttoexcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp; Current View</button>
                        <button class="btn  btn-success  waves-effect" title="Export to All Excel" id="exportalltoexcel"><i class="fa fa-file-excel-o" aria-hidden="true"> </i>&nbsp;All</button>
                        <button class="btn  btn-danger  waves-effect" title="Export to PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                    </div>

                </h1>
                <div class="page-header-actions">
                    <form>
                        <select class="form-control" id="pagi-change">
                            <option value="all" {{ request()->pagi=='all'?'selected':'' }}>All</option>
                            <option value="10" {{ request()->pagi==10?'selected':(request()->pagi==''?'selected':'') }}>10</option>
                            <option value="15" {{ request()->pagi==15?'selected':'' }}>15</option>
                            <option value="25" {{ request()->pagi==25?'selected':'' }}>25</option>
                            <option value="50" {{ request()->pagi==50?'selected':'' }}>50</option>
                        </select>
                    </form>
                </div>
            </div>
            <!-- Contacts Content -->
            <div id="contactsContent" class="page-content page-content-table" data-plugin="asSelectable">
                <!-- Actions -->
                {{--<div class="page-content-actions">--}}

                    {{--<div class="btn-group btn-group-flat">--}}
                        {{--<button class="btn  btn-success " title="Export to Excel" id="exporttoexcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp; Current View</button>--}}
                        {{--<button class="btn  btn-success " title="Export to All Excel" id="exportalltoexcel"><i class="fa fa-file-excel-o" aria-hidden="true"> </i>&nbsp;All</button>--}}
                        {{--<button class="btn  btn-danger " title="Export to PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>--}}
                    {{--</div>--}}
                    {{----}}
                {{--</div>--}}

                <div class="panel-group" id="filterAccordion" aria-multiselectable="true"
                     role="tablist">
                    <div class="panel bg-blue-a100 ">
                        <div class="panel-heading" id="filterAccordionHeadingOne" role="tab">
                            <a class="panel-title collapsed" data-toggle="collapse" href="#filterAccordionOne"
                               data-parent="#filterAccordion" aria-expanded="true"
                               aria-controls="filterAccordionOne" style="color:#fff;padding: 7px;">
                                <button class="btn btn-primary">Filter Users</button>
                            </a>
                        </div>
                        <div class="panel-collapse collapse" id="filterAccordionOne" aria-labelledby="filterAccordionHeadingOne"
                             role="tabpanel">
                            <div class="panel-body bg-blue-50">
                                <form class="" action="{{route('users.index')}}" method="get" id="filterForm" >
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group " data-plugin="formMaterial">
                                                <label class="form-control-label" for="employee">Employee Name/Email/Staff ID</label>
                                                <input type="text" class="form-control" name="employee" value="{{ request()->employee }}">

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group " data-plugin="formMaterial">
                                                <label class="form-control-label" for="company">Role</label>
                                                <select class="form-control" name="role">
                                                    <option value="0">All Roles</option>
                                                    @forelse($roles as $role)
                                                        <option value="{{$role->id}}" {{ request()->role==$role->id?'selected':'' }}>{{$role->name}}</option>
                                                    @empty
                                                        <option value="0">Please Create Roles</option>
                                                    @endforelse
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group " data-plugin="formMaterial">
                                                <label class="form-control-label" for="user_group">User Groups</label>
                                                <select class="form-control" name="user_group">
                                                    <option value="0">All User Groups</option>
                                                    @forelse($user_groups as $group)
                                                        <option value="{{$group->id}}" {{ request()->group==$group->id?'selected':'' }}>{{$group->name}}</option>
                                                    @empty
                                                        <option value="0">Please Create User Groups</option>
                                                    @endforelse
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group " data-plugin="formMaterial">
                                                <label class="form-control-label" for="company">Company</label>
                                                <select class="form-control" name="company" required onchange="getDepartmentBranchesFilter(this.value);">
                                                    <option value="0">All Companies</option>
                                                    @forelse($companies as $company)
                                                        <option value="{{$company->id}}" {{ request()->company==$company->id?'selected':'' }}>{{$company->name}}</option>
                                                    @empty
                                                        <option value="0">Please Create a company</option>
                                                    @endforelse
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group " data-plugin="formMaterial">
                                                <label class="form-control-label" for="name">Department</label>
                                                <select class="form-control" name="department" required id="department">
                                                    @if($departments)
                                                        <option value="0">All Departments</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{$department->id}}" {{ request()->department==$department->id?'selected':'' }}>{{$department->name}}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="0">Company has no department</option>
                                                    @endif

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group " data-plugin="formMaterial">
                                                <label class="form-control-label" for="name" >Branch</label>
                                                <select class="form-control" name="branch" required id="branch">
                                                    @if($branches)
                                                        <option value="0">All Branches</option>
                                                        @foreach($branches as $branch)
                                                            <option value="{{$branch->id}}" {{ request()->branch==$branch->id?'selected':'' }}>{{$branch->name}}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="0">Company has no branch</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12"></div>
                                        <input type="hidden" value="{{ request()->pagi }}" id="pagi" name="pagi">
                                        <input type="hidden" value="{{ request()->status }}" id="status" name="status">
                                        <input type="hidden" value="{{ request()->employment_status }}" id="employment_status" name="employment_status">
                                        <input type="hidden" value="" id="excel" name="excel">
                                        <input type="hidden" value="" id="excelall" name="excelall">
                                        <a href="{{url('/users')}}" class="btn btn-warning pull-xs-right">Clear Filters</a>
                                        <button type="submit" class="btn btn-primary pull-xs-right">Filter</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- Contacts -->
                <div class="table-responsive"  data-pattern="priority-columns">
                    <table class="table is-indent tablesaw" data-tablesaw-mode="swipe" data-plugin="animateList"
                           data-animate="fade" data-child="tr" data-selectable="selectable">
                        <thead>
                        <tr>
                            <th class="pre-cell"></th>
                            <th class="cell-30" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">
                <span class="checkbox-custom checkbox-primary checkbox-lg contacts-select-all">
                  <input type="checkbox" class="contacts-checkbox selectable-all" id="select_all"
                  />
                  <label for="select_all"></label>
                </span>
                            </th>
                            <th  data-priority="1" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Name</th>
                            <th data-priority="2" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3">Staff ID</th>
                            <th data-priority="3"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Gender</th>
                            <th data-priority="3"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Grade</th>
                            <th data-priority="3"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4"> Status </th>
                            <th data-priority="3"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Department</th>
                            {{--<th data-priority="3"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Job Role</th>--}}
                            <th data-priority="4"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Email</th>
                            {{--<th data-priority="4"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Manager</th>--}}

                            <th data-priority="4" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Role</th>
                            {{--<th data-priority="4" class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Status</th>--}}
                            {{--<th data-priority="4"  class="cell-300" scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Address</th>--}}
                            <th data-priority="5" class="cell-300"  scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4">Options</th>
                            <th class="suf-cell"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr data-url="{{route('users.modal',['user_id'=>$user->id])}}" data-toggle="slidePanel">
                                <td class="pre-cell"></td>
                                <td class="cell-30">
                <span class="checkbox-custom checkbox-primary checkbox-lg">
                  <input data-check-user="{{ $user->id }}" type="checkbox" class="contacts-checkbox users-checkbox selectable-item" id="{{$user->id}}"
                  />
                  <label for="contacts_1"></label>
                </span>
                                </td>
                                <td class="cell-300">

                                    {{$user->name}}
                                </td>
                                <td class="cell-300" >{{$user->emp_num}}</td>
                                <td class="cell-300" >{{$user->sex=='M'?'Male':($user->sex=='F'?'Female':'')}}</td>

                                <td class="cell-300" >{{$user->user_grade?$user->user_grade->level:''}}</td>
                                <td class="cell-300" >

                                        <b>{{$user->my_status}}</b>

                                </td>
{{--                                <td class="cell-300" >{{$user->grade?($user->grade->grade_category?$user->grade->grade_category->name:''):''}}</td>--}}
                                <td class="cell-300" >{{$user->job?$user->job->department->name:''}}</td>

                                {{--<td >--}}
                                    {{--@if(count($user->jobs)>0)--}}
                                        {{--{{$user->jobs()->latest()->first()->title}}--}}
                                    {{--@endif--}}
                                {{--</td>--}}

                                <td class="cell-300" >{{$user->email}}</td>

                                {{--<td >--}}
                                    {{--@if(count($user->managers)>0)--}}
                                        {{--{{$user->managers()->first()->name}}--}}
                                    {{--@endif--}}
                                {{--</td>--}}

                                <td>
                                    @if($user->role)
                                        {{$user->role->name}}
                                    @endif
                                </td>
                                {{--<td>--}}

                                    {{--{{$user->status==0?'Probation':($user->status==1?'Confirmed':($user->status==2?'Disengaged':''))}}--}}

                                {{--</td>--}}
                                {{--<td class="cell-300" >{{$user->address}}</td>--}}
                                <td ><div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" id="exampleIconDropdown1"
                                                data-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem">View Profile</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#changeRoleModal"  >Change Role</a>
                                            <a target="_blank" class="dropdown-item" href="{{ route('app.get',['course-training']) }}?id={{ $user->id }}">View Training</a>
                                        </div>
                                    </div></td>
                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>
                @if (request()->pagi!='all')
                    {!! $users->appends(Request::capture()->except('page'))->render() !!}
                @endif

            </div>
        </div>
    </div>
    <!-- Site Action -->
    <div class="site-action" data-plugin="actionBtn">
        <button type="button" class="site-action-toggle btn-raised btn btn-success btn-floating">
            <i class="front-icon md-plus animation-scale-up" aria-hidden="true"></i>
            <i class="back-icon md-close animation-scale-up" aria-hidden="true"></i>
        </button>
        <div class="site-action-buttons">
            @if (Auth::user()->role->permissions->contains('constant', 'enroll_users_elearning'))
            <button type="button" id="enroll-user" class="btn-raised btn btn-success btn-floating animation-slide-bottom" title="Enroll To E-Learning" >
                <i class="icon md-book" aria-hidden="true"></i>
            </button>
            @endif

            <button type="button" data-toggle="modal" data-target="#assignGroupModal" class="btn-raised btn btn-success btn-floating animation-slide-bottom" title="Add to Group" >
                <i class="icon md-accounts-add" aria-hidden="true"></i>
            </button>

            <button type="button" data-toggle="modal" data-target="#changeRoleModal" class="btn-raised btn btn-success btn-floating animation-slide-bottom" title="Assign to Role" >
                <i class="icon md-case" aria-hidden="true"></i>
            </button>
            <button type="button" data-toggle="modal" data-target="#assignManagerModal" class="btn-raised btn btn-success btn-floating animation-slide-bottom" title="Assign to Line Manager" >
                <i class="icon md-arrow-right-top" aria-hidden="true"></i>
            </button>
            <button type="button" data-action="folder" class="btn-raised btn btn-success btn-floating animation-slide-bottom" title="Change Employee Status" data-toggle="modal" data-target="#changeStatusModal">
                <i class="icon md-refresh-alt" aria-hidden="true"></i>
            </button>
                <button type="button" data-action="folder" class="btn-raised btn btn-success btn-floating animation-slide-bottom" title="Change Employee Login Status" data-toggle="modal" data-target="#changeLoginStatusModal">
                    <i class="icon md-lock" aria-hidden="true"></i>
                </button>
            {{--  <button type="button" data-toggle="modal" data-target="#changeSystemStatusModal" class="btn-raised btn btn-success btn-floating animation-slide-bottom" title="Change Employee System Status" >
               <i class="icon fa fa-tags" aria-hidden="true"></i>
             </button> --}}
        </div>
    </div>
    <!-- End Site Action -->
    <!-- Add User Form -->
    @include('empmgt.modals.newemployee')
    @include('empmgt.modals.assignLineManager')
    @include('empmgt.modals.assignGroup')
    @include('empmgt.modals.changeRole')
    @include('empmgt.modals.changeStatus')
    @include('empmgt.modals.changeLoginStatus')
    @include('empmgt.modals.changeSystemStatus')
    @include('empmgt.modals.queryEmployee')

    <!-- End Add User Form -->
    <!-- Add Label Form -->
    <div class="modal fade" id="addLabelForm" aria-hidden="true" aria-labelledby="addLabelForm"
         role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Add New Label</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" class="form-control" name="lablename" placeholder="Label Name"
                            />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" type="submit">Save</button>
                    <a class="btn btn-sm btn-white btn-pure" data-dismiss="modal" href="javascript:void(0)">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Label Form -->
@endsection
@section('scripts')
    {{-- <script src="{{ asset('global/vendor/tablesaw/tablesaw.jquery.js')}}"></script> --}}
    <script src="{{asset('global/vendor/rwd-table-patterns/dist/js/rwd-table.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/js/Plugin/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>

    <script src="{{asset('global/vendor/summernote/summernote.min.js')}}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.issue_query').click(function(){
                    content=$('.query_content').summernote('code');
                    queried_user_id=$('#queried_user_id').val();
                    query_type_id=$('#query_type_select').val();
                    if(content.trim()=='' || query_type_id==''){
                        return toastr.error('Some Fields Empty')
                    }
                    formData={
                        _token:'{{csrf_token()}}',
                        content:content,
                        type:'replyOrIssueQuery',
                        created_by:'{{request()->user()->id}}',
                        queried_user_id:queried_user_id,
                        query_type_id:query_type_id,
                        text:'Issued'
                    }
                    console.log(formData);
                    alertify.confirm('Are you sure you want to issue query?',async function(){
                        $.post('{{url('query')}}',formData,function(data){
                            if(data.status=='success'){
                                toastr.success(data.message);
                                return;
                            }
                            return toastr.error(data.message);
                        });
                    })
                });
                $('#query_type_select').change(function(){
// alert($('#query_content'+$(this).val()).val());
                    $('.query_content').summernote('code',$('#query_content'+$(this).val()).val());
                })
                $('.query_content').summernote({height:300});
                $('.datepicker').datepicker();
                $('.select2').select2();

            $(document).on('submit', '#addNewUserForm', function(event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                // console.log(formdata);
                // return;
                //var formAction = form.attr('action');
                $.ajax({
                    url         : '{{url('users/new')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){
                        toastr["success"]("User Added successfully",'Success');
                        $('#addUserForm').modal('toggle');
                    },
                    error:function(data, textStatus, jqXHR){

                        jQuery.each( data['responseJSON'], function( i, val ) {

                            jQuery.each( val, function( i, valchild ) {

                                toastr["error"](valchild[0]);

                            });

                        });
                        console.log(textStatus);
                        console.log(jqXHR);
                    }
                });

            });
        });
        function getDepartmentBranchesFilter(company_id){
            event.preventDefault();
            $.get('{{ url('/users/company/departmentsandbranches') }}/'+company_id,function(data){
                console.log(data.branch);
                if (data.departments=='') {
                    $("#department").empty();
                    $('#department').append($('<option>', {value:0, text:'All Departments'}));
                }else{
                    $("#department").empty();
                    $('#department').append($('<option>', {value:0, text:'All Departments'}));
                    jQuery.each( data.departments, function( i, val ) {
                        $('#department').append($('<option>', {value:val.id, text:val.name}));
                    });
                }
                if (data.branches=='') {
                    $("#branch").empty();
                    $('#branch').append($('<option>', {value:0, text:'All Branches'}));
                }else{
                    $("#branch").empty();
                    $('#branch').append($('<option>', {value:0, text:'All Branches'}));
                    jQuery.each( data.branches, function( i, val ) {
                        $('#branch').append($('<option>', {value:val.id, text:val.name}));
                    });
                }

            });
        }

        function getDepartmentBranchesNew(company_id){
            event.preventDefault();
            $.get('{{ url('/users/company/departmentsandbranches') }}/'+company_id,function(data){

                if (data.departments=='') {
                    $("#department_id").empty();
                    $('#department_id').append($('<option>', {value:0, text:'Please Create a Department'}));
                }else{
                    $("#department_id").empty();
                    jQuery.each( data.departments, function( i, val ) {
                        $('#department_id').append($('<option>', {value:val.id, text:val.name}));
                    });
                }
                if (data.branches=='') {
                    $("#branch_id").empty();
                    $('#branch_id').append($('<option>', {value:0, text:'Please Create a Branch'}));
                }else{
                    $("#branch_id").empty();
                    jQuery.each( data.branches, function( i, val ) {
                        $('#branch_id').append($('<option>', {value:val.id, text:val.name}));
                    });
                }

            });
        }
        $(document).on('click','#assignLineManager',function(event){
            if ($('.users-checkbox:checkbox:checked').length==0) {
                toastr.error("Please Select a User",'Error');
            }
            var users = $(".users-checkbox:checkbox:checked").map(function(){
                if (this.checked === true) {
                    return this.id;
                }
            }).toArray();
            manager_id=$("#managers").val();
            $.get('{{ url('/users/assignmanager') }}/',{ manager_id: manager_id,users:users },function(data){
                toastr.success("Manager Assigned Successfully",'Success');
                $('#assignManagerModal').modal('toggle');
            });

        });


        $(document).on('click','#assignRole',function(event){
            event.preventDefault();
            if ($('.users-checkbox:checkbox:checked').length==0) {
                toastr.error("Please Select a User",'Error');
            }
            var users = $(".users-checkbox:checkbox:checked").map(function(){
                if (this.checked === true) {
                    return this.id;
                }
            }).toArray();
            role_id=$("#roles").val();
            $.get('{{ url('/users/assignrole') }}/',{ role_id: role_id,users:users },function(data){
                toastr.success("Role Changed Successfully",'Success');
                $('#changeRoleModal').modal('toggle');
            });
        });

        $(document).on('click','#assignGroup',function(event){
            event.preventDefault();
            if ($('.users-checkbox:checkbox:checked').length==0) {
                toastr.error("Please Select a User",'Error');
            }
            var users = $(".users-checkbox:checkbox:checked").map(function(){
                if (this.checked === true) {
                    return this.id;
                }
            }).toArray();
            group_id=$("#groups").val();
            $.get('{{ url('/users/assigngroup') }}/',{ group_id: group_id,users:users },function(data){
                toastr.success("Users Added Successfully",'Success');
                $('#assignGroupModal').modal('toggle');
            });
        });

        $(document).on('click','#changeStatus',function(event){
            event.preventDefault();
            if ($('.users-checkbox:checkbox:checked').length==0) {
                toastr.error("Please Select a User",'Error');
            }
            var users = $(".users-checkbox:checkbox:checked").map(function(){
                if (this.checked === true) {
                    return this.id;
                }
            }).toArray();
            status=$("#status_id").val();
            $.get('{{ url('/users/alterstatus') }}/',{ status: status,users:users },function(data){
                toastr.success("Status Changed Successfully",'Success');
                $('#changeStatusModal').modal('toggle');
            });
        });
        $(document).on('click','#changeLoginStatus',function(event){
            event.preventDefault();
            if ($('.users-checkbox:checkbox:checked').length==0) {
                toastr.error("Please Select a User",'Error');
            }
            var users = $(".users-checkbox:checkbox:checked").map(function(){
                if (this.checked === true) {
                    return this.id;
                }
            }).toArray();
            status=$("#login_status_id").val();
            $.get('{{ url('/users/alterloginstatus') }}/',{ status: status,users:users },function(data){
                toastr.success("Status Changed Successfully",'Success');
                $('#changeLoginStatusModal').modal('toggle');
            });
        });


        $(document).on('click','#changeSystemStatus',function(event){
            event.preventDefault();
            if ($('.users-checkbox:checkbox:checked').length==0) {
                toastr.error("Please Select a User",'Error');
            }
            var users = $(".users-checkbox:checkbox:checked").map(function(){
                if (this.checked === true) {
                    return this.id;
                }
            }).toArray();
            status=$("#employee_status_id").val();
            $.get('{{ url('/users/alteremployeestatus') }}/',{ status: status,users:users },function(data){
                toastr.success("Employee System Status Changed Successfully",'Success');
                $('#changeSystemStatusModal').modal('toggle');
            });
        });

        $(document).on('change','#pagi-change',function(event){
            event.preventDefault();
            $('#pagi').val($(this).val());
            $('#filterForm').submit();
        });
        $(document).on('click','.status-sort',function(event){
            event.preventDefault();
            console.log($(this).attr("id"));
            $('#status').val($(this).attr("id"));
            $('#filterForm').submit();
        });
        $(document).on('click','.employment_status-sort',function(event){
            event.preventDefault();
            console.log($(this).attr("id"));
            $('#employment_status').val($(this).attr("id"));
            $('#filterForm').submit();
        });
        $(document).on('click','.employment_status-reset',function(event){
            event.preventDefault();
            console.log($(this).attr("id"));
            $('#employment_status').val('');
            $('#filterForm').submit();
        });

        $(document).on('click','.status-reset',function(event){
            event.preventDefault();
            console.log($(this).attr("id"));
            $('#status').val('');
            $('#filterForm').submit();
        });
        $(document).on('click','#exporttoexcel',function(event){
            event.preventDefault();
            $('#excel').val(true);
            $('#filterForm').submit();
        });
        $(document).on('click','#exportalltoexcel',function(event){
            event.preventDefault();
            $('#excelall').val(true);
            $('#filterForm').submit();
        });
        function departmentChange(department_id){
            event.preventDefault();
            $.get('{{ url('/users/department/jobroles') }}/'+department_id,function(data){


                if (data.jobs=='') {
                    $("#jobroles").empty();
                    $('#jobroles').append($('<option>', {value:0, text:'Please Create a Jobrole in Department'}));
                }else{
                    $("#jobroles").empty();
                    jQuery.each( data.jobroles, function( i, val ) {
                        $('#jobroles').append($('<option>', {value:val.id, text:val.title}));
                    });
                }

            });
        }



        (function($){
            $(function () {

                // alert('data-check-user');


                function getCheckedUsers(){
                  var users = [];
                  $('[data-check-user]').each(function(){
                      if ($(this).is(':checked')){
                          users.push($(this).attr('data-check-user'));
                      }
                  });
                  return users;
                }

                window.getCheckedUsers = getCheckedUsers;

                function getEnrollmentButton(){
                  return $('#enroll-user');
                    // id="enroll-user"
                }

                getEnrollmentButton().on('click',function(){

                    var users = getCheckedUsers();

                    users.forEach(function(value,key){
                        toastr.info('Enrolling user ... ');
                        $.ajax({

                            url:'',
                            type: 'POST',
                            data:{
                                userId: value,
                                _token: '{{ csrf_token() }}'
                            },
                            success:function(response){
                                toastr.success(response.message);
                            }


                        });

                    });

                    return false;
                });




            })
        })(jQuery);
    </script>

@endsection
