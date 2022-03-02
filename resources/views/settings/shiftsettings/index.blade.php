<div class="page-header">
    <h1 class="page-title">{{__('All Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item ">{{__('Leave Settings')}}</li>
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
                    <h3 class="panel-title">Shifts</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addShiftModal">Add Shift</button>
                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                           data-query-params="queryParams" data-mobile-responsive="true"
                           data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                        <thead>
                        <tr>
                            <th style="width: 30%">Name:</th>
                            <th style="width: 25%">StartTime:</th>
                            <th style="width: 25%">EndTime:</th>
                            <th style="width: 25%">Color:</th>
                            <th style="width: 20%">Action:</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($shifts as $shift)
                            <tr>
                                <td>{{$shift->type}}</td>
                                <td>{{date("h:i A",strtotime($shift->start_time))}}</td>
                                <td>{{date("h:i A",strtotime($shift->end_time))}}</td>
                                <td>{{$shift->color_code}}</td>
                                <td><a class="" style="cursor:pointer;" title="edit" class="btn btn-info" id="{{$shift->id}}"
                                       onclick="prepareSEditData(this.id);"><i class="fa fa-pencil"
                                                                               aria-hidden="true"></i></a>
                                    {{--<a class="" style="cursor:pointer;" title="Delete" class="btn btn-info" id="{{$shift->id}}"
                                       onclick="deleteShift(this.id);"><i class="fa fa-trash"
                                                                          aria-hidden="true"></i></a>--}}
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
<div class="col-md-12 col-xs-12">

</div>
@include('settings.shiftsettings.modals.addshift')
{{-- edit Qualification modal --}}
@include('settings.shiftsettings.modals.editshift')
<script type="text/javascript">
    $('.colorpicker-default').colorpicker({
        format: 'hex'
    });

    $('.datepicker').datepicker({
        autoclose: true
    });
    $('.bstoggle').bootstrapToggle({
        on: 'Yes',
        off: 'No',
        onstyle: 'info',
        offstyle: 'default'
    });

    $('#addShiftForm').on('submit', function (event) {
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        console.log(formdata);
        $.ajax({
            url: '{{route('shifts.store')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#addShiftModal').modal('toggle');
                $( "#ldr" ).load('{{route('shiftsettings')}}');
                //location.reload();

            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });
        return event.preventDefault();
    });
    $('#editShiftForm').on('submit', function (event) {
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        console.log(formdata);
        $.ajax({
            url: '{{route('shifts.store')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#editShiftModal').modal('toggle');
                $( "#ldr" ).load('{{route('shiftsettings')}}');
                //location.reload();

            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });
        return event.preventDefault();
    });

    function prepareSEditData(shift_id) {
        $.get('{{ url('/settings/shift') }}/' + shift_id, function (data) {
            // console.log(data);
            $('#editsid').val(data.id);
            $('#editstype').val(data.type);
            $('#editsstart_time').val(data.start_time);
            $('#editsend_time').val(data.end_time);
            $('#editcolor_code').val(data.color_code);
        });
        $('#editShiftModal').modal();
    }

    function deleteShift(shift_id) {
        alertify.confirm('Are you sure you want to delete this Shift?', function () {
            $.get('{{ url('/settings/shifts/delete') }}/' + shift_id, function (data) {
                if (data == 'success') {
                    toastr["success"]("Shift deleted successfully", 'Success');
                    $( "#ldr" ).load('{{route('shiftsettings')}}');
                } else {
                    toastr["error"]("Error deleting Shift", 'Success');
                }

            });
        }, function () {
            alertify.error('Shift not deleted');
        });
    }
</script>