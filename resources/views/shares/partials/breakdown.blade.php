<table class="table table-striped">
	<thead>
		<tr>
			<th>Shares</th>
			<th>Date Due</th>
			<th>Status</th>
			@if(Auth::user()->role=='3')
				<th>Force Vest</th>
				<th>Revoke</th>
			@endif
		</tr>
	</thead>
	<tbody>
		@foreach($breakdowns as $breakdown)
		<tr>
			<td>{{ number_format($breakdown->no_of_shares) }}</td>
			<td>{{ $breakdown->date_vested }}</td>
			<td><span class="badge badge-warning">{{ $breakdown->status }}</span></td>

			@if(Auth::user()->role=='3')
				@if($breakdown->status=='pending')
					<td><a href="{{ url('vest/breakdown',$breakdown->id) }}">Vest</a></td>
					<td><a href="{{ url('revoke/share',$breakdown->id) }}">Revoke</a></td>
				@elseif($breakdown->status=='vested')
					<td></td>
					<td><a href="{{ url('revoke/share',$breakdown->id) }}">Revoke</a></td>
				@elseif($breakdown->status=='cancelled')
					<td></td>
					<td></td>
				@elseif($breakdown->status=='withdrawn')
					<td></td>
					<td></td>
				@endif
			@endif

		</tr>
		@endforeach
	</tbody>
</table>