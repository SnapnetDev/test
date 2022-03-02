@php
	$days=cal_days_in_month(CAL_GREGORIAN,$datas->month,$datas->year);
@endphp
<table class="table table-striped">
	<thead>
		<tr>
			<th>User</th>
            <th>Position</th>
            @for($i=1;$i<=$days;$i++)
            
            <th style="background: {{date('N',strtotime("$datas->year-$datas->month-$i"))>5?"#f6f02e":($holidays->contains('date',"$datas->year-$datas->month-$i")? "#f6f02e":"#495dee")}}">{{$i}}</th>
            
            @endfor
            <th>Total Hours</th>
            <th>Weekday Hours</th>
            <th>Basic Work Hours</th>
            <th>Overtime Weekday Hours</th>
            <th>Overtime Holiday Hours</th>
            <th>Overtime Saturday Hours</th>
            <th>Overtime Sunday Hours</th>
            <th>Expected Work Hours</th>
            <th>Expected Work Days</th>
		</tr>
	</thead>
	<tbody>
		@foreach($datas->timesheetdetails as $detail)
		@php
			$tdays=unserialize($detail->tdays);
		@endphp
		<tr>
			<td>{{$detail->user->name}}</td>
             <td>{{$detail->user->job->name}}</td>
             @for($i=1;$i<=$days;$i++)
			<td>{{$tdays[$i]}}</td>
			@endfor
             <td>{{$detail->total_hours}}</td>
             <td>{{$detail->weekday_hours}}</td>
             <td>{{$detail->basic_work_hours}}</td>
             <td>{{$detail->overtime_weekday_hours}}</td>
             <td>{{$detail->overtime_holiday_hours}}</td>
             <td>{{$detail->overtime_saturday_hours}}</td>
             <td>{{$detail->overtime_sunday_hours}}</td>
              <td>{{$detail->expected_work_hours}}</td>
               <td>{{$detail->expected_work_days}}</td>
		</tr>
		@endforeach
	</tbody>
</table>