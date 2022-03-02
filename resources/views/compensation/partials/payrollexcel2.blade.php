@if($payroll->salary_components->count()>0)
<table >
	<thead>
		<tr>
			<th>Office Payroll</th>
		</tr>
		<tr>
			<th></th>
			<th>Employee Number</th>
			<th>Employee Name</th>
			
			<th>Basic pay</th>
			@foreach($payroll->salary_components as $component)
			<th>{{$component->name}}</th>
			@endforeach
			@foreach($specific_salary_component_types as $ct)
			<th>{{$ct->name}}</th>
			@endforeach

			
			<th>Personal Allowances</th>
			<th>Personal Deductions</th>
			<th>PAYE</th>
			<th>Union Dues</th>
			<th>Gross pay</th>
			<th>Net Pay</th>
			
		</tr>
	</thead>
	<tbody>
		@php
			$sn=1;

		@endphp
		@foreach ($payroll->payroll_details as $detail)
		@if($detail->payroll_type=='office')
			@php
				$allowances=0;
				$deductions=0;
				$gp=0;
				
			@endphp
				<tr>
					<td>{{$sn}}</td>
				<td>{{$detail->user->emp_num}}</td>
				<td>{{$detail->user->name}}</td>
				
				<td>{{$detail->basic_pay}}</td>
				@php
				$pdetails=unserialize($detail->sc_details);
				// $num=count($days);
				@endphp
					@foreach($payroll->salary_components as $component)
					<td>
						@if($component->type==1 && isset($pdetails['sc_allowances'][$component->constant]))
							@php
								$allowances+=$pdetails['sc_allowances'][$component->constant];
							@endphp
							{{isset($pdetails['sc_allowances'][$component->constant])?$pdetails['sc_allowances'][$component->constant]:""}}
						@elseif($component->type==0 && isset($pdetails['sc_deductions'][$component->constant]))
							@php
								$deductions+=$pdetails['sc_deductions'][$component->constant];
							@endphp
							{{isset($pdetails['sc_deductions'][$component->constant])?$pdetails['sc_deductions'][$component->constant]:""}}
						@endif
					</td>
					@endforeach
				@php
				$psscdetails=unserialize($detail->ssc_details);
				// dd($psscdetails);
				// $num=count($days);
				@endphp
					@if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
						@foreach($specific_salary_component_types as $ct)
							@php
							$ct_sum=0;

							
							@endphp
							@foreach($psscdetails['ssc_component_category'] as $key => $det)

							@php
							 // dd($psscdetails);
							 @endphp
								@if($det==$ct->id)
								@php
								$ct_sum+=$psscdetails['ssc_amount'][$key];
								if ($psscdetails['ssc_component_type'][$key]==1):
									$allowances+=$psscdetails['ssc_amount'][$key];
								elseif($psscdetails['ssc_component_type'][$key]==0):
									$deductions+=$psscdetails['ssc_amount'][$key];
								endif;
								@endphp
								

								@endif
								
							@endforeach
							<td>{{$ct_sum}}</td>
						@endforeach
					@else
						@foreach($specific_salary_component_types as $ct)
						<td>0.00</td>
						@endforeach
					@endif
				<td>{{$detail->ssc_allowances}}</td>
				<td>{{$detail->ssc_deductions}}</td>
				<td>{{$detail->paye}}</td>
				<td>{{$detail->union_dues}}</td>
				<td>{{($detail->basic_pay+$allowances)}}</td>
				<td>{{($detail->basic_pay+$allowances)-($deductions+$detail->paye+$detail->union_dues)}}</td>
				</tr>
				@php
					$sn++;
				@endphp
				@endif
			@endforeach
		
	</tbody>
</table>
@endif
@php
	$has_projects = $payroll->whereHas('payroll_details', function ($query) {
    	$query->where('payroll_type', 'project');
	})->get();
