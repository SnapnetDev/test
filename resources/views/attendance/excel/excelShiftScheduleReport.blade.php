@if(isset($special))
    <table class="table">
        <thead>
        <tr>
            <th>Staff Name</th>
            @foreach($dates as $date)
                <th>{{ $date }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td><a style="text-decoration: none;" href="{{ route('attendance.staff',$user->id) }}">{{$user->name}}</a></td>
                @foreach($dates as $date)
                    @php($user_shift=$users_shifts->where('user_id',$user->id)->where('sdate',$date)->first())
                    <td>
                        @php($lr=$leave_requests->where('user_id',$user->id)->where('start_date','<=',$date)->where('end_date','>=',$date)->first())
                        @if($lr)
                            {{ isset($lr->leave->name) ? '' : 'A-Leave' }}
                            @if($lr->leave->name=='Sick Leave')
                                S-Leave
                            @elseif($lr->leave->name=='Casual Leave')
                                C-Leave
                            @endif
                        @else
                            {{ isset($user_shift) ? $user_shift->shift->type : 'Default' }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Staff</th>
            @foreach($dates as $date)
                <th>{{ $date }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $user)
            <tr>
                <td>{{$user->name}}</td>
                @foreach($user['dates'] as $date)
                    <th>{{ $date }}</th>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@endif