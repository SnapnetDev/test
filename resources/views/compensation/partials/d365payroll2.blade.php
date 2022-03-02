

{{-- Pension --}}
{{-- tax --}}
{{-- loan --}}
<table>
	<thead>
		<tr>
			<th>Code</th>
			<th>date</th>
			<th>GL Code</th>
			<th>Description</th>
			<th>Project Code</th>
			<th>DR</th>
			<th>CR</th>
		</tr>
	</thead>
	<tbody>
		@php
			$total_gross=0;
		@endphp
		{{-- Gross salary --}}
		@foreach ($departments as $department)
			@php
				$users=$department->users()->whereHas('payroll_details', function ($query) use($payroll){
					$query->where('payroll_id',  $payroll->id);
				})->get();
				// $posts = App\Post::whereHas('comments', function ($query) {
				//     $query->where('content', 'like', 'foo%');
				// })->get();
				$department_total_gross=0;

			@endphp
			<tr>
			<td>PAYROLL-{{date('ym',strtotime($date))}}</td>
			<td>{{date('dmy',strtotime($date))}}</td>
			@foreach ($users as $user)
				@php
					$department_total_gross+=$user->payroll_details()->where('payroll_id',$payroll->id)->first()->gross_pay;
				@endphp
			@endforeach
			<td>{{'6411300'}}</td>
			<td>GROSS SAL -{{date('dmy',strtotime($date))}}-{{$department->name}}</td>
			<td></td>
			<td>{{number_format($department_total_gross,2)}}</td>
			<td></td>

			</tr>
			@php
				$total_gross+=$department_total_gross;
			@endphp

		@endforeach
		<tr>
			<td>PAYROLL-{{date('ym',strtotime($date))}}</td>
			<td>{{date('dmy',strtotime($date))}}</td>
			
			<td>{{'6411300'}}</td>
			<td>National Salary -{{date('dmy',strtotime($date))}}-</td>
			<td></td>
			<td></td>
			<td>{{number_format($total_gross,2)}}</td>

		</tr>
		{{-- Allowances --}}
		@php
			$details=$payroll->payroll_details()->where('ssc_allowances','>','0')->get();
		@endphp
		@if ($details)
			@foreach ($details as $detail)
				@php
					$pdetails=unserialize($detail->ssc_details);
				@endphp
				@foreach($pdetails['ssc_allowances'] as $key=>$allowance)
				<tr>
					<td>PAYROLL-{{date('ym',strtotime($date))}}</td>
					<td>{{date('dmy',strtotime($date))}}</td>
					<td>{{$pdetails['ssc_gl_code'][$key]}}</td>
					<td>ALOW -{{date('dmy',strtotime($date))}}-{{$pdetails['ssc_component_names'][$key]}}</td>
					<td>{{$pdetails['ssc_project_code'][$key]}}</td>
					<td>{{number_format($allowance,2)}}</td>
				</tr>
				@endforeach
			@endforeach
		@endif
		{{-- PAYE --}}
		@php
			$total_paye=0;
		@endphp
		@foreach ($departments as $department)
			@php
				$users=$department->users()->whereHas('payroll_details', function ($query) use($payroll){
					$query->where('payroll_id',  $payroll->id);
				})->get();
				$department_total_paye=0;
			@endphp
			<tr>
			<td>PAYROLL-{{date('ym',strtotime($date))}}</td>
			<td>{{date('dmy',strtotime($date))}}</td>
			@foreach ($users as $user)
				@php
					$department_total_paye+=$user->payroll_details()->where('payroll_id',$payroll->id)->first()->paye;
				@endphp
			@endforeach
			<td>{{'4310000'}}</td>
			<td>PAYE -{{date('dmy',strtotime($date))}}-{{$department->name}}</td>
			<td></td>
			<td>{{number_format($department_total_paye,2)}}</td>
			<td></td>

		</tr>
		@php
			$total_paye+=$department_total_paye;
		@endphp

		@endforeach
		<tr>
			<td>PAYROLL-{{date('ym',strtotime($date))}}</td>
			<td>{{date('dmy',strtotime($date))}}</td>
			
			<td>{{'4310000'}}</td>
			<td>National staff PAYE -{{date('dmy',strtotime($date))}}-</td>
			<td></td>
			<td></td>
			<td>{{number_format($total_paye,2)}}</td>

		</tr>
		{{-- Deductions--}}

		@php
		$deductions= \App\SalaryComponent::where(['type'=>0,'status'=>1])->get();
			
		@endphp
				@forelse ($deductions as $deduction)
					@php
						$total_deduction=0;
					@endphp
							@foreach ($departments as $department)
									@php
										$ddt=0;
										$users=$department->users()->whereHas('payroll_details', function ($query) use($payroll){
											$query->where('payroll_id',  $payroll->id);
										})->get();
										
									@endphp
									<tr>
									<td>PAYROLL-{{date('ym',strtotime($date))}}</td>
									<td>{{date('dmy',strtotime($date))}}</td>
										@foreach ($users as $user)
											@php
												$pdetail=$user->payroll_details()->where('payroll_id',$payroll->id)->first();
												$pdetails=unserialize($pdetail->details);
												 foreach($pdetails['deductions'] as $key=>$detail_deduction):
													 if ($key==$deduction->constant) {
													 	$ddt+=$detail_deduction;
													 }
							    					endforeach;
											@endphp
										@endforeach
									<td>{{$deduction->gl_code}}</td>
									<td>{{$deduction->name}} -{{date('dmy',strtotime($date))}}-{{$department->name}}</td>
									<td>{{$deduction->project_code}}</td>
									<td>{{number_format($ddt,2)}}</td>
									<td></td>

									</tr>
									@php
										$total_deduction+=$ddt;
									@endphp

							@endforeach
					<tr>
					<td>PAYROLL-{{date('ym',strtotime($date))}}</td>
					<td>{{date('dmy',strtotime($date))}}</td>
					
					<td>{{$deduction->gl_code}}</td>
					<td>National staff {{$deduction->name}} -{{date('dmy',strtotime($date))}}-</td>
					<td>{{$deduction->project_code}}</td>
					<td></td>
					<td>{{number_format($total_deduction,2)}}</td>

					</tr>
				@empty
				@endforelse
		
		
	</tbody>
</table>