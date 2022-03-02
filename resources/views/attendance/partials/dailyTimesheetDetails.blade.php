


<table>
	<thead>
		<tr>
			<th>Attendance Report for {{date('D dS M, Y ',strtotime($date))}}</th>
			
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>No of Employees Present</th>
			<th>{{count($users)}}</th>
		</tr>
		<tr>
			<th>No of Employees Absent</th>
			<th>{{count($absentees)}}</th>
		</tr>
		<tr>
			<th>No of Employees Early</th>
			<th>{{$earlys}}</th>
		</tr>
		<tr>
			<th>No of Employees Late</th>
			<th>{{$lates}}</th>
		</tr>
		<tr>
			<th>Employees Present</th>
		</tr>
		<tr>
			<th>Staff ID</th>
			<th>Employee Name</th>
			<th>Clock In time</th>
			<th>Clock Out time</th>
			<th>Status</th>
			<th>Hours Worked</th>
		</tr>
		

	@foreach ($attendances as $attendance)
	<tr>
	    	<td>{{$attendance['emp_num']}}</td>
	    	<td>{{$attendance['name']}}</td>
	    	<td>{{date('D dS M, Y @ h:i:s a',strtotime($date.$attendance['first_clock_in']))}}</td>
	    	<td>@if($attendance['first_clock_out']=="" ||$attendance['first_clock_out']==$attendance['first_clock_in']) {{__('Nill')}} @else {{$attendance['spills']==1?date('D dS M, Y @ h:i:s a',strtotime($date.$attendance['first_clock_out']. "+1 day")):date('D dS M, Y @ h:i:s a',strtotime($date.$attendance['first_clock_out']))}} @endif</td>
	    	<td style=" {{$attendance['diff']>=0?'color:white;background: #050;':'color:white;background: #500;'}}">{{$attendance['diff']>=0?'Early':'Late'}}</td>
	    	<td>{{$attendance['hours']}}</td>
	    	
	    	</tr>
	   @endforeach
	   <tr></tr>
	   <tr></tr>
	   <tr>
			<th>Employees Absent</th>
		</tr>
		<tr>
			<th>Staff ID</th>
			<th>Employee Name</th>
		</tr>
	   @foreach ($absentees as $absentee)
	<tr>
	    	<td>{{$absentee->name}}</td>
	    	<td>{{$absentee->emp_num}}</td>
	    	
	    	</tr>
	   @endforeach

	    
		
	</tbody>
	
</table>
