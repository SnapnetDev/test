
@if(count($user_daily_shifts)>0)

	<table class="table table-hover dataTable table-striped w-full" id="detailsTable" data-plugin="dataTable">
	<thead>
		<tr>
			<th>Employee Name </th>
			<th>Department</th>
			<th>Shift Type</th>
			<th>Shift Starts</th>
			<th>Shift Ends</th>
		</tr>
	</thead>
	<tbody>
		@foreach($user_daily_shifts as $dshift)
		<tr>
			<td>{{$dshift->user->name}}</td>
			<td>{{$dshift->user->job?$dshift->user->job->department->name:'No Department'}}</td>
			<td>{{$dshift->shift->type}}</td>
			<td>{{date('D dS M, Y @ h:i:s a',strtotime($dshift->starts))}}</td>
			<td>{{date('D dS M, Y @ h:i:s a',strtotime($dshift->ends))}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@else
No Shifts has been assigned for this day
@endif