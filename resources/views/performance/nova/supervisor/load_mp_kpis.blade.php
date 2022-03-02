<div class="panel panel-info panel-line">
    <div class="panel-heading">
        <h3 class="panel-title">Staff: {{ $np_user->user->name }} <br>
            <b>Measurement Period:</b> {{ $measurement_period->name }} <br><br>
            <b>From:</b> {{ $measurement_period->from }} <br>
            <b>To:</b> {{ $measurement_period->to }}. <br>
            <b>Status:</b> {{ $np_user->status }} <br>
            <b>TOTAL Score:</b> {{ $np_user->score }} <br>
        </h3>
            <div class="panel-actions">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline btn-info dropdown-toggle" id="exampleGroupDrop2" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exampleGroupDrop2" role="menu">
                        <a class="dropdown-item" href="{{ route('user.measurement.period.export') }}?np_user={{$np_user->id}}" role="menuitem">Export as Excel</a>
                    </div>
                </div>
                @if($np_user->status=='pending' )
                    <button type="button" class="btn btn-outline btn-success" id="completedButton" onclick="markAsComplete({{$np_user->id}})">
                       Completed
                    </button>
                @endif
            </div>
    </div>
    <div class="panel-body">
        <div class="example table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="w-50">
                    </th>
                    <th>KPI</th>
                    <th>Weight</th>
                    <th>Target</th>
                    <th>Actual</th>
                    <th>Score</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($kpis as $kpi)
                    <tbody class="table-section" data-plugin="tableSection">
                    <tr>
                        <td class="text-center"><i class="table-section-arrow"></i></td>
                        <td class="font-weight-medium">{{ $kpi->kpi_question }}</td>
                        <td>{{ $kpi->weight }}</td>
                        <td>{{ $kpi->target }}</td>
                        <td>{{ $kpi->actual }}</td>
                        <td>{{ $kpi->score }}</td>

                        <td class="hidden-sm-down">
                            @if($np_user->status=='pending' )
                                <button class="btn btn-xs btn-primary" onclick="return supervisorResponds({{$kpi}})">Respond</button>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                    <tbody>
                    <tr>
                        <td></td>
                        <td class="font-weight-medium text-success">
                            Measurement
                        </td>
                        <td colspan="5">
                            {{ $kpi->measurement }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="font-weight-medium text-success">
                            Data Source
                        </td>
                        <td colspan="5">
                            {{ $kpi->data_source }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="font-weight-medium text-success">
                            Frequency of Data
                        </td>
                        <td colspan="5">
                            {{ $kpi->frequency_of_data }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="font-weight-medium text-success">
                            Responsible Collation Unit
                        </td>
                        <td colspan="5">
                            {{ $kpi->responsible_collation_unit }}
                        </td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>

<div class="modal fade in modal-3d-flip-horizontal modal-info modal-md" id="supervisorRespondsModal" aria-hidden="true"
     aria-labelledby="enterDetails" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="training_title">Supervisor Response</h4>
            </div>
            <div class="modal-body">
                <form id="supervisorRespondsForm">
                    @csrf
                    <p>Question: <span id="kpi_question"></span></p>
                    <p>KPI Rating: <span id="kpi_rating"></span></p>

                    <input type="hidden" name="kpi_id" id="kpi_id">
                    <div class="form-group">
                        <label class="form-control-label" for="actual">Actual</label>
                        <input class="form-control" type="number" name="actual" id="actual" placeholder="Actual" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="inputBasicEmail">Supervisor Comment</label>
                        <textarea class="form-control" name="comment" id="comment" cols="20"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-xs-12">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#supervisorRespondsForm').on('submit', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{route('supervisor.kpi.submit.response')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                if (data.status=='fail'){
                    toastr.error(data.details, 'Error');
                }
                else {
                    toastr.success("Changes saved successfully", 'Success');
                    $('#supervisorRespondsModal').modal('toggle');
                    loadUserKPIs(data.kpi.n_p_user_id)
                }
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

    function supervisorResponds(kpi) {
        $("#supervisorRespondsModal").modal();
        $("#kpi_id").val(kpi.id);
        $("#np_user_id").val(kpi.n_p_user_id);
        $("#kpi_question").html(kpi.kpi_question);
        $("#kpi_rating").html(kpi.kpi_rating);
        if (kpi.kpi_rating_type!='punitive'){
            $('#actual').attr('max', kpi.target);
        }
    }

    function markAsComplete(np_user) {
        var r = confirm("Are you sure you want to mark this as Complete?");
        if (r == true) {
            complete(np_user,'supervisor_complete')
        } else {
            toastr.error('It was not confirmed');
        }
    }
    function complete(np_user,status) {
        $.ajax({
            url: '{{url('performance/measurement/np_user/status')}}?np_user='+np_user+'&status='+status,
            type: 'GET',
            success: function (data, textStatus, jqXHR) {
                if (data.status==='success'){
                    toastr.success(data.details, 'Success');
                }
                else{

                }
                loadUserKPIs(data.np_user.id)
            },
            error: function (data, textStatus, jqXHR) {
                toastr.error('Error', 'Error');
            }
        });
    }
</script>
