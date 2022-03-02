   <table class="table table striped">
         	<thead>
         		<tr>
                    <th>Employee ID</th>
              <th>Employee name</th>
              <th>Employment Date</th>
              <th>Department</th>
              <th>Designation</th>
         	</tr>
         	</thead>
         	<tbody>
         		
         	@foreach($leave_plans as $leave_plan)
          <tr>
            
            <td>{{$leave_plan->user->emp_num}}</td>
            <td>{{$leave_plan->user->name}}</td>
            <td>{{date("F j, Y", strtotime($leave_plan->user->hiredate))}}</td>
            <td>{{$leave_plan->user->job?$leave_plan->user->job->department->name:''}}</td>
            <td>{{$leave_plan->user->job?$leave_plan->user->job->title:''}}</td>
         		
        </tr>
         	@endforeach
         
         	</tbody>
         	
         </table>