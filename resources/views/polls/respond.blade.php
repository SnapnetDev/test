@extends('layouts.master')
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Taking Poll: ')}} {{ $poll->name }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('view.polls')}}">{{__('Polls')}}</a></li>
                <li class="breadcrumb-item active">{{ $poll->name }}</li>
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
                <p style="color: green;">Instruction: Click on your choice from the listed options to select</p>
                @if(isset($response))<p style="color: green;">You have already Submitted a Response for this Poll</p>@endif
            <form action="{{ route('submit.response') }}" method="post">
                <div class="row is-flex">
                @csrf
                <input type="hidden" name="poll_id" value="{{$poll->id}}">
                @foreach($poll->questions as $question)
                    <div class="col-lg-4 masonry-item">
                        <div class="card card-shadow">
                            <div class="card-header bg-blue-600 white p-15 clearfix">
                                <div class="font-size-18">{{ $question->question }}</div>
                            </div>
                            <div class="list-group radio-list-group">
                                @foreach(collect($question->options) as $option)
                                    <div class="list-group-item">&nbsp;
                                        <label>
                                            <input required type="radio" name="{{ $question->id }}" value="{{ $option['id'] }}" id="{{ $option['id'] }}"
                                            @if(isset($response))
                                                {{ collect($response->responses)->where('question_id',$question->id)->first()['selected_option']== $option['id'] ? 'checked' : ' '}}
                                            @endif
                                            >
                                            <span class="list-group-item-text"> <i class="fa fa-fw"></i>   {{ $option['option'] }} </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn bg-primary" type="submit">Submit</button>
                    </div>

                </div>
            </form>
        </div>

    </div>
@endsection
@section('scripts')
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
@endsection
@section('stylesheets')
    @include('polls.flex_row')
    <style>

        .radio-list-group input[type=radio] {
            display: none
        }

        .radio-list-group .list-group-item {
            position: relative;
            overflow: hidden;
            border-style: hidden
        }

        .radio-list-group .list-group-item label {
            display: block;
            width: 100%;
            font-weight: 400
        }

        .radio-list-group .list-group-item input + span {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin-bottom: -1px;
            z-index: 1
        }

        .radio-list-group .list-group-item input + span i.fa:before {
            content: "\f111";
            font-size: 20px;
            line-height: 20px;
            font-weight: 700
        }

        .radio-list-group .list-group-item input:checked + span i.fa:before {
            content: "\f10c"
        }

        .radio-list-group .list-group-item:hover input + span {
            background-color: #f5f5f5
        }

        .radio-list-group .list-group-item input:checked + span {
            color: #fff;
            background-color: #6bc346;
            border-color: #6bc346;
            z-index: 10
        }

        .radio-list-group .list-group-item.list-group-item-success:hover input + span {
            color: #3c763d;
            background-color: #d0e9c6
        }

        .radio-list-group .list-group-item-success input:checked + span {
            color: #fff !important;
            background-color: #3c763d !important;
            border-color: #3c763d !important
        }

        .radio-list-group .list-group-item-success input:checked + span i.fa:before {
            color: #d0e9c6 !important
        }

        .radio-list-group .list-group-item.list-group-item-info:hover input + span {
            color: #31708f;
            background-color: #c4e3f3
        }

        .radio-list-group .list-group-item-info input:checked + span {
            color: #fff !important;
            background-color: #31708f !important;
            border-color: #31708f !important
        }

        .radio-list-group .list-group-item-info input:checked + span i.fa:before {
            color: #c4e3f3 !important
        }

        .radio-list-group .list-group-item.list-group-item-warning:hover input + span {
            color: #8a6d3b;
            background-color: #faf2cc
        }

        .radio-list-group .list-group-item-warning input:checked + span {
            color: #fff !important;
            background-color: #8a6d3b !important;
            border-color: #8a6d3b !important
        }

        .radio-list-group .list-group-item-warning input:checked + span i.fa:before {
            color: #faf2cc !important
        }

        .radio-list-group .list-group-item-danger:hover input + span {
            color: #a94442;
            background-color: #ebcccc
        }

        .radio-list-group .list-group-item-danger input:checked + span {
            color: #fff !important;
            background-color: #a94442 !important;
            border-color: #a94442 !important
        }

        .radio-list-group .list-group-item-danger input:checked + span i.fa:before {
            color: #faf2cc !important
        }

        .radio-list-group-2 input {
            display: none
        }

        .radio-list-group.radio-list-group-2 .list-group-item input + span i.fa:before {
            display: inline-block;
            position: relative;
            content: '' !important;
            height: 14px;
            width: 14px;
            line-height: 14px;
            vertical-align: middle;
            overflow: hidden;
            border-radius: 14px;
            border: 3px #ccc outset;
            border-bottom-width: 4px;
            -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15), 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15), 0 1px 1px rgba(0, 0, 0, .075);
            background-color: rgba(255, 255, 255, .5)
        }

        .radio-list-group.radio-list-group-2 .list-group-item input:checked + span i.fa:before {
            content: '' !important;
            border-style: inset;
            border-top-width: 4px;
            border-bottom-width: 3px;
            background-color: rgba(0, 0, 0, .5);
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075)
        }
    </style>
@endsection