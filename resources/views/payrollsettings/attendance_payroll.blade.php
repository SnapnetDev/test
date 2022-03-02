<div class="page-header">
    <h1 class="page-title">{{__('All Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item ">{{__('Payroll Settings')}}</li>
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
                    <h3 class="panel-title">Attendance Based Payroll Settings</h3>
                    <div class="panel-actions">


                    </div>
                </div>
                <form id="editAttendancePayrollForm" enctype="multipart/form-data">
                    <div class="panel-body">
                        <div class="col-md-12">
                            @csrf
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <h4>Pay Early?</h4>
                                        <input type="checkbox" name="pay_early" class="active-toggle bstoggle"
                                               {{$attendance_payroll_settings['pay_early']==1?'checked':''}} value="1">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <h4>Pay Late?</h4>
                                        <input type="checkbox" name="pay_late" class="active-toggle bstoggle"
                                               {{$attendance_payroll_settings['pay_late']==1?'checked':''}} value="1">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <h4>Pay Off?</h4>
                                        <input type="checkbox" name="pay_off" class="active-toggle bstoggle"
                                               {{$attendance_payroll_settings['pay_off']==1?'checked':''}} value="1">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <h4>Pay Absent?</h4>
                                        <input type="checkbox" name="pay_absent" class="active-toggle bstoggle"
                                               {{$attendance_payroll_settings['pay_absent']==1?'checked':''}} value="1">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4>Pay Full Basic if Total Days greater than </h4>
                                        <input type="text" name="pay_full_days" class="form-control"
                                               value="{{$attendance_payroll_settings['pay_full_days']}}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4>Days to divide Basic by to get Daily Pay</h4>
                                        <input type="text" name="divide_by_days" class="form-control"
                                               value="{{$attendance_payroll_settings['divide_by_days']}}">
                                    </div>
                                </div>

                            </div>
                            {{--
                            <div class="form-group">
                                <h4>Does Your payroll process require an approval?</h4>
                                <input type="radio" id="uses_approval_yes"
                                       {{$pp->uses_approval==1?'checked':''}} name="uses_approval" value="1"> Yes
                                <input type="radio" id="uses_approval_no"
                                       {{$pp->uses_approval==0?'checked':''}} name="uses_approval" value="0"> No
                            </div>
                            <div class="form-group">
                                <h4>Approval Workflow</h4>
                                <select class="form-control" name="workflow_id">
                                    @forelse($workflows as $workflow)
                                        <option value="{{$workflow->id}}" {{$pp->workflow_id==$workflow->id?'selected':''}}>{{$workflow->name}}</option>
                                    @empty
                                        <option value="0">Please Create a Workflow</option>
                                    @endforelse

                                </select>
                            </div>--}}


                        </div>

                    </div>
                    <div class="panel-footer">
                        <div class="form-group">
                            <button class="btn btn-info">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- End Page -->
<script type="text/javascript">
    $(function () {
        $('.bstoggle').bootstrapToggle({
            on: 'Yes',
            off: 'No',
            onstyle: 'info',
            offstyle: 'default'
        });
    });


    $(function() {
        $('#editAttendancePayrollForm').submit(function(event){
            event.preventDefault();
            var form = $(this);
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url         : '{{route('attendancepayrollsettings.store')}}',
                data        : formdata ? formdata : form.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                success     : function(data, textStatus, jqXHR){

                    toastr.success("Changes saved successfully",'Success');

                    $( "#ldr" ).load('{{url('payrollsetting/attendance_payroll_setting')}}');
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
    });

    $(function () {
        $('.sc-status').on('change', function () {
            lateness_policy_id = $(this).attr('id');

            $.get('{{ url('/payrollsettings/change_lateness_policy_status') }}/', {lateness_policy_id: lateness_policy_id}, function (data) {
                if (data == 1) {
                    toastr.success("Lateness Policy Enabled", 'Success');
                }
                if (data == 2) {
                    toastr.warning("Lateness Policy Disabled", 'Success');
                }
                $("#ldr").load('{{url('payrollsettings/payroll_policy')}}');
            });
        });
        $('.sc-display').on('change', function () {
            specific_salary_component_type_id = $(this).attr('id');

            $.get('{{ url('/payrollsettings/change_specific_salary_component_type_display') }}/', {specific_salary_component_type_id: specific_salary_component_type_id}, function (data) {
                if (data == 1) {
                    toastr.success("Specific Salary Component Type Enabled", 'Success');
                }
                if (data == 2) {
                    toastr.warning("Specific Salary Component Type Disabled", 'Success');
                }
                $("#ldr").load('{{url('payrollsettings/payroll_policy')}}');
            });
        });


        $('#lps').on('change', function () {

            $.get('{{ url('/payrollsettings/switch_lateness_policy') }}/', function (data) {
                if (data == 1) {
                    toastr.success("Lateness Policy Enabled", 'Success');
                }
                if (data == 2) {
                    toastr.warning("Lateness Policy Disabled", 'Success');
                }
                $("#ldr").load('{{url('payrollsettings/payroll_policy')}}');
            });
        });
        $('#use_office').on('change', function () {

            $.get('{{ url('/payrollsettings/switch_office_payroll_policy') }}/', function (data) {
                if (data == 1) {
                    toastr.success("Office Payroll Usage Enabled", 'Success');
                }
                if (data == 2) {
                    toastr.warning("Office Payroll Usage Disabled", 'Success');
                }
                $("#ldr").load('{{url('payrollsettings/payroll_policy')}}');
            });
        });
        $('#use_tmsa').on('change', function () {

            $.get('{{ url('/payrollsettings/switch_tmsa_payroll_policy') }}/', function (data) {
                if (data == 1) {
                    toastr.success("TMSA Payroll Usage Enabled", 'Success');
                }
                if (data == 2) {
                    toastr.warning("TMSA Payroll Usage Disabled", 'Success');
                }
                $("#ldr").load('{{url('payrollsettings/payroll_policy')}}');
            });
        });
        $('#use_project').on('change', function () {

            $.get('{{ url('/payrollsettings/switch_project_payroll_policy') }}/', function (data) {
                if (data == 1) {
                    toastr.success("Project Payroll Usage Enabled", 'Success');
                }
                if (data == 2) {
                    toastr.warning("Project Payroll Usage Disabled", 'Success');
                }
                $("#ldr").load('{{url('payrollsettings/payroll_policy')}}');
            });
        });
    });

</script>

