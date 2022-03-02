<table >
	<thead>

		<tr>
			<th></th>
			<th>Employee Number</th>
			<th>Employee Name</th>

            <th>Basic pay</th>
            @foreach ($components as $key=>$component)
            <th>
                    {{$component['name']}}
            </th>

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
            <th>Payroll Type</th>

		</tr>
	</thead>
	<tbody>
            @php
			$sn=1;

        @endphp
        @foreach ($payroll->payroll_details as $detail)
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
                @foreach ($components as $key=>$component)
                <td>
                    @if($component['type']==1 && isset($pdetails['sc_allowances'][$component['constant']]))
                        @php
                            $allowances+=$pdetails['sc_allowances'][$component['constant']];
                        @endphp
                        {{isset($pdetails['sc_allowances'][$component['constant']])?$pdetails['sc_allowances'][$component['constant']]:""}}
                    @elseif($component['type']==0 && isset($pdetails['sc_deductions'][$component['constant']]))
                        @php
                            $deductions+=$pdetails['sc_deductions'][$component['constant']];
                        @endphp
                        {{isset($pdetails['sc_deductions'][$component['constant']])?$pdetails['sc_deductions'][$component['constant']]:""}}
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
                {{--<td>{{($detail->basic_pay+$allowances)-($deductions+$detail->paye+$detail->union_dues)}}</td>--}}
                {{--<td>{{$detail->gross_pay}}</td>--}}
                <td>{{($detail->basic_pay+$allowances-($deductions+$detail->paye+$detail->union_dues))}}</td>
                <td>{{$detail->payroll_type}}</td>
				</tr>
				@php
					$sn++;
				@endphp

        @endforeach


        </tr>
    </tbody>
</table>