@endphp
@if($has_projects)
@foreach($salary_categories as $salary_category)
<table >
	<thead>
		<tr>
			<th>Project Payroll ({{$salary_category->name}})</th>
		</tr>
		<tr>
			<th></th>
			<th>Employee Number</th>
			<th>Employee Name</th>
			
			<th>Basic pay</th>
			@foreach($payroll->project_salary_components as $component)
			@if($component->pace_salary_category_id==$salary_category->id)
			<th>{{$component->name}}</th>
			@endif
			@endforeach
			
			@foreach($specific_salary_component_types as $ctt)
			<th>{{$ctt->name}}</th>
			@endforeach

			
			<th>Personal Allowances</th>
			<th>Personal Deductions</th>
			@if($salary_category->uses_tax==1)
			<th>PAYE</th>
			@endif
			<th>Union Dues</th>
			<th>Gross pay</th>
			<th>Net Pay</th>
			
		</tr>
	</thead>
	<tbody>
		@php
			$sn=1;

		@endphp
		@foreach ($payroll->payroll_details as $detail)
			@if($detail->payroll_type=='project' && $detail->user->project_salary_category_id==$salary_category->id)
			@php
				$allowances=0;
				$deductions=0;
				
			@endphp
				<tr>
					<td>{{$sn}}</td>
				<td>{{$detail->user->emp_num}}</td>
				<td>{{$detail->user->name}}</td>
				<td>{{$detail->basic_pay}}</td>
				@php
				$pdetails=unserialize($detail->sc_details);
				// $num=count($days);
				@endphp
					@foreach($payroll->project_salary_components as $component)
					@if($component->pace_salary_category_id==$salary_category->id)
					<td>
						@if($component->type==1 && isset($pdetails['sc_allowances'][$component->constant]))
							@php
								$allowances+=$pdetails['sc_allowances'][$component->constant];
							@endphp
							{{isset($pdetails['sc_allowances'][$component->constant])?$pdetails['sc_allowances'][$component->constant]:""}}
						@elseif($component->type==0 && isset($pdetails['sc_deductions'][$component->constant]))
							@php
								$deductions+=$pdetails['sc_deductions'][$component->constant];
							@endphp
							{{isset($pdetails['sc_deductions'][$component->constant])?$pdetails['sc_deductions'][$component->constant]:""}}
						@endif
					</td>
					@endif
					@endforeach
				@php
				$psscdetails=unserialize($detail->ssc_details);
				// $num=count($days);
				@endphp
					@if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
						@foreach($specific_salary_component_types as $ct)
							@php
							$ct_sum=0;

							
							@endphp
							@foreach($psscdetails['ssc_component_category'] as $key => $det)

							@php
							 // dd($psscdetails);
							 @endphp
								@if($det==$ct->id)
								@php
								$ct_sum+=$psscdetails['ssc_amount'][$key];
								if ($psscdetails['ssc_component_type'][$key]==1):
									$allowances+=$psscdetails['ssc_amount'][$key];
								elseif($psscdetails['ssc_component_type'][$key]==0):
									$deductions+=$psscdetails['ssc_amount'][$key];
								endif;
								@endphp
								

								@endif
								
							@endforeach
							<td>{{$ct_sum}}</td>
						@endforeach
					@else
						@foreach($specific_salary_component_types as $ct)
						<td>0.00</td>
						@endforeach
					@endif
				<td>{{$detail->ssc_allowances}}</td>
				<td>{{$detail->ssc_deductions}}</td>
				@if($salary_category->uses_tax==1)
				<td>{{$detail->paye}}</td>
				@endif
				<td>{{$detail->union_dues}}</td>
				<td>{{($detail->basic_pay+$allowances)}}</td>
				<td>{{($detail->basic_pay+$allowances)-($deductions+$detail->paye+$detail->union_dues)}}</td>
				</tr>
				@php
					$sn++;
				@endphp
				@endif
			@endforeach
		
	</tbody>
