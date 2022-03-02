<div class="page-header">
    <h1 class="page-title">{{__('All Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item ">{{__('Employee Settings')}}</li>
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
<div class="page-content container-fluid" >
    <div class="row">
        <div class="col-md-12">
    <div class="panel panel-info panel-line">
        <div class="panel-heading">
            <h3 class="panel-title">Performance Notification Settings</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <form id="savePermance">
                    <table class="table">
                        <tr>
                            <td>
                                Performance Review Frequency
                            </td>
                            <td>
                                <select id="review" class="form-control">

                                    <option value="1">Monthly</option>
                                    <option value="3">Quarterly</option>
                                    <option value="6">Bienially</option>
                                    <option value="12">Yearly</option>


                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Review Reminder Message
                            </td>
                            <td>
                                <textarea class="form-control" id="reminderMessage"
                                          placeholder="Review Reminder Message"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Enable and Disable
                            </td>
                            <td>
                                <li class="list-inline-item m-r-25 m-b-25">
                                    <select id="reviewStart" class="form-control">

                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </li>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Settings Applies to
                            </td>
                            <td>
                                <br>
                                <li class="list-inline-item m-r-25 m-b-25">
                                    <select id="company_id"
                                            {{Auth::user()->role->permissions->contains('constant','performance_settings_all_company') || Auth::user()->role->permissions->contains('constant','group_access')? '' : 'disabled' }}  name="company_id"
                                            class="form-control">
                                        <option value="0">All Company</option>
                                        @foreach($companys as $company)
                                            <option
                                                {{$company->id==Auth::user()->comapny_id ? 'selected' : ''}} value="{{$company->id}}">{{$company->name}}</option>

                                        @endforeach
                                    </select>
                                </li>
                            </td>
                        </tr>
                    </table>

                </form>
                <button id="saveSettings" class="btn btn-info btn-md pull-right">Save</button>
            </div>
        </div>
    </div>

    <div class="panel panel-info panel-line">
        <div class="panel-heading">
            <h3 class="panel-title">Organization Goals Settings</h3>
            <div class="panel-actions">
                @if(is_null($published))
                    <button class="btn btn-success" id="Publish">Publish</button>
                @endif

                <button class="btn btn-info" id="addpilot">Add Organizational Goal</button>

            </div>
        </div>
        <div class="panel-body"><br>
            <div class="panel-group  panel-group-continuous" id="exampleAccordionContinuous" aria-multiselectable="true"
                 role="tablist">
                @if(count($pilots)>0)
                    @foreach($pilots as $pilot)
                        <div class="panel">

                            <div class="panel-heading" id="exampleHeadingContinuousThree" role="tab">
                                <div class="ribbon ribbon-bookmark ribbon-danger">
                                    <span class="ribbon-inner" style="cursor:pointer"
                                          onclick="deletes('{{$pilot->id}}')">{{('Delete')}}</span>
                                </div>
                                <div class="ribbon ribbon-bookmark ribbon-reverse ribbon-success">
                                    <span class="ribbon-inner" style="cursor:pointer"
                                          onclick='modify("{{$pilot->id}}","{{$pilot->objective}}","{{htmlspecialchars($pilot->commitment)}}")'>{{('Modify')}}</span>
                                </div>
                                <br><Br>
                                <a class="panel-title collapsed" data-parent="#exampleAccordionContinuous"
                                   data-toggle="collapse" href="#exampleCollapseContinuousThree{{$pilot->id}}"
                                   aria-controls="exampleCollapseContinuousThree{{$pilot->id}}" aria-expanded="false">
                                    <h4>Objective:</h4> {{$pilot->objective}}
                                </a>
                            </div>
                            <div class="panel-collapse collapse" id="exampleCollapseContinuousThree{{$pilot->id}}"
                                 aria-labelledby="exampleCollapseContinuousThree{{$pilot->id}}" role="tabpanel"
                                 aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    <h4>Commitment:</h4><br>{{$pilot->commitment}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3>{{('No Key Goals Has Been Set for this Year')}}</h3>
                @endif
            </div>

            <div id="addpilotcontrol">
                <div class="example-wrap">
                    <h4 class="example-title">{{('Objective')}}</h4>
                    <input type="hidden" id="type"/>
                    <input type="hidden" id="id" value="0"/>
                    <textarea class="form-control" placeholder="Enter Objective" id="objective" rows="5"></textarea>
                    <br>
                    <h4 class="example-title">{{('Commitment')}}</h4>
                    <textarea class="form-control" id="commitment" placeholder="Enter Commitment"
                              rows="5"></textarea><br>

                    <button type="button" id="savepilot" class="btn btn-primary"><i
                            class="fa fa-save"></i>&nbsp;&nbsp;{{('Save Goal')}}</button>
                    <button type="button" id="cancelpilot" class="btn btn-danger"><i class="fa fa-ban"></i>&nbsp;&nbsp;
                        Cancel
                    </button>
                </div>
            </div>


        </div>
    </div>
        </div>
    </div>

<script>

    function modify(id, objective, commitment) {

        $('#type').val(2);
        id = $('#id').val(id);
        objective = $('#objective').val(objective);
        commitment = $('#commitment').val(commitment);

        $('#addpilotcontrol').show(1000);

    }

    function deletes(id) {

        swal({
                title: "{{('Are you sure?')}}",
                text: "{{('You will not be able to recover this goal!')}}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{('Yes, delete it!')}}",
                closeOnConfirm: false
            },
            function () {
                formData = {
                    id: id,
                    _token: '{{csrf_token()}}',
                    type: 'deleteGoal'
                }
                $.post("{{url('performances')}}", formData, function (data, status, xhr) {

                    if (data.status == 'success') {

                        swal("Deleted!", "{{('Pilot Goal Deleted.')}}", "success");

                        $("#ldr").load(sessionStorage.getItem('href'));
                    } else {
                        toastr.error("{{('Some Error Occurred')}}");
                    }
                });

            });
    }

    $(function () {
        @if(isset($performanceSettings) && count($performanceSettings)>0)
        $('#review').val("{{$performanceSettings['reviewFreq']}}");
        $('#reminderMessage').val("{{$performanceSettings['reminderMessage']}}");
        $('#reviewStart').val("{{$performanceSettings['reviewStart']}}");
        @endif
        $('#saveSettings').click(function () {
            $('#savePermance').trigger('submit');
        })

        $('#savePermance').submit(function (e) {
            e.preventDefault();
            review = $('#review').val();
            reminderMessage = $('#reminderMessage').val();
            reviewStart = $('#reviewStart').val();
            company_id = $('#company_id').val();

            formData = {
                reviewFreq: review,
                reminderMessage: reminderMessage,
                _token: '{{csrf_token()}}',
                company_id: company_id,
                reviewStart: reviewStart,
                type: 'saveSettings'
            }
            console.log(formData);

            $.post('{{url('performances')}}', formData, function (data, status, xhr) {

                if (data.status == 'success') {
                    $("#ldr").load(sessionStorage.getItem('href'));
                    toastr.success(data.message);
                    return;
                }
                return toastr.error(data.message);
            });
        });

        $('#addpilotcontrol').hide();
        @if(is_null($published))
        $('#Publish').click(function () {

            swal({
                    title: "{{('Are you sure?')}}",
                    text: "{{('You want to Publish Goals!')}}",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "{{('Yes, Publish it!')}}",
                    closeOnConfirm: false
                },
                function () {
                    $.post('{{url('performances')}}', {
                        type: 'publishPilot',
                        _token: '{{csrf_token()}}'
                    }, function (data, status, xhr) {

                        if (data.status == 'success') {
                            swal("Deleted!", "{{('Pilot Goal Published.')}}", "success");
                            toastr.success("{{('Pilot Goal Published.')}}");
                            $("#ldr").load(sessionStorage.getItem('href'));
                            return;
                        }
                        toastr.error(data.message);
                    })

                });

        })
        @endif

        $('#addpilot').click(function () {
            $('#type').val(1);
            id = $('#id').val(0);
            objective = $('#objective').val("");
            commitment = $('#commitment').val("");

            $('#addpilotcontrol').show(1000);

        });

        //cancel pilot goal
        $('#cancelpilot').click(function () {
            $('#addpilotcontrol').hide(1000);


        });


        $('#savepilot').click(function () {

            type = $('#type').val();
            id = $('#id').val();
            objective = $('#objective').val();
            commitment = $('#commitment').val();
            if (objective == "" || commitment == "") {

                toastr.error("{{('Please Fill all fields')}}");
                return;
            }
            $.post('{{url('performances')}}', {
                type: type,
                id: id,
                objective: objective,
                commitment: commitment,
                type: 'addPilot',
                _token: '{{csrf_token()}}'

            }, function (data, status, xhr) {

                if (data.status == 'success') {
                    if (type == 1) {


                        toastr.success("{{('Successfully Add Pilot Goal')}}");
                    } else {

                        toastr.success("{{('Successfully Modified Pilot Goal')}}");
                    }

                    $("#ldr").load(sessionStorage.getItem('href'));
                    return;
                }
                toastr.error(data.message);


            });
        });

    });

</script>
