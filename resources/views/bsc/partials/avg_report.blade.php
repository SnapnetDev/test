   <table class="table table striped">
         	<thead>
         		<tr>
                <th>Employee ID</th>
                  <th>Employee name</th>
                  <th>Employment Date</th>
                  <th>Department</th>
                  <th>Designation</th>
         		<th>Score</th>
                <th>Behavioral Score</th>
                <th>Average</th>
                <th>Evaluated by</th>
         	</tr>
         	</thead>
         	<tbody>
         		
         	@foreach($evaluations as $evaluation)
            <tr>
            
            <td>{{$evaluation->user->emp_num}}</td>
            <td>{{$evaluation->user->name}}</td>
            <td>{{date("F j, Y", strtotime($evaluation->user->hiredate))}}</td>
            <td>{{$evaluation->user->job?$evaluation->user->job->department->name:''}}</td>
            <td>{{$evaluation->user->job?$evaluation->user->job->title:''}}</td>
            <td>{{$evaluation->score}}</td>
         	<td>{{$evaluation->behavioral_score}}</td>
            @php
               $average=($evaluation->score+$evaluation->behavioral_score)/2;
             @endphp
            <td>{{$average>1?round($average,1):"1.0"}}</td>
            <td>{{$evaluation->evaluator?$evaluation->evaluator->name:''}}</td>
            </tr>
         	@endforeach
         
         	</tbody>
         	
         </table>