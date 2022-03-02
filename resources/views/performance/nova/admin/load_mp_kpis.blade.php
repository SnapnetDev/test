<div class="panel panel-info panel-line">
    <div class="panel-heading">
        <h3 class="panel-title">Staff: {{ $np_user->user->name }} <br>
            <b>Measurement Period:</b> {{ $measurement_period->name }} <br>
            <b>From:</b> {{ $measurement_period->from }} <br>
            <b>To:</b> {{ $measurement_period->to }}. <br>
            <b>TOTAL Score:</b> {{ $np_user->score }} <br>
        </h3>
        <div class="panel-actions">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline btn-info dropdown-toggle" id="exampleGroupDrop2" data-toggle="dropdown" aria-expanded="false">
                    Action
                </button>
                <div class="dropdown-menu" aria-labelledby="exampleGroupDrop2" role="menu">
                    <a class="dropdown-item" style="cursor: pointer" onclick="loadaddToKPIFormContent({{$np_user->id}})" role="menuitem">Add KPI</a>
                    <a class="dropdown-item" href="{{ route('user.measurement.period.export') }}?np_user={{$np_user->id}}" role="menuitem">Export as Excel</a>
                </div>
            </div>
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
                            <button class="btn btn-xs btn-primary" onclick="return editKPI({{$np_user->id}},{{$kpi}})">Edit</button>
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

<div class="modal fade in modal-3d-flip-horizontal modal-info modal-md" id="addToKPIModal" aria-hidden="true"
     aria-labelledby="enterDetails" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="create_title">Add to KPI</h4>
            </div>
            <div class="modal-body">
                <form id="addToKPIForm">
                    @csrf
                    <input type="hidden" id="np_user_id" name="np_user_id">
                    <input type="hidden" id="type" name="type">
                    <input type="hidden" id="kpi_id" name="kpi_id">

                    <div class="col col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="weight">Weight</label>
                            <input class="form-control" type="number" name="weight" id="weight" placeholder="Weight" autocomplete="off">
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="kpi_rating_type">KPI Rating Type</label>
                            <select class="form-control" name="kpi_rating_type" id="kpi_rating_type">
                                <option value="calculation">Calculation</option>
                                <option value="conditional">Conditional</option>
                                <option value="punitive">Punitive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col col-md-8">
                        <div class="form-group">
                            <label class="form-control-label" for="target">Target Words</label>
                            <input class="form-control" type="text" name="target_words" id="target_words" placeholder="Target Words" autocomplete="off">
                        </div>
                    </div>
                    <div class="col col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="target">Target</label>
                            <input class="form-control" type="number" name="target" id="target" placeholder="Target" autocomplete="off">
                        </div>
                    </div>


                    <div class="col col-md-12">
                        <div class="form-group">
                            <label class="form-control-label" for="inputBasicEmail">KPI Question</label>
                            <textarea class="form-control" name="question" id="question" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="kpi_rating">KPI Rating</label>
                            <textarea class="form-control" name="kpi_rating" id="kpi_rating" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="measurement">Measurement</label>
                            <textarea class="form-control" name="measurement" id="measurement" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="data_source">Data Source</label>
                            <textarea class="form-control" name="data_source" id="data_source" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="frequency_of_data">Frequency of Data</label>
                            <textarea class="form-control" name="frequency_of_data" id="frequency_of_data" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col col-md-4">
                        <div class="form-group">
                            <label class="form-control-label" for="responsible_collation_unit">Responsible collation unit</label>
                            <textarea class="form-control" name="responsible_collation_unit" id="responsible_collation_unit" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>

                <form id="bulkUploadSingleUserExcel">
                    @csrf
                    <input type="hidden" id="np_user_id_excel" name="np_user_id_excel">

                    <div class="col col-md-12">
                        <a href="{{ route('user.kpi.excel.template',['type'=>'single']) }}">Download Single User KPI Template</a>
                    </div>
                    <div class="col col-md-12">
                        <div class="form-group">
                            <label class="form-control-label" for="inputBasicEmail">Upload</label>
                            <input type="file" class="form-control" name="template" id="from">
                        </div>
                    </div>

                    <div class="col col-md-6">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
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
    $('#addToKPIForm').on('submit', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{route('user.kpi.add')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#addToKPIModal').modal('toggle');
                loadUserKPIs(data.n_p_user_id)
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

    $('#bulkUploadSingleUserExcel').on('submit', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{route('user.kpi.excel.import')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#addToKPIModal').modal('toggle');
                loadUserKPIs(data.n_p_user_id)
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

    function loadaddToKPIFormContent(np_user) {
        $("#addToKPIModal").modal();
        $("#np_user_id").val(np_user);
        $("#np_user_id_excel").val(np_user);
        $("#create_title").html('Add to KPI');
        $("#type").val('new');
    }
    function editKPI(np_user,kpi) {
        $("#addToKPIModal").modal();
        $("#np_user_id").val(np_user);
        $("#np_user_id_excel").val(np_user);


        $("#weight").val(kpi.weight);
        $("#target").val(kpi.target);
        $("#target_words").val(kpi.target_words);
        //$("#kpi_rating_type").attr('selected'.kpi.kpi_rating_type);

        $("#question").val(kpi.kpi_question);
        $("#kpi_rating").val(kpi.kpi_rating);
        $("#measurement").val(kpi.measurement);
        $("#data_source").val(kpi.data_source);
        $("#frequency_of_data").val(kpi.frequency_of_data);
        $("#responsible_collation_unit").val(kpi.responsible_collation_unit);


        $("#create_title").html('Edit KPI');
        $("#type").val('edit');
        $("#kpi_id").val(kpi.id);
    }
</script>
