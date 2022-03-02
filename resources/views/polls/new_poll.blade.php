@extends('layouts.master')
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Polls')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('view.polls')}}">{{__('Polls')}}</a></li>
                <li class="breadcrumb-item active">New Poll</li>
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
                            <h3 class="panel-title">New Poll</h3>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('store.poll') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Name</h4>
                                                <input type="text" name="name" class="form-control" autocomplete="off" placeholder="Poll Name">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Description </h4>
                                                <textarea class="form-control"  name="description" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <h4 class="example-title">End Date</h4>
                                                <input type="text" class="form-control datepair-date datepair-start"
                                                    id="date"
                                                    data-plugin="datepicker" name="end_date" autocomplete="off"
                                                    placeholder="MM/DD/YY">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <h4 class="example-title">Status</h4>
                                                <select class="form-control" name="status">
                                                     <option value="pending">Pending</option>
                                                     <option value="active">Active</option>
                                                     <option value="ended">Ended</option>
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
                                                <select  data-plugin="select2" multiple class="form-control" name="roles[]" id="roles">
                                                    @foreach($roles as $role)
                                                        <option value="{{$role->id}}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Groups</h4>
                                                <select data-plugin="select2" multiple class="form-control" name="groups[]">
                                                    @foreach($groups as $group)
                                                        <option value="{{$group->id}}">{{ $group->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <h4 class="example-title">Department</h4>
                                                <select data-plugin="select2" multiple class="form-control" name="departments[]">
                                                    @foreach($departments as $department)
                                                        <option value="{{$department->id}}">{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <input type="checkbox" class="active-toggle" id="all_staff" name="all_staff">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    @php($rand=str_random(4))
                                    <div  class="col-lg-8">
                                        <div id="questionDiv">
                                           <div id="QandO">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                             <h4 class="example-title">Question </h4>
                                                             <textarea class="form-control"  name="questions[222][question]" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" id="optionRow222">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                             <h4 class="example-title">Option</h4>
                                                             <textarea class="form-control" name="questions[222][option][]" rows="2" required></textarea>
                                                             <span class="remove_field">Remove</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <button type="button"  onclick="addOption(this.id)" class="btn btn-primary" id="222">Add Option</button>
                                                    </div>
                                                </div>
                                               <br>
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

        $('#all_staff').bootstrapToggle({
            on: 'SELECT ALL STAFF',
            off: 'NOT ALL STAFF',
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

         function  selectAll() {
             var arr = new Array();
             $('#roles option').each(function(){
                 $(this).attr("selected",true)
                 arr.push($(this).val());
             });
             $('#roles').val(arr)
         }
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


