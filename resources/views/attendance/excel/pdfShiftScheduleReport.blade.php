<style>
    *
    {
        font-size: 9px;
    }
</style>
<table cellspacing="0" border="0">
    <colgroup width="123"></colgroup>
    <colgroup span="14" width="85"></colgroup>
    <colgroup span="4" width="81"></colgroup>
    <colgroup width="86"></colgroup>
    <colgroup width="84"></colgroup>
    <colgroup span="6" width="81"></colgroup>
    <colgroup width="84"></colgroup>
    <colgroup span="4" width="81"></colgroup>
    <tbody><tr>
        <td colspan="29" height="62" align="center" valign="middle"><font color="#8EB4E3">ITC DEPARTMENT<br>Monthly Roaster<br></font></td>
        <td align="left" valign="bottom"><font color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font color="#000000"><br></font></td>
    </tr>
    <tr>
        <td height="19" align="left" valign="middle"><b><font face="Arial" color="#000000">Period Start Date :</font></b></td>
        <td style="border-left: 1px solid #000000" colspan="30" align="center" valign="middle" bgcolor="#F2F2F2" sdval="43983" sdnum="1033;0;[$-F800]DDDD\, MMMM DD\, YYYY"><b><font face="Arial" color="#000000">{{ collect($datesFull)->first() }}</font></b></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
    </tr>
    <tr>
        <td height="11" align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#FF0000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#FF0000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#FF0000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#FF0000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#FF0000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
        <td align="left" valign="bottom"><font face="Arial" color="#000000"><br></font></td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="19" align="center" valign="middle" bgcolor="#B3A2C7"><b><font face="Arial" color="#000000">Day</font></b></td>
        @foreach($fullDays as $day)
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign="bottom" bgcolor="#B3A2C7" sdnum="1033;0;[$-F400]H:MM:SS AM/PM"><b><font face="Arial" color="#000000">{{ $day }}</font></b></td>
        @endforeach
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="19" align="center" valign="middle" bgcolor="#B3A2C7"><b><font face="Arial" color="#000000">Week</font></b></td>
        @foreach($fullWeeks as $week)
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign="bottom" bgcolor="#B3A2C7" sdval="23" sdnum="1033;"><b><font face="Arial" color="#000000">{{ $week }}</font></b></td>
        @endforeach
    </tr>
    <tr>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="19" align="center" valign="middle" bgcolor="#B3A2C7"><b><font face="Arial" color="#000000">Date</font></b></td>
        @foreach($fullDates as $date)
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign="bottom" bgcolor="#B3A2C7" sdval="43983" sdnum="1033;0;M/D;@"><b><font face="Arial" color="#000000">{{ $date }}</font></b></td>
        @endforeach
    </tr>
    @foreach($users as $user)
        <tr>
        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="20" align="left" valign="bottom" bgcolor="#F2F2F2"><font face="Arial" color="#000000">{{$user->name}}</font></td>
            @foreach($dates as $date)
                @php($user_shift=$users_shifts->where('user_id',$user->id)->where('sdate',$date)->first())
                <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign="bottom" bgcolor=" {{ isset($user_shift) ? $user_shift->shift->color_code : '#C8E7A8' }}"><font face="Arial" color="#000000">
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
                    </font></td>
            @endforeach
    </tr>
    @endforeach
    </tbody></table>