</table>
@endforeach
@endif
@if($pp->use_tmsa==1)
<table >
	<thead>
		<tr>
			<th>TMSA Payroll</th>
		</tr>
		<tr>
			<th></th>
			<th>Employee Number</th>
			<th>Employee Name</th>
			<th>Day Rate Onshore</th>
			<th>Number of Days Worked Onshore</th>
			<th>Total for Days Worked Onshore</th>
			<th>Day Rate Offshore</th>
			<th>Number of Days Worked Offshore</th>
			<th>Total for Days Worked Offshore</th>
			<th>Total Gross Pay</th>
			<th>Annual Total Gross Pay</th>
			<th>Annual Gross Pay + Employer Pension</th>
			<th>Annual Employee's Contribution</th>
			<th>Monthly Employee's Contribution</th>
			<th>Annual Employer Contribution</th>
			<th>Monthly Employer Contribution</th>
			<th>Employee Pension Exempted</th>
			<th>Children and DR Relief</th>
			<th>Consolidated Relief Allowance (CRA)</th>
			<th>Total Reliefs</th>
			<th>Taxable Income</th>
			<th>Total PAYE</th>
			<th>Annual PAYE</th>
			<th>Out of Station Allowance</th>
			<th>BRT Allowance</th>
			@foreach($payroll->tmsa_components as $component)
			<th>{{$component->name}}</th>
			@endforeach
			@foreach($specific_salary_component_types as $ct)
			<th>{{$ct->name}}</th>
			@endforeach

			
			<th>Personal Allowances</th>
			<th>Personal Deductions</th>
			
			<th>Net Pay</th>
			
		</tr>
	</thead>
	<tbody>
		@php
			$sn=1;

		@endphp
		@foreach ($payroll->tmsa_payroll_details as $detail)
			
			@php
				$allowances=0;
				$deductions=0;
				
			@endphp
				<tr>
					<td>{{$sn}}</td>
				<td>{{$detail->user->emp_num}}</td>
				<td>{{$detail->user->name}}</td>
				<td>{{$detail->onshore_day_rate}}</td>
				<td>{{$detail->days_worked_onshore}}</td>
				<td>{{$detail->onshore_day_rate*$detail->days_worked_onshore}}</td>
				<td>{{$detail->offshore_day_rate}}</td>
				<td>{{$detail->days_worked_offshore}}</td>
				<td>{{$detail->offshore_day_rate*$detail->days_worked_offshore}}</td>
				<td>{{$detail->total_gross_pay}}</td>
				<td>{{$detail->annual_gross_pay}}</td>
				<td>{{$detail->annual_gross_pay+($detail->annual_employee_pension_contribution/0.8)}}</td>
				<td>{{$detail->annual_employee_pension_contribution}}</td>
				<td>{{$detail->monthly_employee_pension_contribution}}</td>
				<td>{{$detail->annual_employee_pension_contribution/0.8}}</td>
				<td>{{$detail->monthly_employee_pension_contribution/0.8}}</td>
				<td>{{$detail->annual_employee_pension_contribution}}</td>
				<td>14000</td>
				<td>{{$detail->cra}}</td>
				<td>{{$detail->total_relief}}</td>
				<td>{{$detail->taxable_income}}</td>
				<td>{{$detail->annual_paye}}</td>
				<td>{{$detail->monthly_paye}}</td>
				<td>{{$detail->out_of_station_allowance}}</td>
				<td>{{$detail->brt_allowance}}</td>

				@php
				$pdetails=unserialize($detail->sc_details);
				// $num=count($days);
				@endphp
					@foreach($payroll->tmsa_components as $component)
					<td>
						@if($component->type==1 && isset($pdetails['sc_allowances'][$component->constant]))
							@php
								$allowances+=$pdetails['sc_allowances'][$component->constant];
							@endphp
							{{isset($pdetails['sc_allowances'][$component->constant])?$pdetails['sc_allowances'][$component->constant]:""}}
						@elseif($component->type==0 && isset($pdetails['sc_deductions'][$component->constant]))
							@php
								$deductions+=$pdetails['sc_deductions'][$component->constant];
							@endphp
							{{isset($pdetails['sc_deductions'][$component->constant])?$pdetails['sc_deductions'][$component->constant]:""}}
						@endif
					</td>
					@endforeach
				@php
				$psscdetails=unserialize($detail->ssc_details);
				// $num=count($days);
				@endphp
					@if($detail->ssc_allowances>0 ||$detail->ssc_deductions>0)
						@foreach($specific_salary_component_types as $ct)
							@php
							$ct_sum=0;

							
							@endphp
							@foreach($psscdetails['ssc_component_category'] as $key => $det)

							@php
							 // dd($psscdetails);
							 @endphp
								@if($det==$ct->id)
								@php
								$ct_sum+=$psscdetails['ssc_amount'][$key];
								if ($psscdetails['ssc_component_type'][$key]==1):
									$allowances+=$psscdetails['ssc_amount'][$key];
								elseif($psscdetails['ssc_component_type'][$key]==0):
									$deductions+=$psscdetails['ssc_amount'][$key];
								endif;
								@endphp
								

								@endif
								
							@endforeach
							<td>{{$ct_sum}}</td>
						@endforeach
					@else
						@foreach($specific_salary_component_types as $ct)
						<td>0.00</td>
						@endforeach
					@endif
				<td>{{$detail->personal_allowances}}</td>
				<td>{{$detail->personal_deductions}}</td>
				<td>{{$detail->netpay}}</td>
				{{-- <td>{{($detail->total_gross_pay+$allowances)-($deductions+$detail->monthly_paye)}}</td> --}}
				</tr>
				@php
					$sn++;
				@endphp
				
			@endforeach
		
	</tbody>
</table>

@endif