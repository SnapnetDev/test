@extends('layouts.master')
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Polls')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('view.polls')}}">{{__('Polls')}}</a></li>
                <li class="breadcrumb-item active">Edit Poll</li>
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
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <div class="panel-actions panel-actions-keep">
                                <a class="text-action" href="javascript:void(0)"><i class="icon wb-edit" aria-hidden="true"></i></a>
                            </div>
                            <h3 class="panel-title">Edit Poll</h3>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('update.poll',$poll->id) }}" method="post">
                                @method('patch')
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Name</h4>
                                                <input type="text" name="name" class="form-control" autocomplete="off" placeholder="Poll Name" value="{{ old('name',$poll->name) }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Description </h4>
                                                <textarea class="form-control"  name="description" rows="3">{{ old('description',$poll->description) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <h4 class="example-title">End Date</h4>
                                                <input type="text" class="form-control datepair-date datepair-start"
                                                    id="date"
                                                    data-plugin="datepicker" name="end_date" autocomplete="off"
                                                    placeholder="MM/DD/YY" value="{{ old('end_date',\Carbon\Carbon::parse($poll->end_date)->format('m/d/Y')) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <h4 class="example-title">Status</h4>
                                                <select class="form-control" name="status">
                                                     <option {{ $poll->status=='pending'? 'selected' : "" }} value="pending">Pending</option>
                                                     <option {{ $poll->status=='active'? 'selected' : "" }} value="active">Active</option>
                                                     <option {{ $poll->status=='ended'? 'selected' : "" }} value="ended">Ended</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="checkbox" class="active-toggle" id="anon" name="anonymous">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Roles</h4>
                                                <select  data-plugin="select2" multiple class="form-control" name="roles[]">
                                                    @foreach($roles as $role)
                                                        <option value="{{$role->id}}" @foreach($poll->roles as $rol) @if($rol==$role->id) selected @endif @endforeach>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Groups</h4>
                                                <select data-plugin="select2" multiple class="form-control" name="groups[]">
                                                    @foreach($groups as $group)
                                                        <option value="{{$group->id}}" @foreach($poll->groups as $gro) @if($gro==$group->id) selected @endif @endforeach>{{ $group->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Department</h4>
                                                <select data-plugin="select2" multiple class="form-control" name="departments[]">
                                                    @foreach($departments as $department)
                                                        <option value="{{$department->id}}" @foreach($poll->departments as $dep) @if($dep==$department->id) selected @endif @endforeach>{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div  class="col-lg-8">
                                        <div id="questionDiv">
                                            <div id="QandO">

                                                @foreach($poll->questions as $question)
                                                <div>

                                                        @php($rand=str_random(4))
                                                        @php($id=mt_rand(1,999))
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <h4 class="example-title">Question </h4>
                                                                    <textarea class="form-control"  name="questions[{{$id}}][question]" rows="3" required>{{ $question->question }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            <div class="row" id="optionRow222">
                                                                @foreach($question->options as $option)
                                                                @php($option=collect($option))
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <h4 class="example-title">Option</h4>
                                                                        <textarea class="form-control" name="questions[{{$id}}][option][]" rows="2" required>{{ $option['option'] }}</textarea>
                                                                        <span class="remove_field">Remove</span>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <button type="button"  onclick="addOption(this.id)" class="btn btn-primary" id="222">Add Option</button>
                                                                </div>
                                                            </div>
                                                            <br>
                                                </div>
                                                @endforeach


                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-lg-8">
                                            <button type="button" onclick="addQuestion()" class="btn btn-primary">Add Question</button>
                                        </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary" style="margin-left:10px">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $('#anon').bootstrapToggle({
            on: 'Anonymous ON',
            off: 'Anonymous Disabled',
            onstyle:'info',
            offstyle:'default'
        });
    </script>
    <script>
         $(document).ready(function() {
           $(questionDiv).on("click", ".remove_field", function (e) { //user click on remove text
               e.preventDefault();
               $(this).parent('div').parent('div').remove();
           });
       });

        function addQuestion() {
            id=getRandomInt()
            qname="questions["+id+"][question]";
            oname="questions["+id+"][option][]";
            $('<div id="QandO">\n' +
                '<div class="row">\n' +
                '    <div class="col-lg-12">\n' +
                '        <div class="form-group">\n' +
                '             <h4 class="example-title">Question </h4>\n' +
                '             <textarea class="form-control"  name="'+qname+'" rows="3"></textarea>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>\n' +
                '    \n' +
                '<div class="row" id="optionRow'+id+'">\n' +
                '    <div class="col-lg-3">\n' +
                '        <div class="form-group">\n' +
                '             <h4 class="example-title">Option</h4>\n' +
                '             <textarea class="form-control"  name="'+oname+'" rows="2"></textarea>\n' +
                '             <span class="remove_field">Remove</span>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>\n' +
                '<div class="row">\n' +
                '    <div class="col-lg-12">\n' +
                '        <button type="button"   onclick="addOption(this.id)" class="btn btn-primary" id="'+id+'">Add Option</button>\n' +
                '    </div>\n' +
                '</div>\n' +
                '<br>\n' +
                '</div>').appendTo(questionDiv);
        }
        function addOption(id){
            varia="questions["+id+"][option][]";
            $('<div class="col-lg-3">\n' +
                '<div class="form-group">\n' +
                '<h4 class="example-title">Option</h4>\n' +
                '<textarea class="form-control"  name="'+varia+'" rows="2"></textarea>\n' +
                '<span class="remove_field">Remove</span>\n' +
                '</div>\n' +
                '</div>').appendTo('#optionRow'+id);
        }

        function getRandomInt() {
            min=1;
            max=1000000;
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
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
@endsection
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <style>
        .remove_field{
            color: red;
        }
    </style>
@endsection


