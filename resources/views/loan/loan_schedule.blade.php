
<table >
	<thead>

		@for($i=1;$i<=8;$i++)
		<tr></tr>
		@endfor
		<tr>
			<th></th>
			<th>Annual Net Pay</th>
			<th style="background: #ff99cc;">{{$netpay}}</th>
			<th></th>
			<th  style="background: #d9d9d9;">Test</th>
			<th style="background: #d9d9d9;"></th>
			<th style="background: #d9d9d9;"></th>
		</tr>
		<tr>
			@for($i=1;$i<=4;$i++)
			<th></th>
			@endfor
			
			<td style="background: #d9d9d9;">Monthly Net Income</td>
			<td style="background: #8db4e2;">{{round($netpay/12,2)}}</td>
			<td style="background: #d9d9d9;"></td>
		</tr>
		<tr>
			<th></th>
			<th>Max Loan Amount</th>
			<td style="color:#ff0000;"></td>
			<td ></td>
			<td style="background: #d9d9d9;">33.3% of net income</td>
			<td style="background: #d9d9d9;">{{round($netpay*33/100,2)}}</td>
			<td style="background: #d9d9d9;"></td>
		</tr>
		<tr>
			<th></th>
			<th>Effective Monthly Rate</th>
			<td>{{$datas->current_rate/12}}%</td>
			<th></th>
			<td style="background: #d9d9d9;">Monthly Repayment</td>
			<td style="background: #d9d9d9;"></td>
			<td style="background: #d9d9d9;"> </td>


		</tr>
		<tr>
			<th></th>
			<th>Start Date</th>
			<th>{{date('d-M-y')}}</th>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
			<th></th>
			<th>Amount Applied for</th>
			<th style="background: #8db4e2;">{{$datas->amount}}</th>
		</tr>
		<tr>
			<th></th>
			<th>Interest Rate Annual</th>
			<th style="background: #8db4e2;">{{$datas->current_rate}}%</th>
		</tr>
		<tr>
			<th></th>
			<th>Max Tenor</th>
			<th style="background: #8db4e2;">{{$datas->period}}</th>
			<th>Months</th>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
			<th></th>
			<th></th>
			<th>Opening</th>
			<th>PRINCIPAL</th>
			<th>INTEREST</th>
			<th>MONTHLY</th>
			<th>Closing</th>
		</tr>
		<tr>
			<th></th>
			<th></th>
			<th>Balance</th>
			<th>PAID</th>
			<th>PAID</th>
			<th>PAYMENTS</th>
			<th>Balance</th>
		</tr>

	</thead>
	<tbody>
		@php
			$pricipal_paid=0.00;
			$interest_paid=0.00;
			$opening_bal=$datas->amount;
			$closing_bal=0;
			$total_pricipal_paid=0.00;
			$total_mothly_deductions=0.00;
			$total_interest_paid=0.00;
			$date=$datas->repayment_starts;
			

		@endphp
		@for ($i = 1; $i <= $datas->period; $i++)
		@php

			$d= new \DateTime($date);
			$d->modify('next month');
		@endphp
			<tr>
				<th>{{$i}}</th>
				@if ($i==1)
					<td>{{date('M-y',strtotime($date))}}</td>

					@else
					<td>{{$d->format('M-y')}}</td>
				@endif
				<td>{{$opening_bal}}</td>
				@php
					$interest_paid=round($opening_bal*($datas->current_rate/12/100),2);
					$pricipal_paid=round($datas->monthly_deduction-($interest_paid),2);
					$total_pricipal_paid+=$pricipal_paid;
					$total_interest_paid+=$interest_paid;
				@endphp
				<td>{{$pricipal_paid}}</td>
				<td>{{$interest_paid}}</td>
				<td>{{$datas->monthly_deduction}}</td>
				@php
				$total_mothly_deductions+=$datas->monthly_deduction;
					$closing_bal=$opening_bal-$pricipal_paid;
				@endphp
				<td style="background: #d9d9d9;">{{$closing_bal}}</td>
			</tr>
			@php
				$opening_bal=$closing_bal;
				$date=$d->format('Y-m-d');
			@endphp
		@endfor
		<tr>
			<th></th>
			<th>TOTAL</th>
			<th></th>
			<th>{{$total_pricipal_paid}}</th>
			<th>{{$total_interest_paid}}</th>
			<th>{{$total_mothly_deductions}}</th>
		</tr>
	</tbody>
</table>