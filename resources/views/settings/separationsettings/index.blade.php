<div class="page-header">
    <h1 class="page-title">{{__('All Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item ">{{__('Separation Settings')}}</li>
        <li class="breadcrumb-item active">{{__('You are Here')}}</li>
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
        <div class="col-md-12 col-xs-12">

            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Separation Question Categories</h3>
                    <div class="panel-actions">

                        <button class="btn btn-info" data-toggle="modal" data-target="#addSQCModal">Add Separation Question Category</button>
                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                           data-query-params="queryParams" data-mobile-responsive="true"
                           data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 80%">Name:</th>
                            <th style="width: 20%">Action:</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($separation_question_categories as $sqc)
                            <tr>
                                <td>{{$sqc->name}}</td>
                                <td><a class="" title="edit" class="btn btn-icon btn-info" id="{{$sqc->id}}"
                                       onclick="prepareCEditData(this.id);"><i class="fa fa-pencil"
                                                                               aria-hidden="true"></i></a>
                                    <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$sqc->id}}"
                                       onclick="deleteSQC(this.id);"><i class="fa fa-trash"
                                                                          aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Separation Policy Settings</h3>
                    <div class="panel-actions">

                    </div>
                </div>
                <form id="separationPolicyForm" enctype="multipart/form-data">
                    <div class="panel-body">
                        <div class="col-md-6">
                            @csrf
                            <div class="form-group">
                                <h4>Does the employee fill an exit form?</h4>
                                <input type="checkbox" name="employee_fills_form" class="active-toggle bstoggle"
                                       {{$sp->employee_fills_form==1?'checked':''}} value="1">
                                <h4>Does the exit form pass through an approval process?</h4>
                                <input type="checkbox" name="use_approval_process" class="active-toggle bstoggle"
                                       {{$sp->use_approval_process==1?'checked':''}} value="1">
                                <h4>Is the employee salary prorated after separation?</h4>
                                <input type="checkbox" name="prorate_salary" class="active-toggle bstoggle"
                                       {{$sp->prorate_salary==1?'checked':''}} value="1">
                                <h4>Do you send notification of a staff exit to all staff?</h4>
                                <input type="checkbox" name="notify_staff_on_exit" class="active-toggle bstoggle"
                                       {{$sp->notify_staff_on_exit==1?'checked':''}} value="1">

                            <br>
                            <div class="form-group">
                                <h4>Approval Workflow</h4>
                                <select class="form-control" name="workflow_id">
                                    @forelse($workflows as $workflow)
                                        <option value="{{$workflow->id}}" {{$sp->workflow_id==$workflow->id?'selected':''}}>{{$workflow->name}}</option>
                                    @empty
                                        <option value="0">Please Create a Workflow</option>
                                    @endforelse

                                </select>
                            </div>
                            <input type="hidden" name=" type" value="save_separation_policy">

                        </div>

                    </div>
                    </div>
                    <div class="panel-footer">
                        <div class="form-group">
                            <button class="btn btn-info">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Separation Types</h3>
                    <div class="panel-actions">

                        <button class="btn btn-info" data-toggle="modal" data-target="#addSeparationTypeModal">Add Separation Type
                        </button>
                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                           data-query-params="queryParams" data-mobile-responsive="true"
                           data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 30%">Name:</th>
                            <th style="width: 20%">Action:</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($separation_types as $st)
                            <tr>
                                <td>{{$st->name}}</td>
                                <td><a class="" title="edit" class="btn btn-info" id="{{$st->id}}"
                                       onclick="prepareTEditData(this.id);"><i class="fa fa-pencil"
                                                                               aria-hidden="true"></i></a>
                                    <a class="" title="delete" class="btn btn-danger" id="{{$st->id}}"
                                       onclick="deleteSeparationType(this.id);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Separation Approval List Items</h3>
                    <div class="panel-actions">

                        <button class="btn btn-info" data-toggle="modal" data-target="#addSeparationApprovalListModal">Add Separation Approval List
                        </button>
                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                           data-query-params="queryParams" data-mobile-responsive="true"
                           data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 30%">Name:</th>
                            <th style="width: 20%">Action:</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($separation_approval_lists as $sal)
                            <tr>
                                <td>{{$sal->name}}</td>
                                <td><a class="" title="edit" class="btn btn-info" id="{{$sal->id}}"
                                       onclick="prepareALEditData(this.id);"><i class="fa fa-pencil"
                                                                               aria-hidden="true"></i></a>
                                    <a class="" title="delete" class="btn btn-danger" id="{{$sal->id}}"
                                       onclick="deleteSAL(this.id);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 col-xs-12"></div>
@include('settings.separationsettings.modals.addseparationquestioncategory')
{{-- edit holiday modal --}}
@include('settings.separationsettings.modals.editseparationquestioncategory')
{{-- Add Grade Modal --}}
@include('settings.separationsettings.modals.addseparationtype')
{{-- edit holiday modal --}}
@include('settings.separationsettings.modals.editseparationtype')
@include('settings.separationsettings.modals.addseparationapprovallist')
{{-- edit holiday modal --}}
@include('settings.separationsettings.modals.editseparationapprovallist')
<script type="text/javascript">
    $('.datepicker').datepicker({
        autoclose: true
    });
    $('.bstoggle').bootstrapToggle({
        on: 'Yes',
        off: 'No',
        onstyle: 'info',
        offstyle: 'default'
    });
    $(document).on('submit', '#separationPolicyForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{url('separation')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');

            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    $(document).on('submit', '#addSQCForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{url('separation')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');
                $('#addSQCModal').modal('toggle');
                {{--$( "#ldr" ).load('{{url('separation/settings')}}');--}}
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });
    $(document).on('submit', '#editSQCForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{url('separation')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');
                $('#editSQCModal').modal('toggle');
                 {{--$( "#ldr" ).load('{{url('separation/settings')}}');--}}
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    $(document).on('submit', '#addSeparationTypeForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{url('separation')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');
                $('#addSeparationTypeModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });
    $(document).on('submit', '#editSeparationTypeForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{url('separation')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');
                $('#editSeparationTypeModal').modal('toggle');
                {{--$( "#ldr" ).load('{{url('separation/settings')}}'); --}}
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    $(document).on('submit', '#addSeparationApprovalListForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{url('separation')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');
                $('#addSeparationApprovalListModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });
    $(document).on('submit', '#editSeparationApprovalListForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{url('separation')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                toastr.success("Changes saved successfully", 'Success');
                $('#editSeparationApprovalListModal').modal('toggle');
                {{--$( "#ldr" ).load('{{url('separation/settings')}}'); --}}
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    function prepareCEditData(separation_question_category_id) {
        $.get('{{ url('/separation/get_separation_question_category') }}' ,{separation_question_category_id:separation_question_category_id}, function (data) {

            console.log(data.date);
            $('#editcid').val(data.id);
            $('#editcname').val(data.name);
        });

        $('#editSQCModal').modal();
    }

    function prepareTEditData(separation_type_id) {
        $.get('{{ url('/separation/get_separation_type') }}' ,{separation_type_id:separation_type_id}, function (data) {
            // console.log(data);
            $('#edittid').val(data.id);
            $('#edittname').val(data.name);
        });
        $('#editSeparationTypeModal').modal();
    }
    function prepareALEditData(separation_approval_list_id) {
        $.get('{{ url('/separation/get_separation_approval_list') }}' ,{separation_approval_list_id:separation_approval_list_id}, function (data) {
            // console.log(data);
            $('#editalid').val(data.id);
            $('#editalname').val(data.name);
        });
        $('#editSeparationApprovalListModal').modal();
    }

    function deleteSQC(separation_question_category_id) {
        alertify.confirm('Are you sure you want to delete this Separation Question Category?', function () {


            $.get('{{ url('/separation/delete_separation_question_category') }}' ,{separation_question_category_id:separation_question_category_id}, function (data) {
                if (data == 'success') {
                    toastr["success"]("Separation Question Category deleted successfully", 'Success');
                    // $( "#ldr" ).load('{{url('separation/settings')}}');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Separation Question Category", 'Success');
                }

            });
        }, function () {
            alertify.error('Separation Question Category not deleted');
        });

}
    function deleteSAL(separation_approval_list_id) {
        alertify.confirm('Are you sure you want to delete this Separation Approval List Item?', function () {


            $.get('{{ url('/separation/delete_separation_approval_list') }}' ,{separation_approval_list_id:separation_approval_list_id}, function (data) {
                if (data == 'success') {
                    toastr["success"]("Separation Approval List Item deleted successfully", 'Success');
                    // $( "#ldr" ).load('{{url('separation/settings')}}');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Separation Approval List Item", 'Success');
                }

            });
        }, function () {
            alertify.error('Separation Approval List Item not deleted');
        });

    }

    function deleteSeparationType(separation_type_id) {
        alertify.confirm('Are you sure you want to delete this Separation Type?', function () {

            $.get('{{ url('/separation/delete_separation_type') }}' ,{separation_type_id:separation_type_id}, function (data) {

                if (data == 'success') {
                    toastr["success"]("Separation Type deleted successfully", 'Success');
                     {{--$( "#ldr" ).load('{{url('separation/settings')}}');--}}
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Separation Type", 'Success');
                }

            });
        }, function () {
            alertify.error('Separation Type not deleted');
        });
    }
</script>
