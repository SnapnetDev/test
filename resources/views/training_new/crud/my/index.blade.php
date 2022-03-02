@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
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
            <h1 class="page-title">My Training Feedback</h1>
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

            {{--include for approval--}}


            @include('training_new.crud.my.update')

            {{--include for budget--}}

            <div class="row" id="user_training_plan_module">


                <div class="col-md-12">


                    <div class="panel panel-info panel-line">
                        <div class="panel-heading main-color-bg">
                            <h3 class="panel-title">My Training Feedback</h3>
                        </div>

                        <div class="panel-body">

{{--table start--}}

                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Year Of Training
                                    </th>
                                    <th>
                                        Completed
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Date Created
                                    </th>
                                    <th>
                                        Actions
                                    </th>
                                </tr>
                                </tbody>
                            </table>

{{--table stop--}}
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

    @include('training_new.crud.js')

    <script type="text/javascript">


        //approvals
        //useTemplate

        $('#user_training_plan_module').crudTable({

            fetchUrl:function(){
                return '{{ route('user_training.show',['fetch-my-training']) }}';
            },
            updateUrl:function(data){
                return '{{ route('user_training.update',['']) }}/' + data.id;
            },
            createModalFormSelector:'#',
            editModalFormSelector:'#edit-feedback',
            onAppendRow:function(data){
              return `<tr>
                                    <td>
                                        ${data.training.name}
                                    </td>
                                    <td>
                                        ${data.year_of_training}
                                    </td>
                                    <td>
                                        ${data.completed? 'Yes' : 'Pending'}
                                    </td>
                                    <td>
                                        ${data.status? 'Enabled' : 'Paused'}
                                    </td>
                                    <td>
                                        ${data.created_at}
                                    </td>
                                    <td>
                                        <a id="feedback" href="#" class="btn btn-sm btn-success">Submit Feedback</a>
                                    </td>
                                </tr>`;
            },
            onSelectRow:function($el,data,openEditForm,remove){

                $el.find('#feedback').on('click',function () {

                    openEditForm();

                    return false;
                });

            },
            onFillForm:function($formEl,data){

                $formEl.find('#download').find('a').hide();

                if (data.upload1){
                    $formEl.find('#download').find('a').show();
                    $formEl.find('#download').find('a').attr('href','{{ asset('uploads/') }}/' + data.upload1);
                }

                $formEl.find('.mrating').find('span').each(function(){

                    if (data.rating == $(this).attr('data')){
                        $(this).addClass('selected');
                    }

                    $(this).on('click',function(){
                        // alert($(this).attr('data'));
                        $formEl.find('.mrating').find('span').removeClass('selected');
                        $('[name=rating]').val($(this).attr('data'));
                        $(this).addClass('selected');
                    });

                });

                if (data.training){
                    console.log(data);

                    $('[name=enroll_instructions]').html(data.training.enroll_instructions);
                    $('#access').hide();
                    if (data.training.type == 'online'){
                        $('#access').show();
                        $('#access').attr('href',data.training.resource_url);
                    }
                }



            }

        });



    </script>
@endsection