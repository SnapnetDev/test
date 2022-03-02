
<div>
	<ul class="list-group list-group-dividered ">
		<li class="list-group-item"><strong>Payroll For:	{{date('M-Y',strtotime($payroll->for))}}</strong></li>
		<li class="list-group-item">Wallet Balance:</li>
		<li class="list-group-item">Basic Salary:&#8358;{{number_format( $payroll_data['current']['basic_pay'],2)}}</li>
		<li class="list-group-item">Allowances:&#8358;{{number_format( $payroll_data['current']['allowances'],2)}}</li>
		<li class="list-group-item">Deductions:&#8358;{{number_format( $payroll_data['current']['deductions'],2)}}</li>
		<li class="list-group-item">PAYE:&#8358;{{number_format( $payroll_data['current']['income_tax'],2)}}</li>
		<li class="list-group-item">Total Net Pay :&#8358;{{number_format( ($payroll_data['current']['basic_pay']+$payroll_data['current']['allowances']-( $payroll_data['current']['deductions']+$payroll_data['current']['income_tax'])),2)}}</li>
	</ul>
</div>
<div class="table-responsive">
	<h4>Approval Details ({{$payroll->workflow->name}})</h4>
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Stage</th>
			<th>Approver</th>
			<th>Comment</th>
			<th>Status</th>
			<th>Time in Stage</th>
			
		</tr>
	</thead>
	<tbody>
		@foreach($payroll->payroll_approvals as $approval)
		<tr>
			<td>{{$approval->stage->name}}</td>
			<td>{{$approval->approver_id>0?$approval->approver->name:""}}</td>
			<td>{{$approval->comments}}</td>
			<td><span class=" tag   {{$approval->status==0?'tag-warning':($approval->status==1?'tag-success':'tag-danger')}}">{{$approval->status==0?'pending':($approval->status==1?'approved':'rejected')}}</span></td>
			<td>{{ $approval->created_at==$approval->updated_at?\Carbon\Carbon::parse($approval->created_at)->diffForHumans():\Carbon\Carbon::parse($approval->created_at)->diffForHumans($approval->updated_at) }}</td>
		</tr>
		@endforeach
	</tbody>
	
</table>
</div>
<h3>Last Two months Payroll Trend</h3>
<div id="chartdiv" style="width: 100%; height: 500px;"></div>
<table class="table table-striped table-bordered">
	<thead>
	<tr>
		<th>Components/Period</th>
		<th>{{$payroll_data['current']['name']}}</th>
		<th>{{$payroll_data['last_month_payroll']['name']}}</th>
		<th>{{$payroll_data['last_two_months_payroll']['name']}}</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>Basic Salary</td>
		<td>&#8358;{{number_format( $payroll_data['current']['basic_pay'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_month_payroll']['basic_pay'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_two_months_payroll']['basic_pay'],2)}}</td>
	</tr>
	<tr>
		<td>Allowances</td>
		<td>&#8358;{{number_format( $payroll_data['current']['allowances'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_month_payroll']['allowances'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_two_months_payroll']['allowances'],2)}}</td>
	</tr>
	<tr>
		<td>Deductions</td>
		<td>&#8358;{{number_format( $payroll_data['current']['deductions'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_month_payroll']['deductions'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_two_months_payroll']['deductions'],2)}}</td>
	</tr>
	<tr>
		<td>Income Tax</td>
		<td>&#8358;{{number_format( $payroll_data['current']['income_tax'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_month_payroll']['income_tax'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_two_months_payroll']['income_tax'],2)}}</td>
	</tr>
	<tr>
		<td>Net Salary</td>
		<td>&#8358;{{number_format( $payroll_data['current']['net_pay'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_month_payroll']['net_pay'],2)}}</td>
		<td>&#8358;{{number_format( $payroll_data['last_two_months_payroll']['net_pay'],2)}}</td>
	</tr>
	</tbody>
</table>






<!-- Chart code -->
<script>
	am4core.ready(function() {

// Themes begin
		am4core.useTheme(am4themes_material);
		am4core.useTheme(am4themes_animated);
// Themes end

		// Create chart instance
		var chart = am4core.create("chartdiv", am4charts.XYChart);

// Add data
		chart.data = [{
			"month": '{{$payroll_data['current']['name']}}',
			"basic_pay": {{round( $payroll_data['current']['basic_pay'],2)}},
			"allowances": {{round( $payroll_data['current']['allowances'],2)}},
			"deductions":{{round( $payroll_data['current']['deductions'],2)}},
			"income_tax":{{round( $payroll_data['current']['income_tax'],2)}},
			"net_pay":{{round( $payroll_data['current']['net_pay'],2)}}
		},{
			"month": '{{$payroll_data['last_month_payroll']['name']}}',
			"basic_pay": {{round( $payroll_data['last_month_payroll']['basic_pay'],2)}},
			"allowances": {{round( $payroll_data['last_month_payroll']['allowances'],2)}},
			"deductions":{{round( $payroll_data['last_month_payroll']['deductions'],2)}},
			"income_tax":{{round( $payroll_data['last_month_payroll']['income_tax'],2)}},
			"net_pay":{{round( $payroll_data['last_month_payroll']['net_pay'],2)}}
		},{
			"month": '{{$payroll_data['last_two_months_payroll']['name']}}',
			"basic_pay": {{round( $payroll_data['last_two_months_payroll']['basic_pay'],2)}},
			"allowances": {{round( $payroll_data['last_two_months_payroll']['allowances'],2)}},
			"deductions":{{round( $payroll_data['last_two_months_payroll']['deductions'],2)}},
			"income_tax":{{round( $payroll_data['last_two_months_payroll']['income_tax'],2)}},
			"net_pay":{{round( $payroll_data['last_two_months_payroll']['net_pay'],2)}}
		}];

// Create axes
		var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "month";
		categoryAxis.numberFormatter.numberFormat = "#";
		categoryAxis.renderer.inversed = true;
		categoryAxis.renderer.grid.template.location = 0;
		categoryAxis.renderer.cellStartLocation = 0.1;
		categoryAxis.renderer.cellEndLocation = 0.9;

		var  valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
		valueAxis.renderer.opposite = true;

// Create series
		function createSeries(field, name) {
			var series = chart.series.push(new am4charts.ColumnSeries());
			series.dataFields.valueX = field;
			series.dataFields.categoryY = "month";
			series.name = name;
			series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
			series.columns.template.height = am4core.percent(100);
			series.sequencedInterpolation = true;

			var valueLabel = series.bullets.push(new am4charts.LabelBullet());
			valueLabel.label.text = "{valueX}";
			valueLabel.label.horizontalCenter = "left";
			valueLabel.label.dx = 10;
			valueLabel.label.hideOversized = false;
			valueLabel.label.truncate = false;

			var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
			categoryLabel.label.text = "{name}";
			categoryLabel.label.horizontalCenter = "right";
			categoryLabel.label.dx = -10;
			categoryLabel.label.fill = am4core.color("#fff");
			categoryLabel.label.hideOversized = false;
			categoryLabel.label.truncate = false;
		}

		createSeries("basic_pay", "Basic Salary");
		createSeries("allowances", "Allowances");
		createSeries("deductions", "Deductions");
		createSeries("income_tax", "Income Tax");
		createSeries("net_pay", "Net Salary");

	}); // end am4core.ready()
</script>
