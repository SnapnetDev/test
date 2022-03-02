@extends('layouts.master')
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Taking Poll: ')}} {{ $poll->name }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('view.polls')}}">{{__('Polls')}}</a></li>
                <li class="breadcrumb-item active">{{ $poll->name }} Responses</li>
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

        <div class="page-content container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @if($poll->user_id==Auth::id())
                        <p>Click on the count to see the list of those who responded</p>
                    @endif
                </div>
            </div>
            <div class="row is-flex">
            @foreach($poll->questions as $question)
                <div class="col-lg-3 masonry-item">
                    <div class="card card-shadow">
                        <div class="card-header bg-blue-600 white p-15 clearfix">
                            <div class="font-size-18">{{ $question->question }}</div>
                        </div>
                        <ul class="list-group list-group-bordered mb-0">
                            @foreach(collect($question->options) as $option)
                            <li class="list-group-item">

                                @if($poll->user_id==Auth::id())
                                    <span class="badge badge-pill badge-success">
                                        @if($answers[$question['id']][$option['id']]['count']>0)
                                            <span style="cursor:pointer;" onclick="return viewUsers({{ collect($answers[$question['id']][$option['id']]['users']) }},{{$poll->id}});">
                                            {{ $answers[$question['id']][$option['id']]['count'] }}
                                            </span>
                                        @else
                                            {{ $answers[$question['id']][$option['id']]['count'] }}
                                        @endif
                                    </span>

                                @else{{--not creator--}}
                                    @if($poll->type=='anonymous')
                                    {{ $answers[$question['id']][$option['id']]['count'] }}
                                    @else
                                        <span class="badge badge-pill badge-success">
                                            @if($answers[$question['id']][$option['id']]['count']>0)
                                                <span style="cursor:pointer;" onclick="return viewUsers({{ collect($answers[$question['id']][$option['id']]['users']) }},{{$poll->id}});">
                                                    {{ $answers[$question['id']][$option['id']]['count'] }}
                                                </span>
                                            @else
                                                {{ $answers[$question['id']][$option['id']]['count'] }}
                                            @endif
                                        </span>
                                    @endif
                                @endif
                                {{ $option['option'] }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
            </div>
        </div>

    </div>

    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="usersVotedModal" aria-hidden="true"
         aria-labelledby="usersVotedModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Users that selected <span id="option_name"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12" id="detailLoader">

                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">


                        <!-- End Example Textarea -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function viewUsers(users,poll_id) {
            formData = {
                _token: '{{csrf_token()}}',
                users:users,
                poll:poll_id,
            };
            $.ajax({
                url: '{{url('poll/voted-users')}}',
                data: formData,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                success: function (data, textStatus, jqXHR) {
                    $("#detailLoader").html(data);
                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr.error(valchild[0]);
                        });
                    });
                }
            });


            $('#usersVotedModal').modal();
            // });
        }
    </script>
    @if(Session::has('success'))
        <script>
            toastr.success("{{ Session::get('success') }}", 'Success');
        </script>
    @endif
    @if(Session::has('fail'))
        <script>
            toastr.error("{{ Session::get('fail') }}", 'Error');
        </script>
    @endif
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
@endsection
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    @include('polls.flex_row')
    <style>
        .list-group-item>.badge-pill {
            -webkit-box-ordinal-group: 2;
            -webkit-order: 1;
            -ms-flex-order: 1;
            order: 1;
            float: right;
            margin-left: auto;
        }

        .badge {
            font-weight: 500;
        }
        .badge-pill {
            padding: 3px 6px;
        }
        .badge-success {
            color: #fff;
            background-color: #28a745;
        }
        .badge-pill {
            padding-right: .6em;
            padding-left: .6em;
            border-radius: 10rem;
        }
        .badge {
            display: inline-block;
            padding: .25em .6em;
            font-size: 75%;
            font-weight: 300;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .215rem;
        }
    </style>
@endsection