@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{asset('global/vendor/asscrollable/asScrollable.min.css')}}">
    <style type="text/css">
        a.list-group-item:hover {
            text-decoration: none;
            background-color: #3f51b5;
        }

        .alertify {
            z-index: 9999999;
        }

        .hide {
            display: none;
        }
    </style>
@endsection
@section('content')
    @if(!request()->filled('excel'))
        <div class="page ">
        <div class="page-header">
            <h1 class="page-title">Query Management</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active">Query Management</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-xl-4 col-md-6 col-xs-12 info-panel pointer"
                 onclick="window.location='{{url('query')}}/allqueries?queried_user_id={{request()->queried_user_id}}'">
                <div class="card card-shadow">
                    <div class="card-block bg-white p-20">
                        <button type="button" class="btn btn-floating btn-sm btn-warning" data-toggle="modal"
                                data-target="#holidays">
                            <i class="fa fa-lg fa-warning"></i>
                        </button>
                        <span class="m-l-15 font-weight-400">Total Queries</span>
                        <div class="content-text text-xs-center m-b-0">
                            <i class="text-success icon wb-triangle-up font-size-20"></i>
                            <span
                                class="font-size-40 font-weight-100">{{$query_statistics->sum('status_count')}} </span>
                            <p class="blue-grey-400 font-weight-100 m-0">Total Queries</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 col-md-6 col-xs-12 info-panel pointer"
                 onclick="window.location='{{url('query')}}/allqueries?status=closed&queried_user_id={{request()->queried_user_id}}'">
                <div class="card card-shadow">
                    <div class="card-block bg-white p-20">
                        <button type="button" class="btn btn-floating btn-sm btn-success" data-toggle="modal"
                                data-target="#holidays">
                            <i class="fa fa-lg fa-warning"></i>
                        </button>
                        <span class="m-l-15 font-weight-400">Closed Queries</span>
                        <div class="content-text text-xs-center m-b-0">
                            <i class="text-success icon wb-triangle-up font-size-20"></i>
                            <span
                                class="font-size-40 font-weight-100">{{$query_statistics->where('status','closed')->sum('status_count')}}</span>
                            <p class="blue-grey-400 font-weight-100 m-0">Closed Queries</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 col-xs-12 info-panel pointer"
                 onclick="window.location='{{url('query')}}/allqueries?status=open&queried_user_id={{request()->queried_user_id}}'">
                <div class="card card-shadow">
                    <div class="card-block bg-white p-20">
                        <button type="button" class="btn btn-floating btn-sm btn-danger" data-toggle="modal"
                                data-target="#holidays">
                            <i class="fa fa-lg fa-warning"></i>
                        </button>
                        <span class="m-l-15 font-weight-400">Open Queries</span>
                        <div class="content-text text-xs-center m-b-0">
                            <i class="text-success icon wb-triangle-up font-size-20"></i>
                            <span
                                class="font-size-40 font-weight-100">{{$query_statistics->where('status','open')->sum('status_count')}} </span>
                            <p class="blue-grey-400 font-weight-100 m-0">Open Queries</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="page-content container-fluid ">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Query List</h3>
                    <div class="panel-actions">

                        <form id="query_form" method="get" action="{{url('query')}}/allqueries"
                              class="page-content  container-fluid">
                            <div class=" ">

                                <table style="margin-top: 2%">
                                    <tr>

                                        <td>
                                            @if(in_array(Auth::User()->role->manages,["dr","all"]))
                                                <select name="queried_user_id" style="width: 200px" id="employeeSearch"
                                                        class="form-control">
                                                    <option value="">- Enter Employee Name/Email to search-</option>
                                                </select>
                                            @endif
                                        </td>

                                        <td>

                                            <div class="input-group">
                                                <input value="{{request()->q}}" type="text" style="width: 200px" class="form-control" name="q"
                                                       placeholder="Search...">
                                                <span class="input-group-btn">
                      <button type="submit" class="btn btn-md btn-primary"><i class="fa   fa-search" style="padding:1px"
                                                                              aria-hidden="true"></i></button>

                        <a href="{{request()->fullUrlWithQuery(['excel'=>1])}}" class="btn btn-md btn-success"><i class="fa   fa-file-excel-o" style="padding:1px"
                                                                  aria-hidden="true"></i></a>
                    </span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="panel-body table-responsive">
                    @endif
                    <table class="table table-striped" style="width: 97.5%">
                        <thead>
                        <tr>
                            <th>Query Type</th>
                            <th>Employee Name</th>
                            <th>Status</th>
                            <th>Query Excerpt</th>
                            <th>Action Taken</th>
                            <th>Date Issued</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($all_queries as $query)
                            <tr>
                                <td id="query_title{{$query->id}}">{{$query->querytype->title}}</td>
                                <td>{{$query->querieduser->name}}</td>
                                <td><label class="tag tag-info" id="status{{$query->id}}">{{$query->status}}<span
                                            style="visibility: hidden">..</span> </label></td>
                                <td>
                                    <input type="hidden" value="{{$query->content}}" id="query_parent{{$query->id}}">
                                    <input type="hidden" value="{{$query->query_type_id}}"
                                           id="query_type_id{{$query->id}}">
                                    <input type="hidden" value="{{$query->createdby->user_image}}"
                                           id="query_user_image{{$query->id}}">
                                    <input type="hidden" value="{{$query->created_by}}" id="created_by{{$query->id}}">
                                    <input type="hidden" value="{{$query->queried_user_id}}"
                                           id="queried_user_id{{$query->id}}">
                                    <input type="hidden" value="{{$query->status}}" id="thread_status{{$query->id}}">
                                    @if(!request()->filled('excel'))
                                    {{substr($query->content,0,200)}}...
                                        @else
                                        {{$query->content}}
                                    @endif
                                </td>
                                <td>
                                    <b>{{strtoupper($query->action_taken)}}</b>
                                </td>
                                <td>
                                    {{$query->created_at->diffForHumans()}}
                                </td>

                                @if(!request()->filled('excel'))
                                <td>
                                    <div class="btn-group show" role="group">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle"
                                                id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="true">
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu show" aria-labelledby="exampleIconDropdown1"
                                             role="menu" x-placement="bottom-start"
                                              >
                                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"
                                               data-toggle="modal" data-target="#queryThread"
                                               onclick="viewQueryThread({{$query->id}},'{{$query->created_at->diffForHumans()}}')"><i
                                                    class="fa fa-eye"></i>View Query Thread</a>
                                            @if(in_array(Auth::User()->role->manages,["dr","all"]) && $query->status=='open' && request()->user()->role->permissions->contains('constant', 'issue_query'))
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                   onclick="closeQuery({{$query->id}},{{$query->queried_user_id}})"
                                                   role="menuitem"><i class="fa fa-close"></i>Close Query</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                    @endif
                            </tr>

                        @endforeach
                        </tbody>

                    </table>

                 @if(!request()->filled('excel'))
                        {!! $all_queries->appends(Request::capture()->except('page'))->render() !!}
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(!request()->filled('excel'))
        <!-- Site Action -->
        @include('query.modal.querythread');
        @include('query.modal.deciplinary_action_taken');
        <!-- End Add User Form -->
    @endif
