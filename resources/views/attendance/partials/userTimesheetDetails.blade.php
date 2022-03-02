@php
	$days=unserialize($detail->tdays);
	$num=count($days);
@endphp
<h4>{{$detail->user->name}}</h4>
<div class="table-responsive">
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Date</th>
			@for($i=1;$i<=$num;$i++)
			<th>{{$i}}</th>
			@endfor
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Hours</th>
			@for($i=1;$i<=$num;$i++)
			<td>{{$days[$i]}}</td>
			@endfor
		</tr>
	</tbody>
	
</table>
</div>