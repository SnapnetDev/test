<table>
	<thead>
		<tr>
			<th>Posting Date</th>
			<th>Amount</th>
			<th>Description</th>
			<th>Document No.</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{date('m/d/Y',strtotime($payroll->created_at))}}</td>
			<td>{{number_format($salary+$allowances-$deductions-$income_tax,2)}}</td>
			<td>Net Salary {{$payroll->year}}-{{date('F',strtotime($date))}}</td>
			<td>{{$payroll->year}}-{{date('F',strtotime($date))}}</td>
		</tr>
		<tr>
			<td>{{date('m/d/Y',strtotime($payroll->created_at))}}</td>
			<td>{{number_format($salary,2)}}</td>
			<td>Basic Salary {{$payroll->year}}-{{date('F',strtotime($date))}}</td>
			<td>{{$payroll->year}}-{{date('F',strtotime($date))}}</td>
		</tr>
		<tr>
			<td>{{date('m/d/Y',strtotime($payroll->created_at))}}</td>
			<td>{{number_format($allowances,2)}}</td>
			<td>Allowances {{$payroll->year}}-{{date('F',strtotime($date))}}</td>
			<td>{{$payroll->year}}-{{date('F',strtotime($date))}}</td>
		</tr>
		<tr>
			<td>{{date('m/d/Y',strtotime($payroll->created_at))}}</td>
			<td>{{number_format($deductions,2)}}</td>
			<td>Deductions {{$payroll->year}}-{{date('F',strtotime($date))}}</td>
			<td>{{$payroll->year}}-{{date('F',strtotime($date))}}</td>
		</tr>
		<tr>
			<td>{{date('m/d/Y',strtotime($payroll->created_at))}}</td>
			<td>{{number_format($income_tax,2)}}</td>
			<td>PAYE {{$payroll->year}}-{{date('F',strtotime($date))}}</td>
			<td>{{$payroll->year}}-{{date('F',strtotime($date))}}</td>
		</tr>
	</tbody>
</table>