<div class="table-responsive">
    <table class="table table striped">
        <thead>
        <tr>
            <th>EMPID</th>
            <th>{{__('NAME')}}</th>
            <th class="hidden-sm-down">{{__('CLOCK IN')}}</th>
            <th class="hidden-sm-down">{{__('SHIFT STARTS')}}</th>
            <th class="hidden-sm-down">{{__('SHIFT ENDS')}}</th>
            <th class="hidden-sm-down">{{__('CLOCK OUT')}}</th>
            <th class="hidden-sm-down">{{__('HOURS WORKED')}}</th>
            <th class="hidden-sm-down">{{__('OVERTIME')}}</th>
            <th class="hidden-sm-down">{{__('SHIFT')}}</th>
            <th class="hidden-sm-down">{{__('STATUS')}}</th>
        </tr>
        </thead>
        <tbody>

        @if(count($attendances)>0)
            @foreach($attendances as $attendance)

                <tr>
                    <td>{{$attendance->user->emp_num}}</td>
                    <td>{{$attendance->user->name}}</td>
                    <td><span class="text text-success"><b>{{$attendance->first_clockin}}</b></span></td>
                    <td>{{$attendance->shift_start}}</td>
                    <td>{{$attendance->shift_end}}</td>
                    <td>{{$attendance->last_clockout}}</td>
                    <td>{{$attendance->hours_worked}}</td>
                    <td>{{$attendance->overtime}}</td>
                    <td>{{$attendance->shift_name}}</td>
                    <td><span class="tag {{$attendance->status=='early'?'tag-success':'tag-danger'}}">{{ $attendance->status }}</span></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td  colspan="11" style="text-align: center">
                    <b style="font-size:20px;"
                       class="text-success"> {{__('No Attendance Report For Today Yet')}}</b>
                </td>
            </tr>
        @endif

        </tbody>
    </table>
</div>