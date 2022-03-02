<table>
    <thead>
    <tr>
        <th>EMPID</th>
        <th>{{__('NAME')}}</th>
        <th>{{__('TIME IN')}}</th>
        <th>{{__('TIME OUT')}}</th>
        <th>{{__('REASON')}}</th>
        <th>{{__('STATUS')}}</th>
    </tr>
    </thead>
    <tbody>

    @if(count($manuals)>0)
        @foreach($manuals as $manual)
            <tr>
                <td><a style="text-decoration: none;" href="{{ route('attendance.staff',$manual->user->id) }}">{{$manual->user->emp_num}}</a></td>
                <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$manual->user->id) }}">{{$manual->user->name}}</a></td>
                <td>
                    <span class="text text-success"><b>{{$manual->time_in}}</b></span>
                </td><td>
                    <span class="text text-success"><b>{{$manual->time_out}}</b></span>
                </td>
                <td>{{$manual->reason}}</td>
                <td>{{$manual->status}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="7" style="text-align: center">
                <b style="font-size:20px;"
                   class="text-success"> {{__('No Manual Attendance For Today Yet')}}</b>
            </td>

        </tr>
    @endif

    </tbody>
</table>