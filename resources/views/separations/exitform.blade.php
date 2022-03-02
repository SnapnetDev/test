@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('global/vendor/icheck/icheck.css') }}">
    <style media="screen">
        .form-cont{
            border: 1px solid #cccccc;
            padding: 10px;
            border-radius: 5px;
        }
        #stgcont {
            list-style: none;
        }
        #stgcont li{
            margin-bottom: 10px;
        }
    </style>

@endsection
@section('content')
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">Employee Exit Form</h1>
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
        <div class="page-content container-fluid">
            <div class="row">

                <div class="col-md-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                            {{ session('success') }}
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                            {{ session('error') }}
                        </div>
                    @endif
                    <div id="formbody">
                    <div class="panel panel-info ">

                        <form action=" " id="ExitForm">

                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h4>Handover Note</h4>
                                    <textarea name="handover_note" id="" cols="30" class="form-control" rows="10" required></textarea>
                                </div>
                                    @php
                                    $cat_count=0;

                                    @endphp
                                    @foreach($question_categories as $category)
                                        @php
                                        $q_count=0;
                                        $cat_count++;
                                        @endphp
                                        <h3>Part {{$cat_count}} - {{$category->name}}</h3>
                                        @foreach($category->questions as $question)
                                            @if($question->status==1)
                                                <div class="form-group">
                                                    <h4>{{$question->question}} {!! $question->compulsory==1?'<span class="text-danger">*</span>':'' !!}</h4>

                                                    @if($question->type=='txt')
                                                        <textarea name="question_{{$question->id}}" id="" cols="30" class="form-control" rows="10" {{$question->compulsory==1?'required':''}}></textarea>
                                                    @elseif($question->type=='rad')
                                                        <ul class="list-unstyled example">
                                                            @foreach($question->options as $option)
                                                                <li class="m-b-15">
                                                                    <input type="radio" name="question_{{$question->id}}" value="{{$option->id}}" class="icheckbox-primary" id="inputRadiosUnchecked"
                                                                           data-plugin="iCheck" data-radio-class="iradio_flat-blue">&nbsp;{{$option->value}}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @elseif($question->type=='chk')
                                                        <ul class="list-unstyled example">
                                                            @foreach($question->options as $option)
                                                                <li class="m-b-15">
                                                                    <input type="checkbox" name="question_{{$question->id}}[]" value="{{$option->id}}" class="icheckbox-primary" id="inputUnchecked" name="inputiCheckCheckboxes"
                                                                           data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue">&nbsp;{{$option->value}}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif


                                                </div>
                                            @endif

                                        @endforeach
                                    @endforeach

                            </div>



                        </div>
                        <div class="panel-footer">
                                @csrf
                            <input type="hidden" name="type" value="save_separation_form">
                            <input type="hidden" name="separation_id" value="{{$separation->id}}">
                            <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                            <button type="reset" class="btn btn-lg btn-warning" value="">Reset</button>
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

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ asset('global/vendor/icheck/icheck.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.input-daterange').datepicker({
                autoclose: true
            });
            $('.select2').select2();

            $(document).on('submit','#ExitForm',function(event){
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{url('separation')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){

                        toastr["success"]("Changes saved successfully",'Success');
                        $('#formbody').empty();
                        $('#formbody').html('<div class="jumbotron jumbotron-fluid">\n' +
                            '  <div class="container">\n' +
                            '    <h1 class="display-4">Form Submission Was Successful</h1>\n' +
                            '  </div>\n' +
                            '</div>');


                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
            var selected = [];

            $('.active-toggle').change(function() {
                var id = $(this).attr('id');
                var isChecked = $(this).is(":checked");
                console.log(isChecked);
                $.get(
                    '{{ route('workflows.alter-status') }}',
                    { id: id, status: isChecked },
                    function(data) {
                        if(data=="enabled"){
                            toastr.success('Enabled!', 'Workflow Status');
                        }
                        if(data=="disabled"){
                            toastr.error('Disabled!', 'Workflow Status')
                        }else{
                            toastr.error(data, 'Workflow Status');

                        }


                    }
                );

            });
            $('#user').select2({
                ajax: {
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    url: function (params) {
                        return '{{url('bsc/usersearch')}}';
                    }
                }
            });
        } );
    </script>
@endsection
