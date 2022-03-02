@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">


    <style media="screen">
        #stgcont{
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
            <h1 class="page-title">360 Review Peer Nomination</h1>
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
                    <div class="panel panel-info ">
                        <div class="panel-heading main-color-bg">
                            <h3 class="panel-title">Nominate Colleagues for Peer Review </h3>
                        </div>
                        <form id="addNomineeForm" class="form-horizontal" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="add_nominees">

                            <input type="hidden" name="mp_id" value="{{$mp->id}}">
                            <input type="hidden" name="user_id" value="{{$employee->id}}">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul id="stgcont">
                                            @foreach($nominations as $nomination )
                                                <li>
                                                    <div class="form-cont" >
                                                        <div class="form-group users-div">
                                                            <div class="col-md-12">
                                                                <label for="">Employee Name</label>
                                                            </div>
                                                            <div class="col-md-10">
                                                            <select class="form-control users " name="nominee_id[]"  >
                                                                @forelse ($users as $user)
                                                                    <option {{$nomination->nominee_id==$user->id?"selected":""}} value="{{$user->id}}">{{$user->name}}</option>
                                                                @empty
                                                                    <option value="">No Users Created</option>
                                                                @endforelse
                                                            </select>
                                                            </div>
                                                            <button type="button" class="btn btn-icon btn-danger " id="remStage"><i class="fa fa-close"></i></button>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endforeach

                                        </ul>
                                        <button type="button" id="addStage" name="button" class="btn btn-primary">Add Nominee</button>
                                        <button type="submit"  class="btn btn-primary pull-right">Save Nominations</button>
                                    </div>
                                </div>

                            </div>


                        </form>
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

    <script type="text/javascript">
        var dup=0;
        $(document).ready(function() {
            $('.users').select2();


            var stgcont = $('#stgcont');
            var i = $('#stgcont li').length + 1;

            $('#addStage').on('click', function() {
                //console.log('working');
                if(i>=6){
                    toastr["error"]("You cannot add more that 5 persons",'Error');
                    return false;
                }
                $(' \n' +
                    '    <li><div class="form-cont" ><div class="form-group users-div"><div class="col-md-12"><label for="">Employee Name</label></div><div class="col-md-10"><select class="form-control users " name="nominee_id[]"  >@forelse ($users as $user)<option value="{{$user->id}}">{{$user->name}}</option>@empty<option value="">No Users Created</option>@endforelse </select></div> <button type="button" class="btn btn-icon btn-danger " id="remStage"><i class="fa fa-close"></i></button> </div> </div> </li>\n' +
                    '\n').appendTo(stgcont);
                //console.log('working'+i);
                $('#stgcont li').last().find('.users-div').find('.users').select2();
                i++;
                return false;
            });

            $(document).on('click',"#remStage",function() {

                //console.log('working'+i);
                if( i > 1 ) {
                    console.log('working'+i);
                    $(this).parents('li').remove();
                    i--;
                }
                return false;
            });

            $(document).on('submit','#addNomineeForm',function(event){
                event.preventDefault();

                var arr=[];
                var arr2=[];
                var numbers = document.getElementsByName("nominee_id[]");
                for (j = 0; j < numbers.length; j++) {
                    arr.push(numbers[j].value);
                    arr2.push(numbers[j].value);
                }
                dup=0;
                console.log(arr);
                arr.forEach(checkDuplicateNominee);
                console.log(arr2);
                console.log(arr);
                if(dup!=0){
                    toastr["error"]("An employee has been selected more than once",'Error');

                    return false;
                }else if($('#stgcont li').length<1){
                    toastr["error"]("Please Select at least one employee",'Error');

                    return false;
                }else  if(arr2.includes("{{$employee->id}}")){
                    toastr["error"]("Employee cannot nominate self",'Error');
                    return false;
                }else {

                    var form = $(this);
                    var formdata = false;
                    if (window.FormData) {
                        formdata = new FormData(form[0]);
                    }
                    $.ajax({
                        url: '{{url('e360')}}',
                        data: formdata ? formdata : form.serialize(),
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function (data, textStatus, jqXHR) {

                            toastr["success"]("Changes saved successfully", 'Success');

                            // location.reload();
                        },
                        error: function (data, textStatus, jqXHR) {
                            jQuery.each(data['responseJSON'], function (i, val) {
                                jQuery.each(val, function (i, valchild) {
                                    toastr["error"](valchild[0]);
                                });
                            });
                        }
                    });
                    return event.preventDefault();
                }
            });

        });
        function checkDuplicateNominee(value,index,array) {
            array.splice(index, 1);
            if(array.includes(value)){
                dup+=1;

            }
        }

    </script>


@endsection
