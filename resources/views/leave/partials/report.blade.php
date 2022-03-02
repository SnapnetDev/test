   <table class="table table striped">
         	<thead>
         		<tr>
                    <th>Employee ID</th>
              <th>Employee name</th>
              <th>Employment Date</th>
              <th>Employment Grade</th>
              <th>Department</th>
              <th>Designation</th>
         		<th>Leave Type</th>
         		<th>Starts</th>
         		<th>Ends</th>
         		<th>Leave Length</th>
         		<th>Reason</th>
         		<th>Approval Status</th>
         		<th>With Pay</th>
         	</tr>
         	</thead>
         	<tbody>
         		
         	@foreach($leave_requests as $leave_request)
          <tr>
            
            <td>{{$leave_request->user->emp_num}}</td>
            <td>{{$leave_request->user->name}}</td>
            <td>{{date("F j, Y", strtotime($leave_request->user->hiredate))}}</td>
             <td>{{$leave_request->user->grade?$leave_request->user->grade->level:''}}</td>
            <td>{{$leave_request->user->job?$leave_request->user->job->department->name:''}}</td>
            <td>{{$leave_request->user->job?$leave_request->user->job->title:''}}</td>
         	<td>{{$leave_request->leave_name}}</td>
         		<td>{{date("F j, Y", strtotime($leave_request->start_date))}}</td>
         		<td>{{date("F j, Y", strtotime($leave_request->end_date))}}</td>
         		<td><span class=" tag tag-outline  {{$leave_request->priority==0?'tag-success':($leave_request->priority==1?'tag-warning':'tag-danger')}}">{{$leave_request->priority==0?'normal':($leave_request->priority==1?'medium':'high')}}</span></td>
         		<td>{{$leave_request->reason}}</td>
         		<td><span class=" tag   {{$leave_request->status==0?'tag-warning':($leave_request->status==1?'tag-success':'tag-danger')}}">{{$leave_request->status==0?'pending':($leave_request->status==1?'approved':'rejected')}}</span></td>
         		<td>{{$leave_request->paystatus==0?'without pay':'with pay'}}</td>
         		
        </tr>
         	@endforeach
         
         	</tbody>
         	
         </table>