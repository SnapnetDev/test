<table class="table table-striped">
	<thead>
		<tr>
			<th>Date</th>
			<th>Status</th>
			<th>Hours Worked</th>
			<th>Overtime</th>
		</tr>
	</thead>
	<tbody>
		@foreach($details as $detail)
		<tr>
			<td>{{ $detail->date }}</td>
			<td>{{ $detail->status }}</td>
			<td>{{ $detail->hours_worked }}</td>
			<td>{{ $detail->overtime }}</td>
		</tr>
		@endforeach
	</tbody>
</table>