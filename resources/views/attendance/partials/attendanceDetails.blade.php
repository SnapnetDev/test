<table class="table table-striped">
	<thead>
		<tr>
			<th>Clock In</th>
			<th>Clock Out</th>
		</tr>
	</thead>
	<tbody>
		@foreach($attendancedetails as $detail)
		<tr>
			<td>{{date(' h:i:s a',strtotime($detail->clock_in))}} @if(isset($detail->clock_in_lat) && isset($detail->clock_in_long))<a target="_blank" href="http://www.google.com/maps/place/{{$detail->clock_in_lat}},{{$detail->clock_in_long}}">View On Map</a>@endif</td>
			<td>
				@if($detail->clock_out)
					{{date(' h:i:s a',strtotime($detail->clock_out))}} @if(isset($detail->clock_out_lat) && isset($detail->clock_out_long))<a target="_blank" href="http://www.google.com/maps/place/{{$detail->clock_out_lat}},{{$detail->clock_out_long}}">View On Map</a>@endif
				@else
					No Clock Out
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>