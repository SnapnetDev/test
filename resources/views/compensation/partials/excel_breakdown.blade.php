<table>
	<tr>
		<th>Employee Name</th>
		<th>Employee ID</th>
		@foreach($component->months as $month)
		<th colspan="6">{{$month->name."-".$month->year}}</th>
		@endforeach

	</tr>
	<tr>
		<td></td>
		<td></td>
		@foreach($component->months as $month)
		<td>Day rate Onshore</td>
		<td>Days worked Onshore</td>
		<td>Day rate Offshore</td>
		<td>Days worked Offshore</td>
		<td>Total</td>
		<td>CRA 1%</td>
		@endforeach
		
	</tr>
	@foreach($payroll->tmsa_payroll_details as $detail)
	@php
		$total_month_amount=0;
	@endphp
		<tr>
			<td>{{$detail->user->name}}</td>
			<td>{{$detail->user->emp_num}}</td>
			@foreach($component->months as $month)
			@php
				$tmsa_schedule=$collection->where('user_id',$detail->user_id)->first();
				$total_month_amount+=($tmsa_schedule->day_rate_onshore*$tmsa_schedule->days_worked_onshore)+($tmsa_schedule->day_rate_offshore*$tmsa_schedule->days_worked_offshore);
			@endphp
			
			
			<td>{{$tmsa_schedule->day_rate_onshore}}</td>
			<td>{{$tmsa_schedule->days_worked_onshore}}</td>
			<td>{{$tmsa_schedule->day_rate_offshore}}</td>
			<td>{{$tmsa_schedule->days_worked_offshore}}</td>
			<td>{{$total_month_amount}}</td>
			<td>{{$total_month_amount*0.01}}</td>
		</tr>
		@endforeach
	@endforeach
</table>