@php
	$begin = new \DateTime($from);
			$end = new \DateTime($to. '+1 days');
	    	$interval = \DateInterval::createFromDateString('1 day');
			$period = new \DatePeriod($begin, $interval, $end);
			
@endphp


<table>
	<thead>
		<tr>
			<th>Employee Name</th>
			<th>Employee Id</th>
			@foreach ($period as $dt) 
			<th>{{$dt->format(" Y-m-d")}}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>

	@foreach ($users as $user)
	<tr>
	    	<td>{{$user->name}}</td>
	    	<td>{{$user->emp_num}}</td>
	    	@php
	    		$index=1;
	    	@endphp
			@foreach ($period as $dt) 
			<td>{{$attendances[$user->id]['day_'.$index]['hours']}}</td>
	    	@php
	    		$index++;
	    	@endphp
	    	
	    	@endforeach
	    	</tr>
	   @endforeach

	    
		
	</tbody>
	
</table>
