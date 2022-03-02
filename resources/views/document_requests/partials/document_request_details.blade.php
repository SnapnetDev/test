
<div class="table-responsive">
	<h4>Approval Details ({{$document_request->workflow->name}})</h4>
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Stage</th>
			<th>Approved By</th>
			<th>Comment</th>
			<th>Status</th>
			<th>Time in Stage</th>

		</tr>
	</thead>
	<tbody>
		@foreach($document_request->document_approvals as $approval)
		<tr>
			<td>{{$approval->stage->name}}</td>
			<td>{{$approval->approver_id>0?$approval->approver->name:""}}</td>
			<td>{{$approval->comment}}</td>
			<td><span class=" tag   {{$approval->status==0?'tag-warning':($approval->status==1?'tag-success':'tag-danger')}}">{{$approval->status==0?'pending':($approval->status==1?'approved':'rejected')}}</span></td>
			<td>{{ $approval->created_at==$approval->updated_at?\Carbon\Carbon::parse($approval->created_at)->diffForHumans():\Carbon\Carbon::parse($approval->created_at)->diffForHumans($approval->updated_at) }}</td>
		</tr>
		@endforeach
	</tbody>

</table>
</div
