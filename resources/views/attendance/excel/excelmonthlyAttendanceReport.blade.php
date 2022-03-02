<table class="table table striped">
    <thead>
    <tr>
        <th>EMPID</th>
        <th>{{__('NAME')}}</th>
        <th>{{__('ROLE')}}</th>
        <th>{{__('HOURS WORKED')}}</th>
        <th>{{__('OVERTIME WORKED')}}</th>
        <th>{{__('EARLY')}}</th>
        <th>{{__('LATE')}}</th>
        <th>{{__('OFF')}}</th>
        <th>{{__('ABSENT')}}</th>
        <th>{{__('PRESENT')}}</th>
        <th>{{__('SATURDAYS')}}</th>
        <th>{{__('SUNDAYS')}}</th>
        <th>{{__('HOLIDAYS')}}</th>
        {{--<th>{{__('AMOUNT')}}</th>--}}
    </tr>
    </thead>
    <tbody>

    @foreach($users as $user)
        <tr>
            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$user->id) }}">{{$user->emp_num}}</a></td>
            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$user->id) }}">{{$user->name}}</a></td>
            <td>{{$user->role->name}}</td>
            <td>{{$user->total_hours}}</td>
            <td>{{$user->overtime_worked}}</td>
            <td> <span class="text text-success">{{$user->earlys}}</span></td>
            <td> <span class="text text-danger">{{$user->lates}}</span></td>
            <td> <span class="text text-danger">{{$user->offs}}</span></td>
            <td> <span class="text text-danger">{{$user->absents}}</span></td>
            <td> <span class="text text-danger">{{$user->present}}</span></td>
            <td> <span class="text text-danger">{{$user->saturdays}}</span></td>
            <td> <span class="text text-danger">{{$user->sundays}}</span></td>
            <td> <span class="text text-danger">{{$user->holidays}}</span></td>
            {{--<td> <span class="text text-success">{{number_format($user->amount,2)}}</span></td>--}}
        </tr>
    @endforeach

    </tbody>
</table>