@endsection

@section('scripts')
    @if(!request()->filled('excel'))
        <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{asset('global/vendor/summernote/summernote.min.js')}}"></script>

    <script src="{{asset('global/vendor/asscrollbar/jquery-asScrollbar.min.js')}}"></script>
    <script src="{{asset('global/vendor/asscrollable/jquery-asScrollable.min.js')}}"></script>
    <script src="{{asset('global/vendor/ashoverscroll/jquery-asHoverScroll.min.js')}}"></script>

    <script type="text/javascript">

        $(function () {

            $('#close_query_btn').click(function () {

                disciplinary_action = $('#disciplinary_action').val();
                select_action_taken = $('#select_action_taken').val();
                effective_date = $('#effective_date').val();
                other_action = $('#other_action').val();
                query_id = $('#query_id').val();
                queried_user_id = $('#queried_user_id').val();

                formData = {
                    queried_user_id: queried_user_id,
                    parent_id: query_id,
                    disciplinary_action: disciplinary_action,
                    select_action_taken: select_action_taken,
                    effective_date: effective_date,
                    other_action: other_action,
                    query_id: query_id,
                    queried_user_id: queried_user_id
                }

                $.get(`{{url('query')}}/${query_id}/edit`, formData, function (data) {
                    if (data.status == 'success') {
                        $(`#status${query_id}`).text('closed');
                        return toastr.success(data.message);
                    }
                    return toastr.error(data.message);
                })
            })
            $('#disciplinary_action').change(function () {
                if ($(this).val() == 'yes') {
                    $('.action_taken').removeClass('hide');
                } else {
                    $('.action_taken').addClass('hide');
                }

            })

            $('#select_action_taken').change(function () {

                if ($(this).val() == 'suspension' || $(this).val() == 'dismissal') {
                    $('.effective_date').removeClass('hide');
                } else {
                    $('.effective_date').addClass('hide');

                }

                if ($(this).val() == 'other') {
                    $('.others').removeClass('hide');
                } else {
                    $('.others').addClass('hide');
                }

            })


        })

        function viewQueryThread(id, time) {
            $('.scrollable-container ,.scrollable-content').css('width', '100%');
            $('#query_parent').html($('#query_parent' + id).val());
            $('#query_thread_id').val(id);
            status = $('#thread_status' + id).val();
            if (status == 'closed') {
                $('.query_response_table').addClass('hide');
            } else {
                $('.query_response_table').removeClass('hide');
            }
            $('#query_image').attr('src', $('#query_user_image' + id).val());
            $('#query_title_display').text($('#query_title' + id).text() + '@' + time)
            $('.query_response').summernote('code', '');

            $('.query_thread').load('{{url('query')}}/queryThread?parent_id=' + id);
        }

        $(document).ready(function () {
            $('#emptable').DataTable();

            selectAjax('employeeSearch', '{{url('query')}}/allEmployees?select2=true');

            {{--$('#employeeSearch').change(function () {--}}
            {{--    window.location = '{{url('query')}}/allqueries?queried_user_id=' + $(this).val();--}}
            {{--})--}}
            $('.query_response').summernote({height: 200});


        });

        @if(in_array(Auth::User()->role->manages,["dr","all"]))
        function closeQuery(query_id, queried_user_id) {

            alertify.confirm('Are you sure you want to close Query ? ', function () {
                $('#query_id').val(query_id);
                $('#queried_user_id').val(queried_user_id);
                $('#deciplinary_action_taken').modal('show');
                {{--$.get(`{{url('query')}}/${query_id}/edit?queried_user_id=${queried_user_id}&parent_id=${query_id}`,function (data) {--}}
                {{--    if(data.status=='success'){--}}
                {{--        $(`#status${query_id}`).text('closed');--}}
                {{--        return toastr.success(data.message);--}}
                {{--    }--}}
                {{--    return toastr.error(data.message);--}}
                {{--})--}}
            });

        }

        @endif

        async function replyQuery(query_id) {
            // alert(query_id);
            content = $('.query_response').summernote('code');
            if (content.trim() == '') {
                return toastr.erro('Some Fields Empty');
            }
            createdby = $('#created_by' + query_id).val();
            queried_user_id = $('#queried_user_id' + query_id).val();

            formData = {
                parent_id: query_id,
                _token: '{{csrf_token()}}',
                content: content,
                type: 'replyOrIssueQuery',
                created_by: createdby,
                queried_user_id: queried_user_id,
                query_type_id: $('#query_type_id' + query_id).val()
            }
            alertify.confirm('Are you sure you want to reply query?', async function () {
                $.post('{{url('query')}}', formData, function (data) {
                    if (data.status == 'success') {
                        toastr.success(data.message);
                        return $('.query_thread').load('{{url('query')}}/queryThread?parent_id=' + query_id);
                    }
                    return toastr.error(data.message);
                });


            })
        }
    </script>
    @endif
@endsection
