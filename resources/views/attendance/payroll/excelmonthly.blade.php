<table>
    <thead>
    <tr>
        <th>EMPID</th>
        <th>{{__('NAME')}}</th>
        <th>{{__('ROLE')}}</th>
        <th>{{__('DAYS EARLY')}}</th>
        <th>{{__('DAYS LATE')}}</th>
        <th>{{__('DAYS ABSENT')}}</th>
        <th>{{__('DAYS OFF')}}</th>
        <th>{{__('Max Expected')}}</th>
        <th>{{__('Amount Paid')}}</th>
    </tr>
    </thead>
    <tbody>

    @foreach($users_payrolls as $pay)
        <tr>
            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$pay->user->id) }}?from={{ $payroll->start }}&to={{ $payroll->end }}">{{$pay->user->emp_num}}</a></td>
            <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$pay->user->id) }}?from={{ $payroll->start }}&to={{ $payroll->end }}">{{$pay->user->name}}</a></td>
            <td>{{$pay->role->name}}</td>
            <td>{{$pay->early}}</td>
            <td>{{$pay->late}}</td>
            <td>{{$pay->absent}}</td>
            <td>{{$pay->off}}</td>
            <td>{{number_format($pay->amount_expected,2)}}</td>
            <td>{{number_format($pay->amount_received,2)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>