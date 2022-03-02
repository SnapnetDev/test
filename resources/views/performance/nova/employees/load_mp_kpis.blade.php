<div class="panel panel-info panel-line">
    <div class="panel-heading">
        <h3 class="panel-title">{{ $user->name }} <b>For:</b> {{ $measurement_period->name }}  <b>From:</b> {{ $measurement_period->from }} <b>To:</b> {{ $measurement_period->to }} <br> <b>TOTAL Score:</b> {{ $np_user->score }}</h3>
            <div class="panel-actions">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline btn-info dropdown-toggle" id="exampleGroupDrop2" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exampleGroupDrop2" role="menu">
                       @if($np_user->status=='supervisor_completed')
                            <a class="dropdown-item" href="#" onclick="return loadrespondToKPIFormContent({{$np_user->id}})" role="menuitem">Respond to KPI</a>\
                        @endif
                        <a class="dropdown-item" href="{{ route('user.measurement.period.export') }}?np_user={{$np_user->id}}" role="menuitem">Export as Excel</a>
                    </div>
                </div>
            </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th class="w-50">
                    </th>
                    <th>KPI</th>
                    <th>Weight</th>
                    <th>Target</th>
                    <th>Actual</th>
                    <th>Score</th>
                </tr>
                </thead>
                @foreach($kpis as $kpi)
                    <tbody class="table-section" data-plugin="tableSection">
                    <tr>
                        <td class="text-center"><i class="table-section-arrow"></i></td>
                        <td class="font-weight-medium">{{ $kpi->kpi_question }}</td>
                        <td>{{ $kpi->weight }}</td>
                        <td>{{ $kpi->target }}</td>
                        <td>{{ $kpi->actual }}</td>
                        <td>{{ $kpi->score }}</td>
                    </tr>
                    </tbody>
                    <tbody>
                    <tr>
                        <td></td>
                        <td class="font-weight-medium text-success">
                            Measurement
                        </td>
                        <td colspan="4">
                            {{ $kpi->measurement }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="font-weight-medium text-success">
                            Data Source
                        </td>
                        <td colspan="4">
                            {{ $kpi->data_source }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="font-weight-medium text-success">
                            Frequency of Data
                        </td>
                        <td colspan="4">
                            {{ $kpi->frequency_of_data }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="font-weight-medium text-success">
                            Responsible Collation Unit
                        </td>
                        <td colspan="4">
                            {{ $kpi->responsible_collation_unit }}
                        </td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>

<div class="modal fade in modal-3d-flip-horizontal modal-info modal-md" id="respondToKPIModal" aria-hidden="true"
     aria-labelledby="enterDetails" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="training_title">Comment on KPI</h4>
            </div>
            <div class="modal-body">
                <form id="respondToKPIForm">
                    @csrf
                    <input type="hidden" name="np_user" id="np_user">
                    <div class="form-group">
                        <label class="form-control-label" for="inputBasicEmail">Your Comment</label>
                        <textarea class="form-control" name="comment" id="comment" cols="30" rows="5"></textarea>
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
    $('#respondToKPIForm').on('submit', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{route('user.kpi.submit.response')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#respondToKPIModal').modal('toggle');
                loadMeasurementPeriodKPI(data.n_p_measurement_period_id)
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

    function loadrespondToKPIFormContent(np_user) {
        $("#respondToKPIModal").modal();

        $("#np_user").val(np_user.id);


    }
</script>
