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


    @include('training_new.rating_style')

@endsection
@section('content')
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">Training Plan.</h1>
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

            @include('training_new.crud.create')
            @include('training_new.crud.update')


            @include('training_new.crud.approvals.index')
            @include('training_new.crud.budget.index')

            @include('training_new.crud.enrollees.index')


            {{--include for budget--}}

            <div class="row" id="training_plan_module">


                <div class="col-md-12">

                    <div align="right" style="margin-bottom: 10px;">
                        @usercan(upload_training_budget)
                          <a data-toggle="modal" data-target="#budget" href="#" class="btn btn-sm btn-primary">Setup Training Budget Per Department</a>
                        @endusercan
                        <a data-create-form href="#" class="btn btn-sm btn-success">+ Add Training Plan</a>
                    </div>



                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading main-color-bg">
                            <h3 class="panel-title">Manage Training Plan</h3>
                        </div>

                        <div class="panel-body">

                            <div class="col-md-12">

                                <div class="col-md-12" id="training_filter_container" style="padding: 0;">

                                    <div class="row" style="margin-bottom: 20px;">


                                        <div class="col-md-3">

                                            <label for="">Filter Department</label>
                                            <select  id="filter_department" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                 @endforeach
                                            </select>


                                        </div>


                                        <div class="col-md-3">

                                            <label for="">Filter Role</label>
                                            <select  id="filter_role" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>


                                        </div>



                                        <div class="col-md-3">

                                            <label for="">Filter Group</label>
                                            <select  id="filter_group" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($groups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>


                                        </div>



                                    </div>


                                </div>


                            </div>



                            <table class="table table-stripped" id="">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Cost Per Head</th>
                                    <th>Number Of Enrollees</th>
                                    <th>Grand Total</th>
                                    <th>Start Date</th>
                                    <th>Stop Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                {{--<tbody>--}}
                                {{--</tbody>--}}

                            </table>
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


        //#training_filter_container
    $('#create,#edit,#budget-create,#edit-budget').each(function(){

        //filter_department
        //,#filter_department
        $(this).find('[name=dep_id]').selectData({
            url:'{{ route('offline_training.show',['fetch-departments']) }}',
            key:'id',
            label:'name'
        });

        {{--$(this).find('#filter_department').selectData({--}}
            {{--url:'{{ route('offline_training.show',['fetch-departments']) }}',--}}
            {{--key:'id',--}}
            {{--label:'name'--}}
        {{--});--}}

        //filter_role
        {{--$(this).find('#filter_role').selectData({--}}
            {{--url:'{{ route('offline_training.show',['fetch-roles']) }}',--}}
            {{--key:'id',--}}
            {{--label:'name'--}}
        {{--});--}}


        {{--$(this).find('#filter_group').selectData({--}}
            {{--url:'{{ route('offline_training.show',['fetch-groups']) }}',--}}
            {{--key:'id',--}}
            {{--label:'name'--}}
        {{--});--}}


        //filter_department
//,#filter_role
        $(this).find('[name=role_id]').selectData({
            url:'{{ route('offline_training.show',['fetch-roles']) }}',
            key:'id',
            label:'name'
        });

        //,#filter_group

        $(this).find('#group-select').selectData({
            url:'{{ route('offline_training.show',['fetch-groups']) }}',
            key:'id',
            label:'name'
        });

        $(this).find('#group-select').selectAppend({
            transform:function(data){
                return `<span style="
    background-color: #eee;
    display: inline-block;
    padding: 3px;
    border: 1px solid #ddd;
    margin: 4px;
    border-radius: 5px;
">${data.label} <input type="hidden" value="${data.value}" data-input name="group_id[]" data-array />  </span>`;
            },
            $elOther:$(this).find('#el-other')
        });



    });


    var $training = $('#training_plan_module').crudTable({

        createModalFormSelector:'#create',

        editModalFormSelector:'#edit',

        fetchUrl:function(){
          return '{{ route('offline_training.show',['fetch']) }}';
        },
        createUrl:function(){
          return '{{ route('offline_training.store') }}';
        },
        updateUrl:function(data){
          return '{{ route('offline_training.update',['']) }}/' + data.id;
        },
        deleteUrl:function(data){
            return '{{ route('offline_training.destroy',['']) }}/' + data.id;
        },
        onFillForm:function($formEl,data){

            function fn(){
                $formEl.find('#resource_url_container').hide();
                if ($formEl.find('[name=type]').is(':checked')){
                    $formEl.find('#resource_url_container').show();
                }
            }

            function fnCalc(){

                var $cost_per_head = $formEl.find('[name=cost_per_head]');
                var $number_of_enrollees = $formEl.find('[name=number_of_enrollees]');
                var $grand_total = $formEl.find('[name=grand_total]');

                $grand_total.val( ($cost_per_head.val() * $number_of_enrollees.val()).toLocaleString() );
            }

            $formEl.find('[name=cost_per_head],[name=number_of_enrollees]').on('keyup',function(){

                fnCalc();

            });


            // alert('Called');
            console.clear();
            // console.log($formEl);

            $formEl.find('[name=type]').on('click',function(){
                fn();
            });

            fn();


            if (data){

                LoadApproval($formEl.find('#approval-hist'),data);

                fnCalc();

                if (data.training_groups){

                    $formEl.find('#group-select').val('');
                    $formEl.find('#group-select').trigger('change');


                    data.training_groups.forEach(function(v,k){

                        $formEl.find('#group-select').val(v.group_id);
                        $formEl.find('#group-select').trigger('change');

                    });

                }

                if (data.type == 'online'){
                    $formEl.find('[name=type]').trigger('click'); //prop('checked',true);
                }

                if (data.type == 'onffline'){
                    if ($formEl.find('[name=type]').is(':checked')){
                        $formEl.find('[name=type]').trigger('click'); //prop('checked',true);
                    }
                }



            }


        },
        onAppendRow:function(data){

            return `<tr>
                                    <td>${data.name}</td>
                                    <td>${data.cost_per_head}</td>
                                    <td>${data.number_of_enrollees}</td>
                                    <td>${(+data.grand_total).toLocaleString()}</td>
                                    <td>${data.train_start}</td>
                                    <td>${data.train_stop}</td>
                                    <td>${data.status}</td>
                                    <td>
                                       <a href="#" id="edit" class="btn btn-sm btn-success">Edit</a>
                                       <a href="#" id="approval" class="btn btn-sm btn-warning">Approvals</a>
                                       <a href="#" id="enrolled" class="btn btn-sm btn-primary">Enrolled-Users</a>
                                       <a href="#" id="remove" class="btn btn-sm btn-danger">Remove</a>
                                    </td>
                                </tr>`;

        },
        onSelectRow:function($el,data,showForm,deleteAction){

            $el.find('#enrolled').on('click',function(){

                //loadEnrollees
                loadEnrollees(data);

                return false;

            });


            $el.find('#edit').on('click',function(){
                showForm();
                //approval-hist


            });

            $el.find('#remove').on('click',function(){
                if (confirm('Do you want to remove this record?')){
                    deleteAction();
                }
            });

            $el.find('#approval').on('click',function(){


                $('#approval').modal();
                LoadApproval($('#approval').find('#approval-outlet'),data);

            });

        }

    });

    $training.hook('filter_container',function () {

        return $('#training_filter_container');

    }).hook('filter',function($el){


        var filters = [];

        if ($el.find('#filter_department').val()){
            filters.push('dep_id=' + $el.find('#filter_department').val());
        }

        if ($el.find('#filter_role').val()){
            filters.push('role_id=' + $el.find('#filter_role').val());
        }

        if ($el.find('#filter_group').val()){
            filters.push('group_id=' + $el.find('#filter_group').val());
        }

        if (filters.length){
            return '?' + filters.join('&');
        }

        return '';

    }).hook('filter_init',function($el,query){

        $el.find('#filter_department,#filter_role,#filter_group').on('change',function() {

            query();

        });


        console.log($el);

        // alert('filter_init');


    }).hook('init')();


    //approvals
    //useTemplate
    function LoadApproval($sel,data){



        $('#approval-template').useTemplate().mount($sel).crudTable({

             editModalFormSelector:'#edit-approval',

             fetchUrl:function(){
                return '{{ route('offline_training_approval.index') }}?id=' + data.id;
             },
             updateUrl:function($data){
                return '{{ route('offline_training_approval.update',['']) }}/' + $data.id;
             },
             onAppendRow:function($data){
               return  `      <tr>
                          <td>
                              ${$data.stage.name}
                          </td>
                          <td>
                             ${$data.status == 1? 'Approved' : 'Pending'}
                          </td>
                          <td>
                              ${$data.approver? $data.approver.name : 'Pending'}
                          </td>
                          <td>
                              ${$data.status == 1? $data.updated_at : 'Pending'}
                          </td>
                          <td>
                              ${$data.created_at}
                          </td>
                          <td>
                             <a href="" id="approve-btn" class="btn btn-success btn-sm">Approve</a>
                             <span style="color: green;cursor: pointer;" id="passed">Passed</span>
                          </td>
                      </tr>`;
             },
             onSelectRow:function($el,$data,showEditForm,removeRec){

                 $el.find('#approve-btn').show();

                 $el.find('#approve-btn').on('click',function () {
                     showEditForm();
                     return false;
                 });

                 $el.find('#passed').hide();

                 $el.find('#passed').on('click',function () {
                    showEditForm();
                 });

                 if ($data.status == 1){
                     $el.find('#passed').show();
                     $el.find('#approve-btn').hide();
                 }

                 if (!$data.canApprove_){
                     $el.find('#approve-btn').hide();
                 }


             },
             onFillForm:function($formEl,$data){

                 $formEl.find('#submit').show();

                 if ($data.status == 1){
                     $formEl.find('#submit').hide();
                 }

             },
            onAjaxFinished:function(){
                $training.refresh();
            }

         });


    }



    function LoadBudget(){

        $('#budget-template').useTemplate().mount($('#budget-outlet'));


        $('[name=grade_id]').each(function(){
            $(this).selectData({
                url:'{{ route('training_budget.show',['fetch-grades']) }}',
                key:'id',
                label:'level'
            });
        });


        $('#budget').crudTable({

            createModalFormSelector:'#budget-create',
            editModalFormSelector:'#edit-budget',

            fetchUrl:function(){
                return '{{ route('training_budget.index') }}';
            },
            updateUrl:function(data){
                return '{{ route('training_budget.update',['']) }}/' + data.id;
            },
            createUrl:function(){
                return '{{ route('training_budget.store') }}';
            },
            deleteUrl:function(data){
                return '{{ route('training_budget.destroy',['']) }}/' + data.id;
            },

            onAppendRow:function(data){

                return `<tr>
                          <td>
                              ${data.department.name}
                          </td>
                          <td>
                              ${data.training_budget_name}
                          </td>
                          <td>
                              ${data.allocation_total}
                          </td>
                          <td>
                              ${data.year_of_allocation}
                          </td>
                          <td>
                               <a href="#" id="edit" class="btn btn-sm btn-info">Edit</a>
                               <a href="#" id="remove" class="btn btn-sm btn-danger">Remove</a>
                          </td>
                      </tr>`;

            },
            onSelectRow:function($el,data,showEditForm,removeRecord){

                $el.find('#edit').on('click',function(){
                    showEditForm();
                    return false;
                });

                $el.find('#remove').on('click',function () {
                    if (confirm('Do you want to remove this record?')){
                        removeRecord();
                    }
                    return false;
                });

            },
            onFillForm:function($formEl,data){

                //Todo code here...

            }


        });


    }


    LoadBudget();




    var $enrollee = $('#enrollee-template').mv2m();
    $enrollee.hook('storeUrl',function(){

    }).hook('updateUrl',function(data){

    }).hook('container',function () {

        return 'table';

    }).hook('editModal',function ($el) {

        return $el.find('#edit-container');

    }).hook('createModal',function ($el) {

        return $el.find('#create-container');

    }).hook('onAppendRow',function($data){

        return  `<tr>
                          <td>
                              ${$data.user.name}
                          </td>
                          <td>
                             ${$data.user.email}
                          </td>
                          <td>
                              ${$data.completed? 'Completed' : 'In-progress'}
                          </td>
                          <td>
                             <a href="" id="detail" class="btn btn-success btn-sm">Detail</a>
                          </td>
                 </tr>`;
    }).hook('onSelectRow',function (findGlobal,findLocal,data,showEditForm,submitForm) {

        // console.log(findGlobal,findLocal,data,showEditForm,submitForm);

        findLocal('#detail').on('click',function(){

            showEditForm();

            return false;
        });

    }).hook('onFillForm',function ($formEl,data) {

        console.log(data);
        // for (var i in data){
        //     console.log(i);
        // }

        $formEl.find('[name=completed]').prop('checked',false);
        if (data.completed){
            $formEl.find('[name=completed]').prop('checked',true);
        }



        $formEl.find('.mrating').find('span[data]').each(function(k,$el){

            var index = k + 1;
            var checkIndex = +data.rating;
            $(this).removeClass('selected');
            if ($(this).attr('data')*1 == checkIndex){
               $(this).addClass('selected');
            }

        });

        $formEl.find('#download').find('a').attr('href',`{{ asset('uploads') }}/${data.upload1}`);


    }).mount(function($el){
        $('body').append($el);
    });

    function loadEnrollees(data){
        // $('#enrollee').modal();


        $enrollee.hook('before.indexUrl',function($el){

            $el.find('#title').html(`Enrolled Users (${data.name})...`);

        }).hook('storeUrl',function(){

            return  `{{ route('user_training.store') }}?training_plan_id=${data.id}&type=enroll`;

        });


        $enrollee.hook('indexUrl',function($urlQuery){
            console.log($urlQuery);
            return `{{ route('user_training.show',['enrolled-users']) }}?training_id=${data.id}`;
        });

        $enrollee.el().find('#enrollee').modal();
        $enrollee.init().fetch();

        console.log(data);

        // $('#enrollee').find('#title').html(`Enrolled Users (${data.name})`);

    }



    </script>
@endsection