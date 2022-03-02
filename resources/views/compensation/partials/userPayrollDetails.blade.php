@php
	$pdetails=unserialize($detail->details);
	// $num=count($days);
@endphp
<h4>{{$detail->user->name}}</h4>
<div class="table-responsive">
<table class="table table-striped ">
	
	<tbody>
		<tr>
			
			<th>Basic Pay</th>
			<td style="text-align: right">&#8358;{{number_format($detail->basic_pay,2)}}</td>
			
		</tr>
	</tbody>
	
</table>
<h4>Allowances</h4>
<table class="table table-striped ">
	
	<tbody>
		@foreach($pdetails['allowances'] as $key=>$allowance)
		<tr>
			
			<th>{{$pdetails['component_names'][$key]}}</th>
			<td style="text-align: right">&#8358;{{number_format($allowance,2)}}</td>
			
		</tr>
		@endforeach
	</tbody>
	
</table>

<h4>Deductions</h4>
<table class="table table-striped ">
	
	<tbody>
		@foreach($pdetails['deductions'] as $key=>$deduction)
		<tr>
			
			<th>{{$pdetails['component_names'][$key]}}</th>
			<td style="text-align: right">-&#8358;{{number_format($deduction,2)}}</td>
			
		</tr>
		@endforeach
		<tr>
			<th>Income Tax</th>
			<td style="text-align: right">&#8358;{{number_format($detail->paye,2)}}</td>
		</tr>
		@if($detail->user->union)
        <tr>
          <th >Union Dues</th>
          <td style="text-align: right">&#8358;{{number_format($detail->union_dues,2)}}</td>
        </tr>
    @endif
	</tbody>
	
</table>
<hr>
<h4><span class="">Net Salary</span><span class="pull-right">&#8358;{{number_format(($detail->basic_pay+$detail->allowances)-($detail->deductions+$detail->paye+$detail->union_dues),2)}}</span></h4>
</div>