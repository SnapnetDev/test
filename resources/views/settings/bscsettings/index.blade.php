<style type="text/css">
    .head > tr > th {
        color: #fff;
    }

    .my-btn.btn-sm {
        font-size: 0.7 .5rem;
        width: 1.5rem;
        height: 1.5rem;
        padding: 0;
    }
</style>
<div class="page-header">
    <h1 class="page-title">{{__('All Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item ">{{__('Balance Score Card')}}</li>
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
            {{--        		<div class="panel panel-info panel-line">--}}
            {{--		            <div class="panel-heading">--}}
            {{--		              <h3 class="panel-title">Balance Score Card Department Weight Percentages</h3>--}}
            {{--		              <div class="panel-actions">--}}
            {{--                			--}}

            {{--              			</div>--}}
            {{--		            	</div>--}}
            {{--		            <div class="panel-body">--}}
            {{--		            --}}
            {{--	                  <table id="bsctable"  class="table table-striped "   >--}}
            {{--		                    <thead>--}}
            {{--		                      <tr>--}}
            {{--		                        <th >Department</th>--}}
            {{--		                        <th>Company</th>--}}
            {{--		                        <th >Performance Category</th>--}}
            {{--		                        <th>Perspective</th>--}}
            {{--		                        <th>Percentage</th>--}}
            {{--		                        <th>Action</th>--}}
            {{--		                        </tr> --}}
            {{--		                    </thead>--}}
            {{--		                    <tbody>--}}
            {{--		                    	@forelse($departments as $department)--}}
            {{--		                    	--}}
            {{--		                    	--}}
            {{--				            	@forelse($performance_categories as $performance_category)--}}
            {{--				            				--}}
            {{--	            					@foreach($metrics as $metric)--}}
            {{--	            					<tr>--}}
            {{--				            		<td >{{$department->name}}</td>--}}
            {{--				            		<td >{{$department->company->name}}</td>--}}
            {{--				            		<td>{{$performance_category->name}}</td>--}}
            {{--				            		<td>{{$metric->name}}</td>--}}
            {{--	            					@php--}}
            {{--	            						$weight=bscweight($department->id,$performance_category->id,$metric->id);--}}
            {{--	            					@endphp--}}
            {{--	                        	 	<td class="weight" id="td_{{$weight->id}}">--}}
            {{--	                        	 		{{$weight->percentage}}--}}
            {{--	                        	 	</td>--}}
            {{--	                        	 	<td><button class="btn btn-primary weightbtn" id="{{$weight->id}}"><i class="fa fa-pencil"></i> </button> </td>--}}
            {{--	                        	 	@endforeach--}}
            {{--	                        	 	--}}
            {{--				            				</tr>--}}
            {{--				            		@empty--}}
            {{--				            		@endforelse--}}
            {{--		                    	--}}
            {{--		                    	@empty--}}
            {{--		                    	@endforelse--}}
            {{--		                    	--}}
            {{--		                    </tbody>--}}
            {{--	                  </table>--}}
            {{--	          		</div>--}}
            {{--	          		--}}
            {{--		          </div>--}}

            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Grade Performance Category</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addPerformanceCategoryModal">Add
                            Grade Performance Category
                        </button>

                    </div>
                </div>
                <div class="panel-body">

                    <table class="table table-striped ">
                        <thead>
                        <tr>
                            <th style="width: 30%">Name:</th>
                            <th style="width: 50%">Grades:</th>
                            <th style="width: 20%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($performance_categories as $performance_category)
                            <tr>
                                <td>{{$performance_category->name}}</td>
                                <td>
                                    @foreach($performance_category->grades as $grade)
                                        <span style="background: #03a9f4; color: white; padding: 3px;"
                                              class="label label-primary">{{$grade->level}}</span>,
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                id="exampleIconDropdown1"
                                                data-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>


                                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                            <a style="cursor:pointer;" class="dropdown-item"
                                               id="{{$performance_category->id}}"
                                               onclick="editPerformanceCategory(this.id)"><i class="fa fa-pencil"
                                                                                             aria-hidden="true"></i>&nbsp;Edit
                                                Grade Performance Category</a>
                                            <a style="cursor:pointer;" class="dropdown-item"
                                               id="{{$performance_category->id}}"
                                               onclick="deletePerformanceCategory(this.id)"><i class="fa fa-trash"
                                                                                               aria-hidden="true"></i>&nbsp;Delete
                                                Grade Performance Category</a>

                                        </div>
                                    </div>
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
                    <h3 class="panel-title">Behavioral Sub Metrics</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addBehavioralSubMetricModal">Add
                            Behavioral Sub Metric
                        </button>

                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <thead>
                            <tr>
                                <th>Objective/Strategic Focus:</th>
                                <th>Weighting:</th>
                                <th>Measure/KPI:</th>
                                <th>Status:</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($bsms as $bsm)
                                <tr>
                                    <td>{{$bsm->objective}}</td>
                                    <td>{{$bsm->weighting}}</td>
                                    <td>{{$bsm->measure}}</td>
                                    <td>{{$bsm->status==1?'Active':'Inactive'}}</td>

                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="exampleIconDropdown1"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </button>


                                            <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                 role="menu">
                                                <a style="cursor:pointer;" class="dropdown-item" id="{{$bsm->id}}"
                                                   onclick="editBehavioralSubMetric(this.id)"><i class="fa fa-pencil"
                                                                                                 aria-hidden="true"></i>&nbsp;Edit
                                                    Behavioral Sub Metric</a>
                                                <a style="cursor:pointer;" class="dropdown-item" id="{{$bsm->id}}"
                                                   onclick="deleteBehavioralSubMetric(this.id)"><i class="fa fa-trash"
                                                                                                   aria-hidden="true"></i>&nbsp;Delete
                                                    Behavioral Sub Metric</a>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Measurement Period</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addMeasurementPeriodModal">Add
                            Measurement Period
                        </button>

                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                           data-query-params="queryParams" data-mobile-responsive="true"
                           data-height="400" data-pagination="true" data-search="true"
                           class="table table-striped datatable">
                        <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Status</th>
                            <th>Head of Strategy</th>
                            <th>Head of HR</th>
                            <th>BSC Percentage</th>
                            <th>Behavioral Percentage</th>
                            <th>Created On</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($measurement_periods as $measurement_period)



                            <tr>
                                <td>{{date('F-Y',strtotime($measurement_period->from))}}</td>
                                <td>{{date('F-Y',strtotime($measurement_period->to))}}</td>
                                <td>{{$measurement_period->status==1?'Active':'Inactive'}}</td>
                                <td>{{$measurement_period->head_of_strategy?$measurement_period->head_of_strategy->name:'None Specified'}}</td>
                                <td>{{$measurement_period->head_of_hr?$measurement_period->head_of_hr->name:'None Specified'}}</td>
                                <td>{{$measurement_period->scorecard_percentage}}</td>
                                <td>{{$measurement_period->behavioral_percentage}}</td>
                                <td>{{date("F j, Y",strtotime($measurement_period->created_at))}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                id="exampleIconDropdown1"
                                                data-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                                            <a style="cursor:pointer;" class="dropdown-item editmp"
                                               id="{{$measurement_period->id}}"><i class="fa fa-pencil"
                                                                                   aria-hidden="true"></i>&nbsp;Edit
                                                Measurement Period</a>
                                            <a style="cursor:pointer;" class="dropdown-item"
                                               id="{{$measurement_period->id}}"
                                               onclick="deleteMeasurementPeriod(this.id)"><i class="fa fa-trash"
                                                                                             aria-hidden="true"></i>&nbsp;Delete
                                                Measurement Period</a>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        </tbody>
                    </table>
                </div>

            </div>
            {{-- start balance scorecard perspective --}}

            {{-- end balance scorecard perspective --}}
            {{-- start balance scorecard measurement period --}}
            {{-- end balance scorecard measurement period--}}
        </div>
    </div>
    <div class="col-md-12 col-xs-12">

    </div>
</div>
</div>
{{-- Add Company Modal --}}
@include('settings.bscsettings.modals.editweight')
@include('settings.bscsettings.modals.addmeasurementperiod')
@include('settings.bscsettings.modals.editmeasurementperiod')
@include('settings.bscsettings.modals.addperformancecategory')
@include('settings.bscsettings.modals.editperformancecategory')
@include('settings.bscsettings.modals.addbehavioral_sub_metric')
@include('settings.bscsettings.modals.editbehavioral_sub_metric')

<!-- End Page -->
<script type="text/javascript">


    $(function () {

		$('#grades').select2({
			ajax: {
				delay: 250,
				processResults: function (data) {
					return {
						results: data
					};
				},
				url: function (params) {
					return '{{url('bscsettings')}}/search_grade';
				}
			}
		});
		$('#editgrades').select2({
			ajax: {
				delay: 250,
				processResults: function (data) {
					return {
						results: data
					};
				},
				url: function (params) {
					return '{{url('bscsettings')}}/search_grade';
				}

			}
		});

		$('.weightbtn').click(function (event) {
			id = $(this).attr('id');


			$.get('{{ url('bscsettings/get_weight') }}', {weight_id: id}, function (data) {

				$('#editwpercentage').val(data.percentage);
				$('#editwid').val(data.id);
				$('#editwcompany').val(data.department.company.name);
				$('#editwdepartment').val(data.department.name);
				$('#editwperspective').val(data.metric.name);
				$('#editwperformancecategory').val(data.performance_category.name);

			});
			$('#editWeightModal').modal();
		});
		$('.editmp').click(function (event) {
			id = $(this).attr('id');


			$.get('{{ url('bscsettings/get_measurement_period') }}', {mp_id: id}, function (data) {


				$('#editmpfrom').val(formatMPDate(data.from));
				$('#editmpid').val(data.id);
				$('#editmpto').val(formatMPDate(data.to));
				$('#editmphos').val(data.head_of_strategy_id);
				$('#editmphoh').val(data.head_of_hr_id);
                $('#editmpbsp').val(data.scorecard_percentage);
                $('#editmpbhp').val(data.behavioral_percentage);
				$('#editmpstatus').val(data.status);
			});
			$('#editMeasurementPeriodModal').modal();
		});
	});
        $(function () {

            $('#addPerformanceCategoryForm').submit(function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{url('bscsettings')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        if (data == 'success') {
                            toastr.success("Changes saved successfully", 'Success');
                            $('#addPerformanceCategoryModal').modal('toggle');
                            {{-- $( "#ldr" ).load('{{url('payrollsettings/salary_components')}}'); --}}
                            location.reload();
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
        });
        $(function () {
            $('#editPerformanceCategoryForm').submit(function (event) {

                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{url('bscsettings')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        if (data == 'success') {
                            toastr.success("Changes saved successfully", 'Success');
                            $('#editPerformanceCategoryModal').modal('toggle');
                            {{-- $( "#ldr" ).load('{{url('payrollsettings/salary_components')}}'); --}}
                            location.reload();
                        }
                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
        });
        $(function () {

            $('#addBehavioralSubMetricForm').submit(function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{url('bscsettings')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        if (data == 'success') {
                            toastr.success("Changes saved successfully", 'Success');
                            $('#addBehavioralSubMetricModal').modal('toggle');
                            {{-- $( "#ldr" ).load('{{url('payrollsettings/salary_components')}}'); --}}
                            location.reload();
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
        });
        $(function () {
            $('#editBehavioralSubMetricForm').submit(function (event) {

                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{url('bscsettings')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        if (data == 'success') {
                            toastr.success("Changes saved successfully", 'Success');
                            $('#editBehavioralSubMetricModal').modal('toggle');
                            {{-- $( "#ldr" ).load('{{url('payrollsettings/salary_components')}}'); --}}
                            location.reload();
                        }
                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
        });
        $(function () {
            // Setup - add a text input to each footer cell
            $('#bsctable thead tr').clone(true).appendTo('#bsctable thead');
            $('#bsctable thead tr:eq(1) th').each(function (i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');

                $('input', this).on('keyup change', function () {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            $('.datatable, #bsctable').DataTable().destroy();

            var table = $('#bsctable').DataTable({
                orderCellsTop: true,

            });

            $('.datatable').DataTable();

            $('#weighttable').editableTableWidget();
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'mm-yyyy',
                viewMode: "months",
                minViewMode: "months"
            });




            $('#editWeightForm').submit(function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
            });
        });

        $('.datatable, #bsctable').DataTable().destroy();

        var table = $('#bsctable').DataTable({
            orderCellsTop: true,

        });

        $('.datatable').DataTable();

        $('#weighttable').editableTableWidget();
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'mm-yyyy',
            viewMode: "months",
            minViewMode: "months"
        });


        $('#editWeightForm').submit(function (event) {
            event.preventDefault();
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{url('bscsettings')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {

                    var newpercentage = document.forms['editWeightForm'].elements['percentage'].value;
                    var weightid = document.forms['editWeightForm'].elements['weight_id'].value;
                    $('#td_' + weightid).html(newpercentage);
                    toastr.success("Changes saved successfully", 'Success');
                    $('#editWeightModal').modal('toggle');
                    // $( "#ldr" ).load('{{url('bscsettings')}}');

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

        $('#addMeasurementPeriodForm').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{url('bscsettings')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {

                    toastr["success"]("Changes saved successfully", 'Success');
                    $('#addMeasurementPeriodModal').modal('toggle');
                    $("#ldr").load('{{url('bscsettings')}}');
                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr["error"](valchild[0]);
                        });
                    });
                }
            });

        });
        $('#editMeasurementPeriodForm').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url: '{{url('bscsettings')}}',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {

                    toastr["success"]("Changes saved successfully", 'Success');
                    $('#editMeasurementPeriodModal').modal('toggle');
                    $("#ldr").load('{{url('bscsettings')}}');
                },
                error: function (data, textStatus, jqXHR) {
                    jQuery.each(data['responseJSON'], function (i, val) {
                        jQuery.each(val, function (i, valchild) {
                            toastr["error"](valchild[0]);
                        });
                    });
                }
            });

        });


    function editPerformanceCategory(performance_category_id) {
        $.get('{{ url('/bscsettings/get_bsc_grade_performance_category') }}/', {performance_category_id: performance_category_id}, function (data) {
            console.log(data);
            $('#editgradecategoryname').val(data.name);
            $("#editgrades").find('option')
                .remove();


            jQuery.each(data.grades, function (i, val) {
                $("#editgrades").append($('<option>', {value: val.id, text: val.level, selected: 'selected'}));
                // console.log(val.name);
            });
            $('#editperformancecategoryid').val(data.id);
        });
        $('#editPerformanceCategoryModal').modal();
    }

    function deletePerformanceCategory(performance_category_id) {
        alertify.confirm('Are you sure you want to delete this Performance Category?', function () {
            $.get('{{ url('/bscsettings/delete_bsc_grade_performance_category') }}/', {performance_category_id: performance_category_id}, function (data) {
                if (data == 'success') {
                    toastr.success("Performance Category deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error deleting Performance Category", 'Success');
                }
            });
        });
    }


    function prepareMPEditData(mp_id) {
        $.get('{{ url('bscsettings/metric') }}/' + company_id, function (data) {
            console.log(data);
            $('#editname').val(data.name);
            $('#editid').val(data.id);
            $('#editemail').val(data.email);
            $('#editaddress').val(data.address);
            $('#edituser').val(data.user_id);
        });
        $('#editCompanyModal').modal();
    }

    function formatMPDate(date) {
        var d = new Date(date);
        month = '' + (d.getMonth() + 1);
        day = '' + d.getDate();
        year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [month, year].join('-');

    }


    function deleteMeasurementPeriod(measurement_period_id) {
        alertify.confirm('Are you sure you want to delete this Measurement Period?', function () {
            $.get('{{ url('/bscsettings/delete_measurement_period') }}/', {mp_id: measurement_period_id}, function (data) {
                if (data == 'success') {
                    toastr.success("Measurement Period deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error deleting Measurement Period", 'Success');
                }

            });

        }, function () {
            alertify.error('Measurement Period not deleted');
        });

    }

    function editBehavioralSubMetric(bsm_id) {
        $.get('{{ url('/bscsettings/get_behavioral_sub_metric') }}/', {bsm_id: bsm_id}, function (data) {

            $('#editbsm_objective').val(data.objective);
            $('#editbsm_measure').val(data.measure);
            $('#editbsm_weighting').val(data.weighting);
            $('#editbsm_low_target').val(data.low_target);
            $('#editbsm_mid_target').val(data.mid_target);
            $('#editbsm_upper_target').val(data.upper_target);
            $('#editbsm_status').val(data.status);


            $('#editbsm_id').val(data.id);
        });
        $('#editBehavioralSubMetricModal').modal();
    }

    function deleteBehavioralSubMetric(bsm_id) {
        alertify.confirm('Are you sure you want to delete this Behavioral Sub Metric?', function () {
            $.get('{{ url('/bscsettings/delete_behavioral_sub_metric') }}/', {bsm_id: bsm_id}, function (data) {
                if (data == 'success') {
                    toastr.success("Behavioral Sub Metric deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error deleting Behavioral Sub Metric", 'Success');
                }

            });

        }, function () {
            alertify.error('Behavioral Sub Metric not deleted');
        });

    }


</script>